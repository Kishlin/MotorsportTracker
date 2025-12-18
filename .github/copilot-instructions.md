# MotorsportTracker - Copilot Instructions

## Project Overview
MotorsportTracker is a motorsport data aggregation and analysis platform that scrapes data from motorsportstats.com and provides APIs for consumption.

**Important**: This project is being actively rewritten from PHP to Go. All backend logic is now in Go. Any PHP code is legacy and should be ignored for new development.

## Architecture Principles

### 1. Golang Structure
- **Hexagonal Architecture**: Domain at the core, infrastructure at the edges
- **Directory Structure**: 
  - `domain/`: Business logic, interfaces, entities
  - `infrastructure/`: Implementations (repositories, gateways, etc.)
  - Tests are colocated with the code they test
- **Naming Conventions**:
  - Interfaces end with the behavior they represent (e.g., `Gateway`, `Repository`)
  - Implementations describe their mechanism (e.g., `GatewayUsingConnector`, `SaveSeriesRepository`)

### 2. Testing Strategy
- **Unit tests**: `*_test.go` files in same package, suffix `UnitTestSuite`
- **Integration tests**: Require database/external services, suffix `IntegrationTestSuite`
- **Functional tests**: End-to-end flows, suffix `FunctionalTestSuite`
- Use `testify/suite` for test organization
- Use `fn.Must()` and `fn.MustReturn()` for setup code that should never fail

### 3. Database Patterns
- **Migrations**: Located in `etc/Migrations/core/`
- **History tables**: Every table has a corresponding `*_history` table with temporal tracking
- **Triggers**: Automatic history tracking via `update_*_history()` functions
- **Conflict handling**: Use `ON CONFLICT (uuid) DO NOTHING` for idempotent inserts

### 4. Error Handling
- Always wrap errors with context: `fmt.Errorf("descriptive context: %w", err)`
- Use structured logging with `slog` package
- Log at appropriate levels: Debug for operations, Info for milestones, Warn for recoverable issues, Error for failures

### 5. Dependency Management
- Use `sync.Once` for singleton initialization in registry pattern
- Close resources in defer statements immediately after successful creation
- Use context.Context for cancellation and timeout propagation

## Code Style

### Golang
```go
// Prefer functional options for complex constructors
func NewService(required string, opts ...Option) *Service

// Use early returns to reduce nesting
if err != nil {
    return fmt.Errorf("context: %w", err)
}

// For boolean comparisons, use explicit equality checks (avoid '!' operator for readability)
if ok == false || value == "" {
    return fmt.Errorf("validation failed: %w", err)
}

// Struct tags for JSON serialization use camelCase
type Series struct {
    UUID      string  `json:"uuid"`
    ShortName *string `json:"shortName"`
}

// Use fn.Deref() for pointer default values
nameVal := fn.Deref(series.Name, "")
```

### SQL
- Use `SERIAL` for auto-increment IDs
- Use `UUID` for distributed identifiers
- Use `TEXT` instead of `VARCHAR` unless there's a specific length constraint
- Always include `created_at` and `updated_at` timestamps
- Foreign keys should be `ON DELETE RESTRICT` by default

### Test Naming
```go
func (suite *ServiceTestSuite) TestMethodName() {
    suite.T().Run("describes the scenario", func(t *testing.T) {
        // Test implementation
    })
}
```

## Common Patterns

### Repository Save Pattern
- Use `shared.Save()` helper for bulk upserts
- Calculate hash from all significant fields for change detection
- Return `UpsertStats` with inserted/updated counts
- Log at Info level with counts

### Gateway Pattern
- Connector handles HTTP/external communication
- Gateway handles JSON parsing and domain object creation
- Cache decorators wrap connectors for performance

### Handler Pattern (Message Processing)
- Extract and validate parameters from message metadata
- Fetch reference IDs from database
- Call gateway for external data
- Save to database
- Log success with relevant identifiers

## File Locations

- **Core domain**: `src/Golang/<module>/domain/`
- **Infrastructure**: `src/Golang/<module>/infrastructure/`
- **Shared utilities**: `src/Golang/shared/*/domain/`
- **Migrations**: `etc/Migrations/core/`
- **Apps**: `apps/Backend/<AppName>/`

## Environment Variables
- Load via `env.LoadEnv()` at application start
- Support `.env`, `.env.local`, `.env.<env>`, `.env.<env>.local` hierarchy
- Always provide fallback values or panic early if required vars are missing

## When Suggesting Code

1. **Follow existing patterns** in the codebase
2. **Include error handling** - never ignore errors
3. **Add tests** for new functionality
4. **Consider batch operations** for performance
5. **Use structured logging** instead of fmt.Printf
6. **Respect the hexagonal architecture** - keep domain clean of infrastructure concerns
