# CLAUDE.md

Guidance for Claude Code in this repository. Area-specific rules live in nested `CLAUDE.md` files and load automatically when you work in that subtree.

## Quick Start

```bash
make start                      # Containers + migrations + vendored deps
make go-build                   # Build all Go applications
make go-test                    # Run all Go tests across all modules
make go-lint                    # Lint with golangci-lint
make go-run ARGS="scrape:series"  # Run the MotorsportTracker CLI directly
make run-api-canary             # Check the live API against the connector schemas
```

Go commands run inside the `golang` container. The CLI subcommand is the **full intent name** — `scrape:series`, not `series`.

## Architecture

MotorsportTracker aggregates motorsport data scraped from motorsportstats.com.

**Pipeline**: Scraping (ETL) → Core DB → Worker → Cache/Memcached → API → Next.js

**Hexagonal**: `domain/` at the core (business logic, interfaces), `infrastructure/` at the edges (implementations). Tests are colocated with the code they test.

## Project Structure

```
apps/
  Backend/
    MotorsportTracker/    # CLI for direct command processing
    CommandsProcessor/    # Queue consumer (processes scraping messages)
    CommandsPublisher/    # Queue publisher (sends scraping intents to SQS)
    DBMigrate/            # Database migration runner (golang-migrate)
    ApiCanary/            # Live API schema-drift check (no DB, no queue)
  MotorsportTracker/
    Frontend/             # Next.js + Material-UI frontend
src/Golang/
  motorsporttracker/      # Core scraping modules (series, seasons, calendar, classification)
    registration/         # Centralized handler + intent registration
  motorsportstats/        # External API gateway (connector + gateway layers)
  shared/                 # Shared utilities (cache, database, messaging, crypto, env, fn)
etc/
  Migrations/             # SQL migrations — lowercase core/ and client-cache/ only
  ConnectorCache/         # Filesystem cache for API responses
docs/                     # PlantUML diagrams and documentation
go.work                   # Go workspace (6 modules)
```

## Scoped Instructions

| File | Loads when working in |
|---|---|
| [src/Golang/CLAUDE.md](src/Golang/CLAUDE.md) | Go core library — layers, naming, registration, persistence |
| [etc/Migrations/CLAUDE.md](etc/Migrations/CLAUDE.md) | SQL migrations — live directories, history tables, triggers |
| [apps/MotorsportTracker/Frontend/CLAUDE.md](apps/MotorsportTracker/Frontend/CLAUDE.md) | Next.js frontend |

## Tooling

- `/new-scraping-op <module>` — scaffold a scraping operation end to end
- `architecture-check` skill — full hexagonal boundary sweep
- `test-runner` skill — scoped Go test runs inside the container

A `PostToolUse` hook runs `gofmt` on every edited `.go` file, and blocks the edit when a file under `domain/` violates the hexagonal boundary. Both are silent when they pass.

## PHP Migration Status

Legacy PHP exists in `src/Backend/`, `apps/Backoffice/`, and parts of `apps/`. **Ignore all PHP for new development.** Backend work is Go; the Next.js frontend is active.

## Documentation Index

| Doc | When to read |
|-----|-------------|
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | System components, data flow, module organization |
| [PATTERNS.md](docs/PATTERNS.md) | Use Cases, Repositories, Gateways, Handlers, Intents, Registration, DI |
| [CODE_STYLE.md](docs/CODE_STYLE.md) | Go code, SQL migrations, tests |
| [DEVELOPMENT.md](docs/DEVELOPMENT.md) | Environment setup, Make targets, debugging |

## Key Patterns

- **Use Case**: Domain orchestrator — fetches via Gateway, saves via Repository
- **Repository**: Persistence via `shared.Save()` with hash-based change detection
- **Gateway**: Connector (HTTP) → Gateway (JSON parsing) → domain objects
- **Handler**: Message processor — extracts metadata params, delegates to a Use Case
- **Intent**: CLI command → Message, with argument/option validation. Lives in `infrastructure/`
- **ServicesRegistry**: Dependency injection via `sync.Once` lazy initialization

## Design Principles

- **Simplest approach first** — Do NOT introduce abstractions (interfaces, wrappers, resolver patterns) unless explicitly requested. When in doubt, ask before adding indirection.
- **Fail loudly** — When errors or invalid states are detected, panic or return an explicit error. Do NOT add silent fallbacks or defensive nil guards that mask bugs.
- **Delete dead code completely** — Fully delete the files. Do not leave behind modified comments or empty shells.

## Workflow

- **Plan mode is sacred** — Stay in plan mode until I explicitly approve the plan. Do not begin implementation or exit planning prematurely.
- **Verify docs against code** — Never guess values; read them from the source. The docs in `docs/` have drifted before and will again.

## Communication Style

- **Be direct and challenge me** — Push back when you think I'm wrong. No flattery, no sugarcoating, no sycophancy.
