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

**Intents are infrastructure.** They build a `messaging.Message` and import `shared/application/infrastructure`. Commit `70c5ef1f` moved them out of domain; `docs/DEVELOPMENT.md` still says otherwise and is wrong.

## File naming

Single-operation modules use bare names: `domain/use_case.go`, `infrastructure/{handler,intent}.go`, `infrastructure/save_<entity>_repository.go`.

Multi-operation modules prefix every file with the operation — see `scrapping/seasons/`, which carries three intents, three handlers and three use cases side by side. Pick one style per module and stay with it.

Implementations are named for their mechanism: `GatewayUsingConnector`, `SaveSeriesRepository`, `DatabaseCache`, `SeasonsScrapperUsingIntents`.

## Style

Explicit boolean comparison — `if exists == false`, not `if !exists`. Early return on error, always wrapping with `%w`. `fn.Deref(ptr, "")` for pointer defaults. `slog` with key/value pairs, never `fmt.Sprintf` into the message.

Existing code is not uniformly compliant: `shared/application/infrastructure/intent.go:86` uses `!configOption.RequiresValue`. Match the documented style in new code rather than the nearest counter-example.

## Registration is manual and silent

A new operation needs **two** edits in `registration/registration.go`: an entry in the `registeredIntents` map and a `register<Module>Handlers()` call inside `RegisterAllHandlers`. Both compile fine when missing and fail only at runtime. `/new-scraping-op` walks the whole sequence.

## Intent names are the CLI subcommand

The registry is keyed by the full name — `scrape:series`, `scrape:seasons`, `scrape:seasons-one`, `scrape:seasons-all`, `scrape:calendar`, `scrape:classification`.

```bash
make go-run ARGS="scrape:series"   # works
make go-run ARGS="series"          # unknown subcommand
```

The bare forms printed by the CLI's own help text in `apps/Backend/MotorsportTracker/main.go` do not exist.

## Persistence

Repositories write through `shared.Save()`, which emits `INSERT ... ON CONFLICT DO UPDATE ... WHERE hash IS DISTINCT FROM EXCLUDED.hash` and batches automatically past 1000 parameters. Build the hash from every significant field — hashing the UUID alone silently prevents all updates. Returns `UpsertStats{Inserted, Updated}`; log both.

## Tests

Colocated. `testify/suite`. Suffix `UnitTestSuite`, `IntegrationTestSuite`, or `FunctionalTestSuite`. Reset state in `SetupSubTest()`, not `TearDownTest()`. Prefer `s.Run()` over `s.T().Run()`. Assert counts and effects, not just a nil error.

Integration suites need the test databases migrated:

```bash
make run-dbmigrate-core.test && make run-dbmigrate-client-cache.test
```

Run via `./scripts/test-runner.sh <scope>` (the `test-runner` skill documents the positional arguments) — tests execute inside the `golang` container, never on the host.

## Workspace

Five modules under `go.work`. After changing dependencies run `make go-vendor` to keep `vendor/` in sync.

The `CommandsPublisher` directory declares module path `.../apps/Backend/CommandsPublisher**s**` — a typo in its `go.mod`. Harmless today because nothing imports it, but do not copy the pattern.
