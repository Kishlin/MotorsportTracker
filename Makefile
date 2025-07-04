include .env
-include .env.local

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

CACHE=composer-cache composer node-cache node
VOL_CACHE?=$(shell docker volume inspect -f '{{ index .Mountpoint }}' cache)

# Env
.env.local:
	@touch .env.local

# 🐘 Composer
.PHONY: composer-install
composer-install: CMD=install

.PHONY: composer-update
composer-update: CMD=update

.PHONY: composer-require
composer-require: CMD=require
composer-require: INTERACTIVE=-ti --interactive

.PHONY: composer-require-module
composer-require-module: CMD=require $(module)
composer-require: INTERACTIVE=-ti --interactive

.PHONY: composer
composer-install composer-update composer-require composer-require-module: .env.local .docker-cache
	@docker-compose exec backend php /usr/local/bin/composer $(CMD) \
			--working-dir=/app \
			--no-ansi

##> Project
docker-compose.yaml:
	@cp docker-compose.yaml.dist docker-compose.yaml
	@sed -i "s/<DOCKER_USER_ID>/$(shell $(shell echo id -u ${USER}))/g" $@
	@sed -i "s/<DOCKER_USER>/$(shell echo ${USER})/g" $@

.docker-cache:
	@touch .docker-cache
	@docker volume create --name=cache;

vendor: composer-install

node_modules:
	@docker-compose exec frontend npm install

cache: $(CACHE)
$(CACHE): .docker-cache
	@if [ ! -d "$(VOL_CACHE)/$@" ]; then \
	sudo mkdir -pm 777 $(VOL_CACHE)/$@; \
	fi;

.INTERMEDIATE: .docker-cache

.PHONY: help setup start stop clean containers node_modules

help:
	@echo "Run make start_working"

setup: .env.local docker-compose.yaml

containers: setup $(CACHE)
	@echo "Starting services"
	@docker-compose up -d --remove-orphans

stop:
	@docker-compose down

clean:
	@if [ -f "./docker-compose.yaml" ]; then \
		docker-compose down; \
	fi;
	@sudo rm -rf docker-compose.yaml vendor apps/MotorsportTracler/Frontend/node_modules apps/MotorsportTracler/Frontend/build

start: containers vendor db.core.reload db.core.reload.test db.cache.reload db.cache.reload.test db.client.reload db.client.reload.test db.admin.reload db.admin.reload.test
	@echo "All services should be running."
	@echo "    Backoffice: http://localhost:8040/monitoring/check-health"
	@echo "    Backend: http://localhost:8030/monitoring/check-health"
	@echo "You can access fronts services."
	@echo "    Frontend: http://localhost:3030/"
	@echo "    Admin: http://localhost:3040/"
	@echo "Ports may differ if overridden in the .env.local file."
	@echo "Run static analysis: \`make complete-analysis\` (see Makefile for more options)."


##> Apps

.PHONY: build-publisher build-processor run-publisher run-processor

build-publishers:
	@echo "Building Golang apps in ScrappingCommandPublishers"
	@cd apps/ScrappingCommandPublishers && go mod tidy
	@cd apps/ScrappingCommandPublishers && go build -o build/scrape-series series.go
	@cd apps/ScrappingCommandPublishers && go build -o build/scrape-seasons seasons.go
	@cd apps/ScrappingCommandPublishers && go build -o build/scrape-events events.go

build-processor:
	@echo "Building Golang app CommandsProcessor"
	@cd apps/CommandsProcessor && go build -o build/CommandsProcessor main.go

run-processor:
	@echo "Running Golang app CommandsProcessor"
	@docker-compose exec golang /app/apps/CommandsProcessor/build/CommandsProcessor

##> Helpers
.PHONY: xdebug.on xdebug.off frontend.sh frontend.build
.PHONY: db.core.connect db.core.reload db.core.reload.test
.PHONY: db.admin.connect db.admin.reload db.admin.reload.test
.PHONY: db.cache.connect db.cache.reload db.cache.reload.test
.PHONY: db.client.connect db.client.reload db.client.reload.test

