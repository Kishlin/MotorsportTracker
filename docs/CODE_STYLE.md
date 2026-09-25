# Code Style

## Go

### Boolean Comparisons

Use explicit equality checks — avoid the `!` operator for readability:

```go
// Good
if ok == false || value == "" {
    return fmt.Errorf("validation failed: %w", err)
}

// Bad
if !ok || value == "" {
    return fmt.Errorf("validation failed: %w", err)
}
```

### Early Returns

Reduce nesting by returning early on errors:

```go
if err != nil {
    return fmt.Errorf("context: %w", err)
}
```

### Error Wrapping

Always preserve the original error with `%w` for error chains:

```go
return fmt.Errorf("saving fetched series: %w", err)
```

### Naming Conventions

- **Interfaces** describe behavior: `Gateway`, `Repository`, `Cache`, `Handler`
- **Implementations** describe mechanism: `GatewayUsingConnector`, `SaveSeriesRepository`, `DatabaseCache`, `FileSystemCache`
- **Use Cases** describe the action: `ScrapeSeriesUseCase`, `ScrapeCalendarUseCase`

### JSON Struct Tags

Use camelCase for JSON serialization:

```go
type Series struct {
    UUID      string  `json:"uuid"`
    ShortName *string `json:"shortName"`
    ShortCode *string `json:"shortCode"`
}
```

### Pointer Default Values

Use `fn.Deref()` for safely dereferencing pointers with defaults:

```go
nameVal := fn.Deref(series.Name, "")
```

### Structured Logging

Use `slog` at appropriate levels:

```go
slog.Debug("Cache miss")
slog.Info("Saved series", "count", len(fetchedSeries))
slog.Warn("Gateway returned 0 series, aborting")
slog.Error("Failed to connect", "error", err)
```

## SQL

### Column Types

- `TEXT` over `VARCHAR` unless there's a specific length constraint
- `UUID` for external/distributed identifiers
- `SERIAL` for auto-increment internal IDs
- Always include `created_at` and `updated_at` timestamps

### History Tables

Every table has a corresponding `*_history` table with temporal tracking (`valid_from`, `valid_to`). Changes are tracked via `update_*_history()` trigger functions.

### Conflict Handling

Use `ON CONFLICT (uuid) DO NOTHING` for idempotent inserts, or `ON CONFLICT ... DO UPDATE SET ... WHERE table.hash IS DISTINCT FROM EXCLUDED.hash` for upserts with change detection.

### Foreign Keys

Default to `ON DELETE RESTRICT`.

## Testing

### Test Framework

Use `testify/suite` for test organization.

### Suite Suffix Conventions

- `*UnitTestSuite` — Unit tests, no external dependencies
- `*IntegrationTestSuite` — Requires database or external services
- `*FunctionalTestSuite` — End-to-end flows

### Test Lifecycle

- `SetupSuite()` — Suite-wide setup (runs once before all tests). Integration suites load the environment here with `env.OverrideAppEnv("tests")` then `env.LoadEnv()`, and never restore `APP_ENV` afterwards (see below)
- `SetupSubTest()` — Cleanup/reset between test cases (runs before each `s.Run()`)
- **Always use `s.Run()`, never `s.T().Run()`** — `s.Run()` points `s.T()` at the subtest, so a failed suite assertion is reported on the right case, and it is what makes `SetupSubTest()` fire at all
- **Avoid `TearDownTest()`** — use `SetupSubTest()` instead for state reset
- Hook names are exact: testify silently ignores a misspelled `TeardownSuite()`

### Integration Test Isolation

Every integration and functional suite calls `t.Parallel()`, and `go test ./...` runs packages as parallel processes, so suites share the `core-test` and client-cache test databases while running at the same time:

- **One UUID prefix per suite**, the first three groups (`xxxxxxxx-xxxx-xxxx`), freshly generated and declared once in a `const`. Every UUID the suite writes is built from it, and teardown deletes `LIKE '<prefix>-%'`. `scripts/fixture-prefix-check.sh` enforces this before every `make go-test` and `scripts/test-runner.sh` run.
- **Searched strings carry the prefix.** Repositories that search with `LIKE '%kw%'` would match another suite's generic names, so names and keywords end with the prefix.
- **Other `UNIQUE` columns stay unique.** Every core table has a `UNIQUE` `hash`; in fixtures, use the row's own UUID as its hash.
- **Leave `APP_ENV` set.** Restoring it in teardown could flip it back while a parallel suite is loading its env files. It only lives as long as the test binary.

The full rules, with examples, are in `src/Golang/CLAUDE.md` under Tests.

### Test Utilities

- `fn.Must()` — For setup that should never fail
- `fn.MustReturn()` — For setup returning a value that should never fail

### Test Checklist

- Use appropriate suite suffix (Unit/Integration/Functional)
- Test both success and error cases
- Verify counts/effects, not just absence of errors
- Use `tests` environment for integration tests
- Call `t.Parallel()` and build every fixture UUID, searched string and hash from the suite's prefix `const`
