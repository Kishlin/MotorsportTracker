# Go Architecture Review — February 2026

> Status as of 2026-08-01: the Weak Points summary has been updated — items 1–8 and 10 are resolved, items 11–14 are open. See [ISSUES.md](ISSUES.md) for what each fix involved.
>
> Strong Point 5 has been **corrected**: the 2026-07-30 pass recorded the caching stack as an unqualified strength, but a cache hit returns bytes without ever validating them. The detail is below.

## Overview

MotorsportTracker is a motorsport data aggregation platform scraping motorsportstats.com. The Go codebase implements hexagonal architecture across 6 modules coordinated by a Go workspace (`go.work`).

**Pipeline**: CLI/SQS Intent → Handler → UseCase → Gateway (HTTP) + Repository (PostgreSQL)

## Strong Points

### 1. Consistent Hexagonal Architecture
Every scraping module (series, seasons, calendar, classification) follows the same `domain/` + `infrastructure/` split. Domain packages have zero external dependencies. Infrastructure implements domain interfaces.

### 2. Uniform Patterns Across Modules
All four modules use identical patterns: UseCase orchestrator, Handler adapter, Intent CLI definition, Repository persistence. Naming, constructor injection, error wrapping, and test structure are uniform.

| Pattern | Series | Seasons | Calendar | Classification |
|---------|--------|---------|----------|----------------|
| UseCase | ScrapeSeriesUseCase | ScrapeSeasonsFor*UseCase | ScrapeCalendarUseCase | ScrapeClassificationUseCase |
| Handler | ScrapeSeriesHandler | ScrapeSeasonsFor*Handler | ScrapeCalendarHandler | ScrapeClassificationHandler |
| Intent | ScrapSeriesIntent | ScrapeSeasonsFor*Intent | ScrapCalendarIntent | ScrapeClassificationIntent |
| Repository | SaveSeriesRepository | SaveSeasonsRepository | SaveCalendarRepository | SaveClassificationRepository |
| Error handling | fmt.Errorf wrapping | fmt.Errorf wrapping | fmt.Errorf wrapping | fmt.Errorf wrapping |
| Tests | testify/suite | testify/suite | testify/suite | testify/suite |

### 3. Hash-Based Change Detection (`shared.Save()`)
`src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go`

Uses `INSERT ... ON CONFLICT DO UPDATE WHERE hash IS DISTINCT FROM EXCLUDED.hash` with:
- SHA-256 hash of concatenated field values for change detection
- Automatic batching when parameters exceed 1000
- Insert/update statistics via `RETURNING id, xmax <> 0 AS updated`

### 4. Temporal Audit Trails
Every core table has a `_history` counterpart with trigger-based tracking (`valid_from`/`valid_to`). Forward-only migrations in `etc/Migrations/core/`.

### 5. Connector Decorator Composition
`src/Golang/motorsportstats/connector/infrastructure/`

Three-layer caching stack: `HTTP → DatabaseCache → FileSystemCache (optional)`. Transparent decorator pattern via `CachedConnector`. Cache interface (`Get`/`Set` with namespace+key) has three pluggable backends (in-memory, database, filesystem).

**Caveat, corrected 2026-08-01 — schema validation sits *below* the cache.** `validate()` runs only in `ConnectorUsingClient`, so a hit at either cache layer hands bytes to the gateway having checked nothing. `ServicesRegistry.GetMotorsportStatsGateway` always wraps the connector in a `DatabaseCache`, and adds a `FileSystemCache` when `USE_FS_CACHE=true` (which `.env.dev` sets), so **the application path cannot detect upstream schema drift while the caches are warm**. That is the correct trade-off for scraping — re-validating cached bytes buys nothing — but it means the schemas cannot double as a drift alarm. `apps/Backend/ApiCanary` exists to cover that gap by building an uncached connector directly.

### 6. Unified Message Contract
Same `Message{Type, Metadata}` struct flows through CLI (MotorsportTracker), queue publisher (CommandsPublisher), and queue consumer (CommandsProcessor). Handlers are transport-agnostic.

### 7. Fail-Loud Initialization
Missing env vars panic immediately in `ServicesRegistry`. No silent defaults for critical configuration. Bulk operations log per-item errors but continue processing.

## Weak Points & Issues

See [ISSUES.md](ISSUES.md) for the full list with file paths and remediation steps.