xdebug.on:
	@docker-compose exec php sudo mv /usr/local/etc/php/conf.d/xdebug.ini.dis /usr/local/etc/php/conf.d/xdebug.ini

xdebug.off:
	@docker-compose exec php sudo mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.dis

db.core.reload db.cache.reload db.client.reload db.admin.reload: ENV=dev
db.core.reload.test db.cache.reload.test db.client.reload.test db.admin.reload.test: ENV=test

db.core.reload db.core.reload.test: DB=core
db.core.connect db.core.dump db.core.dump.data db.core.fill: DB=core

db.cache.reload db.cache.reload.test: DB=cache
db.cache.connect db.cache.dump db.cache.dump.data db.cache.fill: DB=cache

db.client.reload db.client.reload.test: DB=client
db.client.connect db.client.dump db.client.dump.data db.client.fill: DB=client

db.admin.reload db.admin.reload.test: DB=admin
db.admin.connect db.admin.dump db.admin.dump.data db.admin.fill: DB=admin

db.core.reload db.core.reload.test db.cache.reload db.cache.reload.test db.client.reload db.client.reload.test db.admin.reload db.admin.reload.test:
	@echo "Creating $(DB) $(ENV) database"
	@docker-compose exec postgres /bin/bash -c 'dropdb -U $$POSTGRES_USER --if-exists $(DB)-$(ENV) &>/dev/null'
	@docker-compose exec postgres /bin/bash -c 'createdb -U $$POSTGRES_USER $(DB)-$(ENV)'
	@docker-compose exec backoffice /bin/bash -c 'php -d xdebug.mode=off bin/console kishlin:persistence:migration:$(DB):prepare --env=$(ENV) &>/dev/null'
	@docker-compose exec backoffice /bin/bash -c 'php -d xdebug.mode=off bin/console kishlin:persistence:migration:$(DB):apply --env=$(ENV) --up &>/dev/null'
	@echo "Done reloading $(DB) $(ENV) database"

db.core.connect db.cache.connect db.client.connect db.admin.connect:
	@docker-compose exec postgres /bin/bash -c 'psql -U $$POSTGRES_USER -d $(DB)-dev'

db.core.dump db.cache.dump db.client.dump db.admin.dump:
	@echo "Dump DB schema to file"
	@docker-compose exec postgres /bin/bash -c 'pg_dump -U $$POSTGRES_USER -d $(DB)-dev --schema-only > /app/etc/Schema/create-$(DB).sql'

db.core.dump.data db.cache.dump.data db.client.dump.data db.admin.dump.data:
	@echo "Dump DB data to file"
	@docker-compose exec postgres /bin/bash -c 'pg_dump -U $$POSTGRES_USER --column-inserts --data-only -d $(DB)-dev > /app/etc/Data/data-$(DB).sql'

db.core.fill db.cache.fill db.client.fill db.admin.fill:
	@echo "Filling DB $(DB) with data from dump"
	@docker-compose exec postgres /bin/bash -c 'psql -q -U $$POSTGRES_USER -d $(DB)-dev -f /app/etc/Data/data-$(DB).sql &>/dev/null'

frontend.sh:
	@docker-compose exec node sh

frontend.build:
	@docker-compose exec node npm run build

##> Prod
.PHONY: deploy

deploy:
	@echo "Composer runs"
	composer install --no-dev --optimize-autoloader
	composer dump-autoload --no-dev --classmap-authoritative
	composer dump-env prod
	@echo "Symfony caches"
	php /app/MotorsportTracker/apps/Backoffice/bin/console ca:cl
	php /app/MotorsportTracker/apps/Backoffice/bin/console ca:warmup
	php /app/MotorsportTracker/apps/MotorsportTracker/Backend/bin/console ca:cl
	php /app/MotorsportTracker/apps/MotorsportTracker/Backend/bin/console ca:warmup
	@echo "Deployed! Remember to clear the opcache with php-fpm."

