# Code Patterns

## Use Case Pattern

Use Cases are domain-level orchestrators. They depend on interfaces (Gateway, Repository) and coordinate the business logic flow.

```go
// src/Golang/motorsporttracker/scrapping/series/domain/use_case.go

type SaveSeriesRepository interface {
    SaveSeries(ctx context.Context, series []*motorsportstats.Series) error
}

type ScrapeSeriesUseCase struct {
    motorsportStatsGateway motorsportstats.Gateway
    saveSeriesRepository   SaveSeriesRepository
}

func NewScrapeSeriesUseCase(
    motorsportStatsGateway motorsportstats.Gateway,
    saveSeriesRepository SaveSeriesRepository,
) *ScrapeSeriesUseCase {
    return &ScrapeSeriesUseCase{
        motorsportStatsGateway: motorsportStatsGateway,
        saveSeriesRepository:   saveSeriesRepository,
    }
}

func (u *ScrapeSeriesUseCase) Execute(ctx context.Context) error {
    fetchedSeries, err := u.motorsportStatsGateway.GetSeries(ctx)
    if err != nil {
        return fmt.Errorf("fetching series: %w", err)
    }

    if len(fetchedSeries) == 0 {
        slog.Warn("Gateway returned 0 series, aborting")
        return nil
    }

    err = u.saveSeriesRepository.SaveSeries(ctx, fetchedSeries)
    if err != nil {
        return fmt.Errorf("saving fetched series: %w", err)
    }

    slog.Info("Saved series", "count", len(fetchedSeries))
    return nil
}
```

Key points:
- Dependencies are interfaces defined in the `domain/` package
- Constructor injection via `New*UseCase()`
- Returns `error`, logs at appropriate levels
- Error wrapping with `%w` preserves the chain

## Repository Save Pattern

Repositories use `shared.Save()` for bulk upserts with hash-based change detection.

```go
// Template for a save repository

type SaveEntityRepository struct {
    db *database.PGXPoolAdapter
}

func NewSaveEntityRepository(db *database.PGXPoolAdapter) *SaveEntityRepository {
    return &SaveEntityRepository{db: db}
}

func (s *SaveEntityRepository) SaveEntities(ctx context.Context, entities []*domain.Entity) error {
    if len(entities) == 0 {
        slog.Debug("No entities to save")
        return nil
    }

    var rows [][]interface{}
    for _, entity := range entities {
        fieldVal := fn.Deref(entity.Field, "")

        // Hash from all significant fields for change detection
        hash := crypto.Hash(fmt.Sprintf("%s|%s", entity.UUID, fieldVal))

        rows = append(rows, []interface{}{entity.UUID, fieldVal, hash})
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

How `shared.Save()` works:
- Generates an `INSERT ... ON CONFLICT DO UPDATE SET ... WHERE hash IS DISTINCT FROM EXCLUDED.hash` query
- Automatically batches when parameter count exceeds 1000
- Returns `UpsertStats{Inserted, Updated}` counts
- The hash column enables skipping unchanged rows

## Gateway Pattern

Three-layer abstraction for external API communication:

### 1. Connector (HTTP layer)

```go
// Connector interface — returns raw bytes
type Connector interface {
    GetSeries(ctx context.Context) ([]byte, error)
    GetSeasons(ctx context.Context, seriesUUID string) ([]byte, error)
    GetCalendar(ctx context.Context, seasonUUID string) ([]byte, error)
    GetClassification(ctx context.Context, sessionUUID string) ([]byte, error)
}
```

### 2. Cache Decorator (wraps Connector)

```go
// CachedConnector wraps any Connector with a Cache layer
type CachedConnector struct {
    inner Connector
    cache cache.Cache  // domain.Cache interface: Get(namespace, key) / Set(namespace, key, value)
}

func (c *CachedConnector) GetSeries(ctx context.Context) ([]byte, error) {
    return c.getFromCacheOrConnector("series", "all", func() ([]byte, error) {
        return c.inner.GetSeries(ctx)
    })
}
```

Cache implementations: `DatabaseCache` (PostgreSQL), `FileSystemCache` (disk), `MemoryCache` (in-process). The decorator chain in `ServicesRegistry`:

```
ConnectorUsingClient (HTTP)
  -> CachedConnector (DatabaseCache)    // always active
    -> CachedConnector (FileSystemCache) // optional, via USE_FS_CACHE=true
```

### 3. Gateway (JSON parsing layer)

```go
// Gateway interface — returns domain objects
type Gateway interface {
    GetSeries(ctx context.Context) ([]*Series, error)
    GetSeasons(ctx context.Context, seriesUUID string) ([]*Season, error)
    GetCalendar(ctx context.Context, seasonUUID string) (*Calendar, error)
    GetClassification(ctx context.Context, sessionUUID string) (*Classification, error)
}

