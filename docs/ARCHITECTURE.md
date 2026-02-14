# Architecture

## System Overview

MotorsportTracker scrapes motorsport data from motorsportstats.com and serves it through a Next.js frontend.

```
Motorsportstats.com -> ETL -> Core DB -> Worker -> Cache/Memcached -> API -> Next.js -> User
```

See the PlantUML diagrams for visual representations:
- [Infrastructure.plantuml](Infrastructure.plantuml) — High-level component diagram
- [Flowchart Setup.plantuml](Flowchart%20Setup.plantuml) — Scraping, sync, and static generation flows
- [Flowchart Usage.plantuml](Flowchart%20Usage.plantuml) — User request flows and data update cycles

## Components

### ETL (Scraping Pipeline)

Extracts data from the motorsportstats.com API, transforms it into domain objects, and loads it into the Core database. Operates via CLI commands or queue-based message processing.

### Core Database (PostgreSQL)

Primary data store. All scraped data lands here with history tracking via trigger-based `*_history` tables. Used as source of truth for the Worker.

### Worker

Reads from Core DB, computes derived data (calendar aggregations, standings), and writes to Cache DB and Memcached for fast API access.

### Cache Database (PostgreSQL)

Stores pre-computed data optimized for API read patterns (calendar data, etc.).

### Memcached

In-memory cache for frequently accessed data (standings, etc.).

### API

Serves cached data to the Next.js frontend. Reads from Cache DB and Memcached.

### Next.js Frontend

Located at `apps/MotorsportTracker/Frontend/`. Uses Material-UI. Supports both Static Site Generation (SSG) for standings and Server Side Rendering (SSR) for dynamic content like upcoming events.

## Data Flows

### Setup Flow (Scraping + Sync + Static Generation)

1. **Scraping**: CLI triggers ETL -> extracts from external API -> transforms -> loads into Core DB
2. **Sync**: CLI triggers Worker -> queries Core DB -> computes -> saves to Cache/Memcached
3. **Static Generation**: CLI triggers Next.js build -> requests API -> API reads from Memcached -> Next.js caches pages

### Usage Flow

- **SSG pages** (standings): Next.js serves from its own cache
- **SSR pages** (upcoming events): Next.js renders server-side -> queries API -> API reads from Cache DB

### Update Flow

1. CLI triggers scraping -> new data lands in Core DB
2. CLI triggers sync -> Worker recomputes -> updates Memcached
3. CLI invalidates Next.js cache -> next request triggers SSR -> re-caches

## Go Module Organization

The project uses a Go workspace (`go.work`) with 5 modules:

### `src/Golang/` — Core Library

```
motorsporttracker/
  scrapping/
    series/           # Series scraping (Use Case, Handler, Repository, Intent)
    seasons/          # Seasons scraping (multiple strategies: by keyword, by ID, all)
    calendar/         # Calendar/events scraping
    classification/   # Race classification scraping
    shared/           # Shared repository helpers (Save, UpsertStats)
  dependencyinjection/
    infrastructure/   # ServicesRegistry (lazy-init DI container)

motorsportstats/
  connector/
    infrastructure/   # HTTP client + cache decorator (CachedConnector)
  gateway/
    domain/           # Gateway interface + domain structs (Series, Season, Event, Classification, etc.)
    infrastructure/   # GatewayUsingConnector (JSON parsing)

shared/
  application/        # Intent interface and BaseIntent
  cache/              # Cache interface, DatabaseCache, FileSystemCache, MemoryCache
  client/             # HTTP client abstraction
  crypto/             # Hashing utilities
  database/           # PGXPoolAdapter (pgx connection pooling)
  env/                # Environment variable loading (.env hierarchy)
  fn/                 # Functional utilities (Must, MustReturn, Ptr, Deref)
  logger/             # slog setup
  messaging/          # Message, Handler, SQSQueue
```

### `apps/Backend/MotorsportTracker/` — CLI Application

Direct command processing. Accepts subcommands (`series`, `seasons`, `events`, `classification`), creates the appropriate Intent + Handler, converts CLI args to a Message, and processes it immediately.

### `apps/Backend/CommandsPublisher/` — Queue Publisher

Same subcommands as MotorsportTracker CLI, but instead of processing directly, converts intents to messages and publishes them to SQS.

### `apps/Backend/CommandsProcessor/` — Queue Consumer

Long-running process that polls SQS for messages. Registers handlers for all scraping intent types. Supports configurable worker count (`-workers` flag) and graceful shutdown on SIGINT/SIGTERM.

### `apps/Backend/DBMigrate/` — Migration Runner

Uses `golang-migrate` to apply SQL migrations from `etc/Migrations/`. Reads migration source and DB connection from environment variables.

## Frontend

Located at `apps/MotorsportTracker/Frontend/`. Next.js application with:

- `app/` — Next.js pages and layouts
- `src/MotorsportTracker/` — Main application components
- `src/MotorsportGraph/` — Data visualization (tyre history, race pace, fastest laps)
- `src/Canvas/` — Canvas-based rendering
- `src/Shared/` — Shared utilities

## Infrastructure

### Docker Compose Services (Active)

| Service | Image | Purpose | Ports |
|---------|-------|---------|-------|
| `sqs` | ElasticMQ | Message queue (SQS-compatible) | 9324, 9325 (UI) |
| `postgres` | PostgreSQL 16.9 | Databases (core-dev, core-test, client-cache-dev, client-cache-test) | 6378 -> 5432 |
| `golang` | Go 1.25 | Development container for building and running Go apps | — |

### Databases

- **core-dev / core-test**: Primary data store with scraped motorsport data
- **client-cache-dev / client-cache-test**: Cache for external API responses (connector cache)

### Client Cache (Filesystem)

API responses can also be cached to the filesystem at `etc/ConnectorCache/` (enabled via `USE_FS_CACHE=true`), organized by namespace (series, seasons, calendar, classification).