##> Tests
#.PHONY: tests.backend.use-cases tests.backend.api tests.backend.backoffice \

.PHONY: tests.backend.use-cases \
        tests.backend.src.isolated tests.backend.src.contract tests.backend.src \
		tests.backend.app.driving tests.backend.app.functional tests.backend.app.integration tests.backend.app \
		tests.backend tests.frontend tests

tests.backend.use-cases:
	@echo "Running Use Case Tests for src/"
	@docker-compose exec backend php \
		/app/vendor/bin/behat --config /app/behat-config.yml --suite use_case_tests
	@echo ""

#tests.backend.api:
#	@echo "Running Api Tests for the Backend"
#	@docker-compose exec backend php \
#		/app/vendor/bin/behat --config /app/behat-config.yml --suite api_tests
#	@echo ""

#tests.backend.backoffice:
#	@echo "Running Backoffice Tests for the Backend"
#	@docker-compose exec backoffice php \
#		/app/vendor/bin/behat --config /app/behat-config.yml --suite backoffice_tests
#	@echo ""

tests.backend.src.isolated:
	@echo "Running Isolated Tests for the src/ folder"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite isolated
	@echo ""

tests.backend.src.contract:
	@echo "Running Contract Tests for the src/ folder"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite contract
	@echo ""

tests.backend.src:
	@echo "Running Tests for the src/ folder"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite isolated,contract
	@echo ""

tests.backend.app.driving:
	@echo "Running Driving Tests for the Backend"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backend-driving
	@echo ""

tests.backend.app.functional:
	@echo "Running Functional Tests for the Backend"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backend-functional
	@echo ""

tests.backend.app.integration:
	@echo "Running Integration Tests for the Backend"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backend-integration
	@echo ""

tests.backend.app:
	@echo "Running Tests for the Backend"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backend-driving,backend-functional,backend-integration
	@echo ""

tests.backoffice.driving:
	@echo "Running Driving Tests for the Backoffice"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backoffice-driving
	@echo ""

tests.backoffice.functional:
	@echo "Running Functional Tests for the Backoffice"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backoffice-functional
	@echo ""

tests.backoffice.integration:
	@echo "Running Integration Tests for the Backoffice"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backoffice-integration
	@echo ""

tests.backoffice:
	@echo "Running Tests for the Backoffice"
	@docker-compose exec backend php \
		/app/vendor/bin/phpunit -c /app/phpunit.xml --testsuite backoffice-driving,backoffice-functional,backoffice-integration
	@echo ""

tests.frontend:
	@echo "Running Tests for the Frontend"
	@docker-compose exec frontend npm run test
	@echo ""


#tests.backend: tests.backend.use-cases tests.backend.api tests.backend.backoffice tests.backend.src tests.backend.app tests.backoffice
tests.backend: tests.backend.use-cases tests.backend.src tests.backend.app tests.backoffice

tests: tests.backend tests.frontend

##> Static Analysis

.PHONY: phpstan php-cs-fixer php-cs-fixer.force eslint complete-analysis.back complete-analysis.front complete-analysis

phpstan:
	@echo "Running PHPStan"
	@docker-compose exec backend php \
		/app/vendor/bin/phpstan analyse -c /app/phpstan.neon
	@echo ""


php-cs-fixer: DRY_RUN="--dry-run"
php-cs-fixer php-cs-fixer.force:
	@echo "Running PHP-Cs-Fixer ${DRY_RUN}"
	@docker-compose exec backend php \
		/app/vendor/bin/php-cs-fixer fix --config=/app/.php-cs-fixer.php -vv ${DRY_RUN}
	@echo ""

eslint:
	@docker-compose exec frontend npm run lint

complete-analysis.back: tests.backend phpstan php-cs-fixer
complete-analysis.front: eslint tests.frontend
complete-analysis: complete-analysis.back complete-analysis.front
