# Go Codebase Issues — February 2026

Actionable issues identified during architecture review. Each includes the affected files, the problem, and a remediation approach. Ordered by impact.

---

## 1. Handler/Intent Registration Duplicated Across Apps

**Impact**: Maintenance burden — adding a new scraping operation requires identical changes in 3 files.

**Files**:
- `apps/Backend/MotorsportTracker/main.go` — switch on subcommand, wires handlers
- `apps/Backend/CommandsProcessor/main.go` — registers all handlers in HandlersList
- `apps/Backend/CommandsPublisher/main.go` — switch on subcommand, wires intents

**Problem**: Each app independently instantiates handlers/intents with the same DI wiring. No shared registration function exists.

**Remediation**: Create a shared registration module (e.g., `motorsporttracker/registration/`) that exposes:
- `RegisterHandlers(handlersList, registry)` — registers all handlers with their use cases
- `RegisterIntents()` — returns all available intents

Each app calls these shared functions instead of duplicating the wiring. The `ServicesRegistry` is passed as a parameter.

---

## 2. Weak Metadata Typing in Handlers

**Impact**: Runtime errors from typos in metadata keys. No compile-time safety.

**Files**:
- `src/Golang/motorsporttracker/scrapping/calendar/infrastructure/handler.go` — manual `message.Metadata["series"]` extraction
- `src/Golang/motorsporttracker/scrapping/classification/infrastructure/handler.go` — same pattern
- `src/Golang/motorsporttracker/scrapping/seasons/infrastructure/handler.go` — same pattern

**Problem**: Every handler manually checks key presence, converts types (`strconv.Atoi`), and validates. A typo in a metadata key (e.g., `"serie"` vs `"series"`) silently fails at runtime.

**Remediation**: Consider defining typed message structs per intent, or a small metadata parser utility that extracts and validates required fields with type conversion in one call. Example:

```go
// In shared or per-module
func RequireString(msg Message, key string) (string, error)
func RequireInt(msg Message, key string) (int, error)
```

This centralizes validation without over-abstracting.

---

## 3. Inline JSON Schemas in Connector (580 lines)

**Impact**: Maintainability — schema changes require editing deep inside a Go file.

**File**: `src/Golang/motorsportstats/connector/infrastructure/connector_using_client.go` (lines ~89-579 are schema strings)

**Problem**: JSON schemas for API response validation are embedded as Go string literals. The file is 580 lines, ~500 of which are schema definitions.

**Remediation**: Move schemas to `.json` files under `src/Golang/motorsportstats/connector/infrastructure/schemas/` and load them via `go:embed`:

```go
//go:embed schemas/series.json
var seriesSchema string
```

The connector file drops to ~80 lines. Schemas become independently reviewable and diffable.

---

## 4. `fmt.Println` in Environment Loading

**Impact**: Unconditional stdout output in production.

**File**: `src/Golang/shared/env/infrastructure/env.go` (lines 33, 41, 83)

**Problem**: Uses `fmt.Println` for debug output during `.env` file loading. This prints to stdout in all environments.

**Remediation**: Replace with `slog.Debug(...)`. Environment loading messages are useful for debugging but should not appear in production output.

---

## 5. Package Name Typo: `doman` Instead of `domain`

**Impact**: Misleading package declaration. No runtime effect.

**File**: `src/Golang/shared/crypto/domain/crypto.go` (line 1)

**Problem**: Package is declared as `package doman` but the directory is named `domain`. All imports use the directory path so it compiles, but the package name is wrong.

**Remediation**: Change `package doman` to `package domain`. Update all files in the package and any direct package-name references (likely none since Go uses directory paths for imports).

**Note**: Check if other files in the directory also use `doman` — all files in a package must share the same package name.

---

## 6. Casing Inconsistency: `clientCacheDBonce`

**Impact**: Naming convention violation. No runtime effect.

**File**: `src/Golang/motorsporttracker/dependencyinjection/infrastructure/services_registry.go` (line 28)

**Problem**: Field `clientCacheDBonce` uses lowercase 'o' while the convention elsewhere is `...Once` (e.g., `coreDBOnce`, `motorsportStatsGatewayOnce`).

**Remediation**: Rename to `clientCacheDBOnce`. This is a private field so no external impact.

---

## 7. No Backoff in Queue Worker

**Impact**: Under degraded conditions (DB down, API unreachable), the worker hammers SQS in a tight loop and floods logs.

**File**: `src/Golang/shared/messaging/infrastructure/worker.go` (around line 65)

**Problem**: On error, the worker immediately retries on the next poll interval with no backoff. Consecutive errors produce rapid-fire log entries and unnecessary SQS API calls.

**Remediation**: Add simple linear or exponential backoff:

```go
// In the poll loop:
consecutiveErrors := 0
// On error:
consecutiveErrors++
backoff := time.Duration(consecutiveErrors) * pollInterval
if backoff > 60*time.Second {
    backoff = 60 * time.Second
}
time.Sleep(backoff)
// On success:
consecutiveErrors = 0
```

---

## 8. No Integration Tests for `shared.Save()`

**Impact**: The core persistence logic (upsert, batching, hash change detection) is untested against a real database.

**File**: `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go`

**Problem**: The SQL template is built via `fmt.Sprintf` with table and column names. Batching splits rows dynamically. None of this is tested against actual PostgreSQL. Current tests mock the repository entirely.

**Remediation**: Add integration tests that:
1. Create a test table with known schema
2. Call `Save()` with test data
3. Verify inserted rows, updated rows, and unchanged rows (hash match)
4. Test batching with >1000 parameters
5. Run against the test database (`core-test`, already provisioned via `make run-dbmigrate-core.test`)

---

## 9. Database Pool Uses Defaults

**Impact**: Suboptimal connection management at scale. No immediate issue at current volume.

**File**: `src/Golang/shared/database/infrastructure/database_using_pgxpool.go`

**Problem**: `pgxpool.New(ctx, connStr)` is called with no configuration for `MaxConns`, `MaxConnLifetime`, `HealthCheckPeriod`, etc.

**Remediation**: If scaling becomes relevant, parse the connection string into a `pgxpool.Config` and set:
- `MaxConns` (match expected concurrency)
- `MaxConnLifetime` (prevent stale connections)
- `HealthCheckPeriod` (detect dead connections)

Low priority — current single-digit concurrency works fine with defaults.

---

## 10. SQL Interpolation in Database Cache

**Impact**: Safe today (hardcoded namespaces), but fragile pattern if reused with dynamic input.

**File**: `src/Golang/shared/cache/infrastructure/cache_using_database.go` (line 26)

**Problem**: `fmt.Sprintf(getQuery, namespace)` interpolates `namespace` directly into SQL as a table name. Current callers pass hardcoded strings ("series", "seasons", "calendar", "classification"), so there's no injection risk. But the function signature accepts any string.

**Remediation**: Add a validation check that namespace matches `^[a-z_]+$` before interpolation:

```go
if !regexp.MustCompile(`^[a-z_]+$`).MatchString(namespace) {
    return fmt.Errorf("invalid cache namespace: %q", namespace)
}
```

Or use a whitelist of known table names. Low priority given current usage.
