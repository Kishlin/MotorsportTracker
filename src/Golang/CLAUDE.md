# Go Core Library

Rules for `src/Golang/`. Loaded on top of the root `CLAUDE.md`.

## Layer boundary (enforced)

`domain/` holds business logic and the interfaces it depends on. `infrastructure/` holds implementations. Domain must not import:

- any `*/infrastructure` package
- `shared/database`, `shared/messaging`, `shared/client`, `shared/cache/infrastructure`
- `pgx`, `aws-sdk`, `golang-migrate`

A `PostToolUse` hook runs `scripts/architecture-check.sh` on every edit under a `domain/` directory and **blocks** on violation. When it fires, define an interface in `domain/` and implement it in `infrastructure/` — do not weaken the check. `_test.go` files are exempt.

## What goes where

| Concern | Layer |
|---|---|
| Use case, repository/gateway **interfaces**, domain structs | `domain` |
| Intent, Handler, Repository impl, Gateway impl, Connector | `infrastructure` |

**Intents are infrastructure.** They build a `messaging.Message` and import `shared/application/infrastructure`.

## File naming

Single-operation modules use bare names: `domain/use_case.go`, `infrastructure/{handler,intent}.go`, `infrastructure/save_<entity>_repository.go`.

Multi-operation modules prefix every file with the operation — see `scrapping/seasons/`, which carries three intents, three handlers and three use cases side by side. Pick one style per module and stay with it.

## Registration is manual and silent

A new operation needs **two** edits in `registration/registration.go`: an entry in the `registeredIntents` map and a `register<Module>Handlers()` call inside `RegisterAllHandlers`. Both compile fine when missing and fail only at runtime. `/new-scraping-op` walks the whole sequence.

## Intent names are the CLI subcommand

The registry is keyed by the full name — `scrape:series`, `scrape:seasons`, `scrape:seasons-one`, `scrape:seasons-all`, `scrape:calendar`, `scrape:classification`.

```bash
make go-run APP=MotorsportTracker ARGS="scrape:series"   # works
make go-run APP=MotorsportTracker ARGS="series"          # unknown subcommand
```

## Persistence

Repositories write through `shared.Save()`, which emits `INSERT ... ON CONFLICT DO UPDATE ... WHERE hash IS DISTINCT FROM EXCLUDED.hash` and batches automatically past 1000 parameters. Build the hash from every significant field — hashing the UUID alone silently prevents all updates. Returns `UpsertStats{Inserted, Updated}`; log both.

## Tests

Conventions for writing tests live in `.claude/rules/go-tests.md`, which loads when a `_test.go` file is read. Before writing a new suite, read an existing one in the same package or a neighbouring one.

Integration suites need the test databases migrated:

```bash
make run-dbmigrate-core.test && make run-dbmigrate-client-cache.test
```

Run via `./scripts/test-runner.sh [scope] [--verbose] [--run <pattern>] [--pristine]` (the `test-runner` skill lists the scopes) — tests execute inside the `golang` container, never on the host.

## Workspace

Seven modules under `go.work`. After changing dependencies run `make go-vendor` to keep `vendor/` in sync.

## Schema validation sits below the cache

`ConnectorUsingClient.validate()` is the only place the embedded JSON Schemas are enforced, and the caching decorators wrap it. A hit in `DatabaseCache` or `FileSystemCache` therefore returns bytes that were never validated, so the application path cannot detect upstream drift while the caches are warm. Do not "fix" this by validating in the decorator — re-checking bytes that already passed once buys nothing.

Drift detection is `apps/Backend/ApiCanary` instead, which builds `NewConnectorUsingClient` directly with no decorators. If you change a schema or an endpoint constant, run `make go-run APP=ApiCanary` to confirm the live API still agrees.

Note the schemas do not set `additionalProperties: false`, so validation is blind to fields motorsportstats adds; the canary reads the same `schemas/*.json` off disk to diff payload keys and reports those separately as warnings.
