# MotorsportTracker - Workspace Context

## Quick Reference Commands

### Running Tests
```bash
# Run all Go tests
make go-test

# Run specific integration test suite
go test ./src/Golang/... -run TestIntegration_<SuiteName>

# Run tests in docker
docker-compose exec golang go test ./src/Golang/...
```

### Database Operations
```bash
# Reload core database (dev)
make db.core.reload

# Reload test database
make db.core.reload.test

# Run migrations
make run-dbmigrate-core
```

### Building Applications
```bash
# Build all Go apps
make go-build

# Build specific app
make build-processor
make build-publisher
```

## Current Work Areas

### 1. Scraping Module (`src/Golang/motorsporttracker/scrapping/`)
**Purpose**: Extract data from motorsportstats.com API

**Key Components**:
- **Handlers**: Process messages from queue, orchestrate scraping
- **Repositories**: Save scraped data to database
- **Intents**: Define available scraping operations

**Adding a New Scraping Operation**:
1. Create `domain/intent.go` with intent definition
2. Create `domain/handler.go` with business logic
3. Create `infrastructure/save_*_repository.go` for persistence
4. Add SQL migration in `etc/Migrations/core/`
5. Register handler in `apps/Backend/CommandsProcessor/main.go`
6. Add intent in `apps/Backend/CommandsPublisher/main.go`

### 2. Gateway Module (`src/Golang/motorsportstats/`)
**Purpose**: Abstract external API communication

**Layers**:
- **Connector** (`connector/infrastructure/`): HTTP communication, caching
- **Gateway** (`gateway/`): JSON parsing, domain object creation
- **Domain** (`gateway/domain/`): Data structures

### 3. Shared Modules (`src/Golang/shared/`)
**Available Utilities**:
- `cache/`: In-memory, database, and filesystem caching
- `client/`: HTTP client abstraction
- `database/`: PostgreSQL connection pooling (pgx)
- `messaging/`: Queue abstraction (SQS, in-memory)
- `fn/`: Functional utilities (Must, MustReturn, Ptr, Deref)
- `crypto/`: Hashing utilities
- `env/`: Environment variable management

## Common Tasks

### Adding a New Database Table

1. **Create Migration** (`etc/Migrations/core/YYYYMMDDHHMMSS_create_<table>_table.up.sql`):
```sql
CREATE TABLE IF NOT EXISTS <table_name> (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    -- your columns here
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS <table_name>_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    uuid UUID NOT NULL,
    -- your columns here
    hash TEXT NOT NULL,
    valid_from TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to TIMESTAMP
);

-- Add trigger for automatic history tracking
CREATE OR REPLACE FUNCTION update_<table_name>_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE <table_name>_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO <table_name>_history (id, uuid, /* columns */, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, /* NEW.columns */, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_<table_name>_history
    AFTER INSERT OR UPDATE ON <table_name>
    FOR EACH ROW
    EXECUTE FUNCTION update_<table_name>_history();
```

2. **Run Migration**:
```bash
make run-dbmigrate-core
```

### Creating a Repository

Template for save repository:
```go
type Save<Entity>Repository struct {
    db *database.PGXPoolAdapter
}

func NewSave<Entity>Repository(db *database.PGXPoolAdapter) *Save<Entity>Repository {
    return &Save<Entity>Repository{db: db}
}

func (s *Save<Entity>Repository) Save<Entity>(ctx context.Context, entities []*domain.Entity) error {
    if len(entities) == 0 {
        slog.Debug("No entities to save")
        return nil
    }

    var rows [][]interface{}
    for _, entity := range entities {
        // Dereference pointers with defaults
        fieldVal := fn.Deref(entity.Field, "")
        
        // Calculate hash
        hash := crypto.Hash(fmt.Sprintf("%s|%s|...", entity.UUID, fieldVal))
        
        rows = append(rows, []interface{}{entity.UUID, entity.Field, hash})
    }

    cols := []string{"uuid", "field", "hash"}
    
    stats, err := shared.Save(ctx, s.db, "table_name", "uuid", cols, rows)
    if err != nil {
        return fmt.Errorf("saving entities: %w", err)
    }

    slog.Info("Entities saved", "count", len(entities), 
              "inserted", stats.Inserted, "updated", stats.Updated)

    return nil
}
```

### Testing Checklist

When creating tests:
- [ ] Use appropriate suite suffix (Unit/Integration/Functional)
- [ ] Setup/Teardown for test fixtures
- [ ] Use `fn.Must()` for setup that should never fail
- [ ] Test both success and error cases
- [ ] Verify counts/effects, not just absence of errors
- [ ] Clean up test data in TearDown

### Debugging Tips

1. **Check logs**: Apps use structured logging with `slog`
2. **Inspect database**: Use `make db.<name>.connect` to access psql
3. **Run tests with verbose**: `go test -v ./...`
4. **Check environment**: Ensure `.env.local` has correct values

## Project-Specific Gotchas

1. **UUID vs ID**: Always use UUID for external references, ID for internal foreign keys
2. **Timestamps**: Use `shared.PrepareTimestamp()` for Unix timestamp to time.Time conversion
3. **Batch Operations**: Use `shared.Save()` which handles large batches automatically
4. **Testing Databases**: Always use `tests` environment for integration tests
5. **Queue Messages**: Message metadata is `map[string]string` - all values are strings
6. **Error Wrapping**: Always preserve original error with `%w` for error chains

## Dependencies Version Reference

- **Go**: 1.25.1
- **PostgreSQL**: 16.9
- **pgx**: v5.7.5
- **testify**: v1.10.0
- **AWS SDK**: v1.55.7
