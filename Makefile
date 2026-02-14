include .env
-include .env.local

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

CACHE=node-cache node
VOL_CACHE?=$(shell docker volume inspect -f '{{ index .Mountpoint }}' cache)

# Env
.env.local:
	@touch .env.local

##> Project
docker-compose.yaml:
	@cp docker-compose.yaml.dist docker-compose.yaml
	@sed -i "s/<DOCKER_USER_ID>/$(shell $(shell echo id -u ${USER}))/g" $@
	@sed -i "s/<DOCKER_USER>/$(shell echo ${USER})/g" $@

.docker-cache:
	@touch .docker-cache
	@docker volume create --name=cache;

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

start: containers go-vendor run-dbmigrate-core run-dbmigrate-core.test run-dbmigrate-client-cache run-dbmigrate-client-cache.test
	@echo "All services should be running."
	@echo "You can access fronts services."
	@echo "    Frontend: http://localhost:3030/"
	@echo "    Admin: http://localhost:3040/"
	@echo "Ports may differ if overridden in the .env.local file."
	@echo "Run backend tests: \`make go-test\` (see Makefile for more options)."

##> Apps

.PHONY: build-publisher build-processor run-publisher run-processor
.PHONY: build-go test-go lint-go tidy-go

build-dbmigrate:
	@echo "Building Golang app DBMigrate"
	@docker compose exec golang bash -c 'cd /app/apps/Backend/DBMigrate && go build -o build/dbmigrate main.go'

run-dbmigrate-core run-dbmigrate-client-cache: ENV=dev
run-dbmigrate-core.test run-dbmigrate-client-cache.test: ENV=test

run-dbmigrate-core run-dbmigrate-core.test: DB=core
run-dbmigrate-client-cache run-dbmigrate-client-cache.test: DB=client-cache

run-dbmigrate-core run-dbmigrate-core.test run-dbmigrate-client-cache run-dbmigrate-client-cache.test:
	@echo "Running Golang app DBMigrate for $(DB) $(ENV)"
	@docker-compose exec postgres /bin/bash -c '(createdb -U $$POSTGRES_USER $(DB)-$(ENV) &>/dev/null && echo "Created database $(DB)-$(ENV)") || echo "Database $(DB)-$(ENV) already exists"'
	@docker-compose exec \
		-e DB_MIGRATE_SOURCE="file:///app/etc/Migrations/$(DB)" \
		-e DB_MIGRATE_USER=$(POSTGRES_USER) \
		-e DB_MIGRATE_PASSWORD=$(POSTGRES_PASSWORD) \
		-e DB_MIGRATE_HOST=postgres \
		-e DB_MIGRATE_PORT=$(POSTGRES_PORT) \
		-e DB_MIGRATE_NO_SSL=true \
		-e DB_MIGRATE_DATABASE="$(DB)-$(ENV)" \
		 golang /app/apps/Backend/DBMigrate/build/dbmigrate

build-publisher:
	@echo "Building Golang app CommandsPublisher"
	@docker compose exec golang bash -c 'cd /app/apps/Backend/CommandsPublisher && go build -o build/scrape-commands-publisher main.go'

run-publisher:
	@echo "Running Golang app CommandsPublisher with ARGS=$(ARGS)"
	@docker-compose exec golang /app/apps/Backend/CommandsPublisher/build/scrape-commands-publisher $(ARGS)

build-processor:
	@echo "Building Golang app CommandsProcessor"
	@docker compose exec golang bash -c 'cd /app/apps/Backend/CommandsProcessor && go build -o build/processor main.go'

run-processor:
	@echo "Running Golang app CommandsProcessor"
	@docker-compose exec golang /app/apps/Backend/CommandsProcessor/build/processor

build-motorsport-tracker:
	@echo "Building Golang MotorsportTracker"
	@docker compose exec golang bash -c 'cd /app/apps/Backend/MotorsportTracker && go build -o build/motorsport-tracker main.go'

run-motorsport-tracker:
	@echo "Running Golang MotorsportTracker with ARGS=$(ARGS)"
	@docker-compose exec golang /app/apps/Backend/MotorsportTracker/build/motorsport-tracker $(ARGS)

# Go workspace commands
go-tidy:
	@echo "Running go mod tidy across all Go modules"
	@docker compose exec golang bash -c 'cd /app && go work sync'
	@docker compose exec golang bash -c 'cd /app/src/Golang && go mod tidy'
	@docker compose exec golang bash -c 'cd /app/apps/Backend/CommandsProcessor && go mod tidy'
	@docker compose exec golang bash -c 'cd /app/apps/Backend/CommandsPublisher && go mod tidy'
	@docker compose exec golang bash -c 'cd /app/apps/Backend/DBMigrate && go mod tidy'

go-vendor: go-tidy
	@echo "Downloading Go workspace dependencies to vendor/"
	@docker compose exec golang bash -c 'cd /app && go work vendor'

go-setup: go-vendor
	@echo "Go workspace setup complete! All modules synchronized and dependencies vendored."

go-build: go-tidy build-dbmigrate build-processor build-publisher build-motorsport-tracker
	@echo "Built all Go applications"

go-test:
	@echo "Running Go tests across all modules"
	@docker compose exec golang bash -c 'cd /app && go test ./src/Golang/...'
	@docker compose exec golang bash -c 'cd /app && go test ./apps/Backend/DBMigrate/...'
	@docker compose exec golang bash -c 'cd /app && go test ./apps/Backend/CommandsProcessor/...'
	@docker compose exec golang bash -c 'cd /app && go test ./apps/Backend/CommandsPublisher/...'

go-cache-clear:
	@docker compose exec golang go clean -testcache

go-test-pristine: go-cache-clear go-test

go-lint:
	@echo "Running Go linting across all modules"
	@docker compose exec golang bash -c 'cd /app && golangci-lint run ./src/Golang/... ./apps/Backend/CommandsProcessor/... ./apps/Backend/CommandsPublisher/... ./apps/Backend/DBMigrate/...'

go-run:
	@docker-compose exec golang go run /app/apps/Backend/MotorsportTracker/main.go $(ARGS)

##> Helpers
.PHONY: frontend.sh frontend.build
.PHONY: db.core.connect db.core.dump db.core.dump.data db.core.fill
.PHONY: db.admin.connect db.admin.dump db.admin.dump.data db.admin.fill
.PHONY: db.cache.connect db.cache.dump db.cache.dump.data db.cache.fill
.PHONY: db.client.connect db.client.dump db.client.dump.data db.client.fill

db.core.connect db.core.dump db.core.dump.data db.core.fill: DB=core

db.cache.connect db.cache.dump db.cache.dump.data db.cache.fill: DB=cache

db.client.connect db.client.dump db.client.dump.data db.client.fill: DB=client

db.admin.connect db.admin.dump db.admin.dump.data db.admin.fill: DB=admin

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

.PHONY: tests.golang tests.frontend tests

tests.golang:
	@echo "Running Golang Tests"
	@docker compose exec golang bash -c 'cd /app/src/Golang && go test ./...'

tests.frontend:
	@echo "Running Tests for the Frontend"
	@docker-compose exec frontend npm run test
	@echo ""

tests: tests.golang tests.frontend

##> Static Analysis

.PHONY: eslint complete-analysis.back complete-analysis.front complete-analysis

eslint:
	@docker-compose exec frontend npm run lint

complete-analysis.front: eslint tests.frontend
complete-analysis: complete-analysis.back complete-analysis.front
