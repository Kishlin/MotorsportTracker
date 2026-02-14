# Development

## Environment Setup

1. Copy the Docker Compose template:
   ```bash
   make setup   # Creates .env.local and docker-compose.yaml from .dist
   ```

2. Start all services:
   ```bash
   make start   # Starts containers, vendors deps, runs all migrations
   ```

3. Environment files are loaded in this hierarchy:
   `.env` -> `.env.local` -> `.env.<env>` -> `.env.<env>.local`

   Override settings in `.env.local` (gitignored).

## Make Targets Reference

### Core Workflow

| Target | Description |
|--------|-------------|
| `make start` | Full startup: containers + migrations + vendor |
| `make containers` | Start Docker containers only |
| `make stop` | Stop all containers |
| `make go-build` | Build all Go applications |
| `make go-test` | Run all Go tests across all modules |
| `make go-lint` | Lint all Go code with golangci-lint |
| `make go-tidy` | Sync go.work and tidy all modules |
| `make go-vendor` | Download and vendor all Go dependencies |

### Individual Applications

| Target | Description |
|--------|-------------|
| `make build-processor` | Build CommandsProcessor |
| `make build-publisher` | Build CommandsPublisher |
| `make build-motorsport-tracker` | Build MotorsportTracker CLI |
| `make build-dbmigrate` | Build DBMigrate |
| `make run-processor` | Run CommandsProcessor (queue consumer) |
| `make run-publisher ARGS="series"` | Publish a scraping intent to the queue |
| `make run-motorsport-tracker ARGS="series"` | Run a scraping command directly |
| `make go-run ARGS="series"` | Run MotorsportTracker via `go run` |

### Database Operations

| Target | Description |
|--------|-------------|
| `make run-dbmigrate-core` | Run core DB migrations (dev) |
| `make run-dbmigrate-core.test` | Run core DB migrations (test) |
| `make run-dbmigrate-client-cache` | Run client-cache DB migrations (dev) |
| `make run-dbmigrate-client-cache.test` | Run client-cache DB migrations (test) |
| `make db.core.connect` | Open psql to core-dev database |
| `make db.core.dump` | Dump core-dev schema to `etc/Schema/` |
| `make db.core.dump.data` | Dump core-dev data to `etc/Data/` |
| `make db.core.fill` | Fill core-dev from data dump |

The same `db.*` targets exist for `cache`, `client`, and `admin` databases.

### Testing & Analysis

| Target | Description |
|--------|-------------|
| `make go-test` | Run all Go tests |
| `make go-test-pristine` | Clear test cache, then run all Go tests |
| `make go-cache-clear` | Clear Go test cache |
| `make tests.golang` | Run Go tests (alternative target) |
| `make tests.frontend` | Run frontend tests |
| `make tests` | Run all tests (Go + frontend) |
| `make eslint` | Lint frontend code |

## Testing Strategy

Tests use `testify/suite`. See `docs/CODE_STYLE.md` for naming conventions and lifecycle details.

```bash
# Run all Go tests
make go-test

# Run a specific test suite
docker compose exec golang bash -c 'cd /app && go test ./src/Golang/... -run TestIntegration_SuiteName'

# Run tests with verbose output
docker compose exec golang bash -c 'cd /app && go test -v ./src/Golang/...'

# Run tests for a specific module
docker compose exec golang bash -c 'cd /app && go test ./src/Golang/motorsporttracker/scrapping/series/...'
```

## Adding a New Scraping Operation

1. **Create the domain intent** — `src/Golang/motorsporttracker/scrapping/<module>/domain/intent.go`
   - Define intent name constant and `IntentConfig` with arguments/options

2. **Create the domain use case** — `src/Golang/motorsporttracker/scrapping/<module>/domain/use_case.go`
   - Define repository interfaces needed
   - Implement `Execute(ctx, ...)` method

3. **Create the infrastructure handler** — `src/Golang/motorsporttracker/scrapping/<module>/infrastructure/handler.go`
   - Extract params from `message.Metadata`
   - Delegate to the use case

4. **Create the save repository** — `src/Golang/motorsporttracker/scrapping/<module>/infrastructure/save_*_repository.go`
   - Use `shared.Save()` for bulk upserts with hash-based change detection

5. **Create SQL migration** — `etc/Migrations/core/YYYYMMDDHHMMSS_create_<table>.up.sql`
   - Include main table + history table + trigger (see template below)

6. **Register in applications**:
   - Add handler registration in `apps/Backend/CommandsProcessor/main.go`
   - Add intent in `apps/Backend/CommandsPublisher/main.go`
   - Add subcommand case in `apps/Backend/MotorsportTracker/main.go`

7. **Run migration**: `make run-dbmigrate-core && make run-dbmigrate-core.test`

## Adding a New Database Table

Migration template (`etc/Migrations/core/YYYYMMDDHHMMSS_create_<table>.up.sql`):

```sql
CREATE TABLE IF NOT EXISTS <table_name> (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    -- your columns here
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS <table_name>_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    uuid UUID NOT NULL,
    -- same columns as main table
    hash TEXT NOT NULL,
    valid_from TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to TIMESTAMP
);

CREATE OR REPLACE FUNCTION update_<table_name>_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE <table_name>_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO <table_name>_history (id, uuid, /* columns */, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, /* NEW.columns */, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_<table_name>_history
    AFTER INSERT OR UPDATE ON <table_name>
    FOR EACH ROW
    EXECUTE FUNCTION update_<table_name>_history();
```

## Debugging Tips

1. **Check logs**: All apps use structured logging with `slog`. Set `LOG_LEVEL=DEBUG` in `.env.local` for verbose output.
2. **Inspect database**: `make db.core.connect` opens psql to the core-dev database.
3. **Run tests with verbose**: `docker compose exec golang bash -c 'cd /app && go test -v ./...'`
4. **Check environment**: Ensure `.env.local` has correct values for your setup.
5. **SQS UI**: Access the ElasticMQ UI at `http://localhost:9325` to inspect queue messages.

## Gotchas

- **UUID vs ID**: Always use UUID for external references (from motorsportstats.com), SERIAL ID for internal foreign keys.
- **Timestamps**: Use `shared.PrepareTimestamp()` for Unix timestamp to `time.Time` conversion.
- **Batch Operations**: Use `shared.Save()` which automatically batches when parameter count exceeds 1000.
- **Queue Messages**: Message metadata is `map[string]string` — all values are strings. Parse numbers with `strconv.Atoi()`.
- **Error Wrapping**: Always preserve original error with `%w` for error chains.
- **Testing Databases**: Always use `test` environment for integration tests (core-test, client-cache-test).
- **Go Workspace**: When adding dependencies, run `make go-vendor` to keep the vendored dependencies in sync.
- **Docker**: All Go commands run inside the `golang` container. Use `docker compose exec golang ...` for ad-hoc commands.
