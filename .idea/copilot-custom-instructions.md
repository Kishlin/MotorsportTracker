# JetBrains + Copilot Custom Instructions

## IDE-Specific Guidelines

### Code Generation Preferences

When generating Go code in this project:
- Use JetBrains' built-in formatters (gofmt, goimports)
- Leverage IDE's quick-fixes for imports
- Suggest test generation with `testify/suite` structure
- Use IDE's refactoring tools for method extraction

### Import Organization
```go
import (
    // Standard library
    "context"
    "fmt"
    
    // Third-party
    "github.com/stretchr/testify/suite"
    
    // Project internal - use full path
    domain "github.com/kishlin/MotorsportTracker/src/Golang/<module>/domain"
    shared "github.com/kishlin/MotorsportTracker/src/Golang/shared/<utility>/infrastructure"
)
```

### Keyboard Shortcuts Context

When suggesting refactoring:
- **Extract Method**: Ctrl+Alt+M (Win/Linux) / Cmd+Alt+M (Mac)
- **Rename**: Shift+F6
- **Generate**: Alt+Insert (Win/Linux) / Cmd+N (Mac)
- **Run tests**: Ctrl+Shift+F10 (Win/Linux) / Ctrl+Shift+R (Mac)

### Run Configurations

Suggest creating run configurations for:
1. **All Tests**: Run all Go tests in workspace
2. **Integration Tests**: Run with `-tags=integration`
3. **Specific Module**: Run tests for current module
4. **Docker Compose**: Start/stop containers

### File Templates

When creating new files, follow these templates:

#### Handler
```go
package domain

import (
    "context"
    "fmt"
    
    messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type <Action>Handler struct {
    // dependencies
}

func New<Action>Handler(/* deps */) *<Action>Handler {
    return &<Action>Handler{
        // init
    }
}

func (h *<Action>Handler) Handle(ctx context.Context, message messaging.Message) error {
    // implementation
    return nil
}
```

#### Repository
```go
package infrastructure

import (
    "context"
    "fmt"
    
    domain "github.com/kishlin/MotorsportTracker/src/Golang/<module>/domain"
    database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type <Action>Repository struct {
    db *database.PGXPoolAdapter
}

func New<Action>Repository(db *database.PGXPoolAdapter) *<Action>Repository {
    return &<Action>Repository{db: db}
}

// Implementation methods
```

#### Test Suite
```go
package domain

import (
    "context"
    "testing"
    
    "github.com/stretchr/testify/suite"
)

type <Name>TestSuite struct {
    suite.Suite
    
    // test fixtures
}

func (suite *<Name>TestSuite) SetupSuite() {
    // one-time setup
}

func (suite *<Name>TestSuite) TearDownSuite() {
    // one-time cleanup
}

func (suite *<Name>TestSuite) SetupTest() {
    // per-test setup
}

func (suite *<Name>TestSuite) Test<Scenario>() {
    suite.T().Run("describes what it tests", func(t *testing.T) {
        // test implementation
    })
}

func Test<Type>_<Name>(t *testing.T) {
    suite.Run(t, new(<Name>TestSuite))
}
```

### Code Inspection Hints

Pay attention to IDE warnings for:
- Unused imports/variables
- Error handling (never ignore errors)
- Context usage (always pass context.Context)
- Resource leaks (missing Close() calls)
- SQL injection risks (use parameterized queries)

### Database Tool Integration

When working with SQL:
- Use IDE's database tool to validate queries
- Format SQL with Ctrl+Alt+L
- Suggest using query parameters ($1, $2) instead of string concatenation
- Always test queries in IDE's console before committing

### Debugging Suggestions

When suggesting debug approaches:
- Use IDE's debugger for step-through debugging
- Set conditional breakpoints for specific scenarios
- Use "Evaluate Expression" for complex state inspection
- Leverage "Run to Cursor" for quick iteration

### Git Integration

When suggesting commits:
- Use conventional commit format: `type(scope): description`
- Types: feat, fix, docs, test, refactor, chore
- Keep commits atomic and well-scoped

### Project-Specific Live Templates

Suggest creating these live templates:

**`goerr`** - Error wrapping:
```go
if err != nil {
    return fmt.Errorf("$CONTEXT$: %w", err)
}
```

**`golog`** - Structured logging:
```go
slog.$LEVEL$("$MESSAGE$", slog.$TYPE$("$KEY$", $VALUE$))
```

**`gotest`** - Test case:
```go
suite.T().Run("$NAME$", func(t *testing.T) {
    $END$
})
```

### Module-Specific Context

When in specific directories:
- **`/domain/`**: Focus on business logic, avoid infrastructure concerns
- **`/infrastructure/`**: Implementation details, external dependencies OK
- **`*_test.go`**: Testing context - use mocks, fixtures, assertions
- **`main.go`**: Entry point - keep minimal, delegate to packages

### Performance Optimization Hints

When suggesting optimizations:
- Batch database operations with `shared.Save()`
- Use connection pooling (already configured)
- Cache expensive operations
- Profile before optimizing (go pprof)

### Security Considerations

Always consider:
- SQL injection prevention (parameterized queries)
- Input validation before database operations
- Error messages shouldn't leak sensitive data
- Proper context propagation for timeouts