// GatewayUsingConnector parses Connector's raw bytes into domain structs
```

## Handler Pattern

Handlers process messages from the queue. They extract parameters from message metadata and delegate to a Use Case.

### Simple Handler (no parameters)

```go
// src/Golang/motorsporttracker/scrapping/series/infrastructure/handler.go

type ScrapeSeriesHandler struct {
    useCase *domain.ScrapeSeriesUseCase
}

func (h *ScrapeSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
    return h.useCase.Execute(ctx)
}
```

### Handler with Parameters

```go
// src/Golang/motorsporttracker/scrapping/calendar/infrastructure/handler.go

func (h *ScrapeCalendarHandler) Handle(ctx context.Context, message messaging.Message) error {
    seriesKeyword, year, err := h.paramsFromMessage(message)
    if err != nil {
        return fmt.Errorf("getting params from message: %w", err)
    }
    return h.useCase.Execute(ctx, seriesKeyword, year)
}

func (h *ScrapeCalendarHandler) paramsFromMessage(message messaging.Message) (string, int, error) {
    seriesKeyword, ok := message.Metadata["series"]
    if !ok || seriesKeyword == "" {
        return "", 0, errors.New("series search keywords is required")
    }

    yearStr, ok := message.Metadata["year"]
    if !ok || yearStr == "" {
        return "", 0, errors.New("year is required")
    }

    year, err := strconv.Atoi(yearStr)
    if err != nil {
        return "", 0, errors.New("invalid year format")
    }
    return seriesKeyword, year, nil
}
```

All handlers implement the `messaging.Handler` interface:
```go
type Handler interface {
    Handle(ctx context.Context, message Message) error
}
```

## Intent Pattern

Intents convert CLI commands into queue messages. They define the command's arguments and options, validate input, and produce a `messaging.Message`.

```go
// shared/application/infrastructure/intent.go

type Intent interface {
    ToMessage(arguments []string, options map[string]string) (messaging.Message, error)
}

// BaseIntent provides validation and message construction.
// Concrete intents embed BaseIntent and configure IntentConfig:
type IntentConfig struct {
    Name        string     // e.g., "scrap:series"
    Description string
    Arguments   []Argument // positional args
    Options     []Option   // named optional args
}
```

Flow: CLI args -> `Intent.ToMessage()` -> `messaging.Message{Type, Metadata}` -> Handler or SQS queue

## Dependency Injection (ServicesRegistry)

`ServicesRegistry` provides lazy-initialized singleton services via `sync.Once`:

```go
// src/Golang/motorsporttracker/dependencyinjection/infrastructure/services_registry.go

type ServicesRegistry struct {
    coreDB                 *databaseImpls.PGXPoolAdapter
    motorsportStatsGateway *gatewayImpls.GatewayUsingConnector
    intentsQueue           *messagingImpls.SQSQueue

    coreDBOnce                 sync.Once
    motorsportStatsGatewayOnce sync.Once
    intentsQueueOnce           sync.Once
    // ...
}

func (s *ServicesRegistry) GetCoreDatabase(ctx context.Context) *databaseImpls.PGXPoolAdapter {
    s.coreDBOnce.Do(func() {
        connStr := os.Getenv("POSTGRES_CORE_URL")
        if connStr == "" {
            panic("POSTGRES_CORE_URL environment variable is not set")
        }
        s.coreDB = databaseImpls.NewDatabaseUsingPGXPool(connStr)
        err := s.coreDB.Connect(ctx)
        if err != nil {
            panic("unable to connect to core database: " + err.Error())
        }
    })
    return s.coreDB
}
```

Key points:
- Services are created on first access, not at startup
- Missing required env vars cause a panic (fail loudly)
- `Close()` method cleans up all initialized resources
- Used by all three backend applications (MotorsportTracker, CommandsProcessor, CommandsPublisher)

## Caching

### Cache Interface

```go
// src/Golang/shared/cache/domain/cache.go

type Cache interface {
    Get(namespace, key string) (value []byte, hit bool, err error)
    Set(namespace, key string, value []byte) error
}
```

### Implementations

- **DatabaseCache**: Stores in PostgreSQL (`client-cache` database), keyed by namespace (table) and key
- **FileSystemCache**: Stores as files in `etc/ConnectorCache/<namespace>/<key>.json`
- **MemoryCache**: In-process map (used in tests)

### Decorator Chain

The `CachedConnector` wraps any `Connector` with a `Cache`. Multiple `CachedConnector` layers can be stacked. The `ServicesRegistry` builds:

1. `ConnectorUsingClient` — Makes HTTP requests to motorsportstats.com
2. Wrapped with `CachedConnector(DatabaseCache)` — Persistent cache in PostgreSQL
3. Optionally wrapped with `CachedConnector(FileSystemCache)` — Local file cache (when `USE_FS_CACHE=true`)

The outermost cache is checked first. On miss, it falls through to the inner layer.
