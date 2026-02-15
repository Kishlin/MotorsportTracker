# Go Architecture Review — February 2026

## Overview

MotorsportTracker is a motorsport data aggregation platform scraping motorsportstats.com. The Go codebase implements hexagonal architecture across 5 modules coordinated by a Go workspace (`go.work`).

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

### 6. Unified Message Contract
Same `Message{Type, Metadata}` struct flows through CLI (MotorsportTracker), queue publisher (CommandsPublisher), and queue consumer (CommandsProcessor). Handlers are transport-agnostic.

### 7. Fail-Loud Initialization
Missing env vars panic immediately in `ServicesRegistry`. No silent defaults for critical configuration. Bulk operations log per-item errors but continue processing.

## Weak Points & Issues

See [ISSUES.md](ISSUES.md) for the full list with file paths and remediation steps.

Summary:
1. **Handler/intent registration duplicated** across 3 main.go files
2. **Weak metadata typing** — `map[string]string` with manual parsing in every handler
3. **580-line connector file** — inline JSON schemas should be externalized
4. **`fmt.Println` in env.go** — should use `slog`
5. **Package typo** — `crypto/doman` instead of `crypto/domain`
6. **Casing inconsistency** — `clientCacheDBonce` vs convention `...Once`
7. **No backoff in queue worker** — tight retry loop under degraded conditions
8. **No integration tests for `shared.Save()`** — core persistence logic untested against real SQL
9. **Database pool uses defaults** — no MaxConns/MaxConnLifetime tuning
10. **SQL interpolation in cache** — `fmt.Sprintf` with namespace as table name (safe today, fragile pattern)

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────┐
│ Applications (apps/Backend/)                            │
│  MotorsportTracker  CommandsPublisher  CommandsProcessor │
│       (CLI)            (SQS send)       (SQS consume)  │
└──────────┬──────────────────┬──────────────┬────────────┘
           │                  │              │
           ▼                  ▼              ▼
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
| Cached connector | `src/Golang/motorsportstats/connector/infrastructure/connector_decoractor_with_cache.go` |
| Gateway (JSON parsing) | `src/Golang/motorsportstats/gateway/infrastructure/gateway_using_connector.go` |
| Domain objects | `src/Golang/motorsportstats/gateway/domain/gateway.go` |
| Queue worker | `src/Golang/shared/messaging/infrastructure/worker.go` |
| Handler registry | `src/Golang/shared/messaging/infrastructure/handlers_list.go` |
| Intent base | `src/Golang/shared/application/infrastructure/intent.go` |
| Cache interface | `src/Golang/shared/cache/domain/cache.go` |
| CLI entry point | `apps/Backend/MotorsportTracker/main.go` |
| Queue consumer | `apps/Backend/CommandsProcessor/main.go` |
| Queue publisher | `apps/Backend/CommandsPublisher/main.go` |
| Core migrations | `etc/Migrations/core/` |
| Client cache migrations | `etc/Migrations/client-cache/` |