Summary:
1. ~~**Handler/intent registration duplicated** across 3 main.go files~~ (resolved — centralized in `registration/`)
2. ~~**Weak metadata typing** — `map[string]string` with manual parsing in every handler~~ (resolved — `RequireString`/`RequireInt`)
3. ~~**580-line connector file** — inline JSON schemas should be externalized~~ (resolved — `go:embed`, now 105 lines)
4. ~~**`fmt.Println` in env.go** — should use `slog`~~ (resolved)
5. ~~**Package typo** — `crypto/doman` instead of `crypto/domain`~~ (resolved — both files in the package)
6. ~~**Casing inconsistency** — `clientCacheDBonce` vs convention `...Once`~~ (resolved)
7. ~~**No backoff in queue worker** — tight retry loop under degraded conditions~~ (resolved — capped exponential, interruptible waits)
8. ~~**No integration tests for `shared.Save()`** — core persistence logic untested against real SQL~~ (resolved — 8 cases against `core-test`)
9. **Database pool uses defaults** — no MaxConns/MaxConnLifetime tuning (open, deliberately; the stated premise was stale — see ISSUES.md)
10. ~~**SQL interpolation in cache** — `fmt.Sprintf` with namespace as table name (safe today, fragile pattern)~~ (resolved — validated on both call sites)
11. **Parallel integration suites share one test database** — intermittent false failures (open)
12. **Inverted `exists` check** — driver nationalities from classifications are never written to `countries` (open, silent)
13. **Driver linked to only one car per session** — missing `entry_drivers` rows in endurance series (open, silent)
14. **Event hash built from venue fields** — event renames never persist; plus a latent nil-deref the schema currently makes unreachable (open, silent)

Items 12–14 were found on 2026-08-01 while tracing the scraping chain for the API canary. All three are in the persistence layer, all three are silent, and none is caught by a test today.

Fixed in passing, not tracked as numbered issues:

- ~~`ConnectorUsingClient.doGet` wrapped every error as `"getting series"` / `"validating series data"` regardless of which of the four endpoints was called, so a calendar or classification failure reported itself as a series failure~~ (resolved 2026-08-01 — both wrappers now interpolate the `url` the function already computes, naming the exact path and UUID that failed; no signature change).

Naming nits found while re-verifying, not tracked as numbered issues:

- ~~`connector_decoractor_with_cache.go` — source filename misspelled ("decoractor") while its test file spelled it correctly~~ (resolved — renamed to `connector_decorator_with_cache.go`; Go never references filenames, so nothing else changed).
- Intent types are inconsistently named: `ScrapSeriesIntent` and `ScrapCalendarIntent` drop the "e" that `ScrapeSeasonsFor*Intent` and `ScrapeClassificationIntent` keep. Constructors mismatch their own types too — `NewScrapClassificationIntent` returns `*ScrapeClassificationIntent`. The pattern table above records this faithfully.

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────┐
│ Applications (apps/Backend/)                            │
│  MotorsportTracker  CommandsPublisher  CommandsProcessor │
│       (CLI)            (SQS send)       (SQS consume)  │
└──────────┬──────────────────┬──────────────┬────────────┘
           │                  │              │
           ▼                  ▼              ▼
                    (ApiCanary bypasses all of this — it
                     builds an uncached Connector directly
                     and never touches a database)
    ┌─────────────┐    ┌───────────┐  ┌───────────┐
    │   Intent    │    │   Intent  │  │  Worker   │
    │ →ToMessage()│    │→ToMessage()│ │ →poll SQS │
    └──────┬──────┘    └─────┬─────┘  └─────┬─────┘
           │                 │              │
           ▼                 ▼              ▼
    ┌─────────────────────────────────────────────┐
    │            Handler (per module)              │
    │  Extract metadata → call UseCase.Execute()  │
    └──────────────────────┬──────────────────────┘
                           │
                           ▼
    ┌─────────────────────────────────────────────┐
    │              UseCase (domain)                │
    │  Fetch via Gateway → Save via Repository    │
    └──────────┬───────────────────┬──────────────┘
               │                   │
               ▼                   ▼
    ┌────────────────┐   ┌──────────────────────┐
    │    Gateway      │   │    Repository        │
    │ (motorsportstats)│  │ (shared.Save upsert) │
    │                 │   │                      │
    │ Connector(HTTP) │   │ Hash change detect   │
    │  → CachedConn   │   │ Auto-batch >1000     │
    │  → Gateway(JSON)│   │ Temporal history     │
    └─────────────────┘   └──────────────────────┘
```

## Key Files Reference

| Component | Path |
|-----------|------|
| ServicesRegistry (DI) | `src/Golang/motorsporttracker/dependencyinjection/infrastructure/services_registry.go` |
| Save helpers (upsert) | `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go` |
| Connector (HTTP+schema) | `src/Golang/motorsportstats/connector/infrastructure/connector_using_client.go` |
| Cached connector | `src/Golang/motorsportstats/connector/infrastructure/connector_decorator_with_cache.go` |
| Gateway (JSON parsing) | `src/Golang/motorsportstats/gateway/infrastructure/gateway_using_connector.go` |
| Domain objects | `src/Golang/motorsportstats/gateway/domain/gateway.go` |
| Queue worker | `src/Golang/shared/messaging/infrastructure/worker.go` |
| Handler registry | `src/Golang/shared/messaging/infrastructure/handlers_list.go` |
| Intent base | `src/Golang/shared/application/infrastructure/intent.go` |
| Cache interface | `src/Golang/shared/cache/domain/cache.go` |
| CLI entry point | `apps/Backend/MotorsportTracker/main.go` |
| Queue consumer | `apps/Backend/CommandsProcessor/main.go` |
| Queue publisher | `apps/Backend/CommandsPublisher/main.go` |
| API drift canary | `apps/Backend/ApiCanary/main.go` |
| Core migrations | `etc/Migrations/core/` |
| Client cache migrations | `etc/Migrations/client-cache/` |
