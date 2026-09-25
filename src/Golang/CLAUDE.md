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

Integration suites share the `core-test` and client-cache test databases and run concurrently: every integration and functional suite calls `t.Parallel()`, and separate packages run as parallel processes under `go test ./...`. Two rules keep them apart:

- **One UUID prefix per suite, unique across suites.** Every UUID the suite writes starts with the same first three groups (`xxxxxxxx-xxxx-xxxx`), and teardown deletes `LIKE '<prefix>-%'`. Two suites sharing a prefix delete each other's fixtures mid-run. When a suite seeds several entities, give each its own group after the prefix (`-0001-`, `-0002-`, …). `scripts/fixture-prefix-check.sh` enforces this and runs before `make go-test` and `scripts/test-runner.sh`; generate a new prefix for a new suite, never copy one. The same goes for every other `UNIQUE` column: each core table has a `UNIQUE` `hash`, and `ON CONFLICT (uuid)` does not absorb a hash clash, so two suites seeding `'venues-hash'` fail with SQLSTATE 23505 as soon as they overlap. In fixtures, use the row's own UUID as its hash.
- **Searched strings carry the prefix.** Any string a repository **searches** on (name, short name, short code, and the keywords the test passes in) ends with the suite's prefix, held in a `const`. The search queries use `LIKE '%kw%'`, so a generic name like `'series'` or a name shared between suites matches another suite's rows mid-run. See `scrapping/classification/infrastructure/search_session_identifier_repository_test.go`.

Integration suites need the test databases migrated:

```bash
make run-dbmigrate-core.test && make run-dbmigrate-client-cache.test
```

Run via `./scripts/test-runner.sh [scope] [--verbose] [--run <pattern>] [--pristine]` (the `test-runner` skill lists the scopes) — tests execute inside the `golang` container, never on the host.

## Workspace

Six modules under `go.work`. After changing dependencies run `make go-vendor` to keep `vendor/` in sync.

`apps/Backend/MotorsportTracker` is absent from `go-tidy`, `go-test`, `go-lint` and `scripts/test-runner.sh`, which is why its `go.mod` carries no `require` block and its `go.sum` is empty — it compiles only because `go.work` resolves `src/Golang` and the root `vendor/` supplies the rest. Do not copy it when adding an app; `CommandsProcessor` and `ApiCanary` have the complete wiring.

## Schema validation sits below the cache

`ConnectorUsingClient.validate()` is the only place the embedded JSON Schemas are enforced, and the caching decorators wrap it. A hit in `DatabaseCache` or `FileSystemCache` therefore returns bytes that were never validated, so the application path cannot detect upstream drift while the caches are warm. Do not "fix" this by validating in the decorator — re-checking bytes that already passed once buys nothing.

Drift detection is `apps/Backend/ApiCanary` instead, which builds `NewConnectorUsingClient` directly with no decorators. If you change a schema or an endpoint constant, run `make run-api-canary` to confirm the live API still agrees.

Note the schemas do not set `additionalProperties: false`, so validation is blind to fields motorsportstats adds; the canary reads the same `schemas/*.json` off disk to diff payload keys and reports those separately as warnings.
