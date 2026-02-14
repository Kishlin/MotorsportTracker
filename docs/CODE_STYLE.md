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

- `SetupSuite()` — Suite-wide setup (runs once before all tests)
- `SetupSubTest()` — Cleanup/reset between test cases (runs before each `s.Run()`)
- Use `s.Run()` over `s.T().Run()` when you need per-test cleanup
- **Avoid `TearDownTest()`** — use `SetupSubTest()` instead for state reset

### Test Utilities

- `fn.Must()` — For setup that should never fail
- `fn.MustReturn()` — For setup returning a value that should never fail

### Test Checklist

- Use appropriate suite suffix (Unit/Integration/Functional)
- Test both success and error cases
- Verify counts/effects, not just absence of errors
- Use `tests` environment for integration tests
