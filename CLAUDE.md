# CLAUDE.md

This file provides guidance to Claude Code when working with this repository.

## Quick Start

```bash
make start                # Start Docker containers, run migrations, vendor deps
make go-build             # Build all Go applications
make go-test              # Run all Go tests across all modules
make go-lint              # Lint all Go code with golangci-lint
make go-run ARGS="series" # Run MotorsportTracker CLI directly
```

All commands run inside Docker containers. See `docs/DEVELOPMENT.md` for the full Make targets reference.

## Architecture

MotorsportTracker is a motorsport data aggregation platform that scrapes data from motorsportstats.com.

**Pipeline**: Scraping (ETL) -> Core DB -> Worker -> Cache/Memcached -> API -> Next.js

**Hexagonal architecture**: `domain/` at the core (business logic, interfaces), `infrastructure/` at the edges (implementations). Tests are colocated with the code they test.

See `docs/ARCHITECTURE.md` for detailed component descriptions and data flow diagrams.

## Project Structure

```
apps/
  Backend/
    MotorsportTracker/    # CLI for direct command processing
    CommandsProcessor/    # Queue consumer (processes scraping messages)
    CommandsPublisher/    # Queue publisher (sends scraping intents to SQS)
    DBMigrate/            # Database migration runner (golang-migrate)
  MotorsportTracker/
    Frontend/             # Next.js + Material-UI frontend
src/Golang/
  motorsporttracker/      # Core scraping modules (series, seasons, calendar, classification)
  motorsportstats/        # External API gateway (connector + gateway layers)
  shared/                 # Shared utilities (cache, database, messaging, crypto, env, fn)
etc/
  Migrations/             # SQL migrations (core/, client-cache/)
  ConnectorCache/         # Filesystem cache for API responses
docs/                     # PlantUML diagrams and documentation
go.work                   # Go workspace (5 modules)
```

## PHP Migration Status

Legacy PHP code exists in `src/Backend/`, `apps/Backoffice/`, and parts of `apps/`. **Ignore all PHP code for new development.** All backend work is Go. The Next.js frontend is active.

## Documentation Index

| Doc | When to read |
|-----|-------------|
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | Understanding system components, data flow, module organization |
| [PATTERNS.md](docs/PATTERNS.md) | Working with Use Cases, Repositories, Gateways, Handlers, Intents, DI |
| [CODE_STYLE.md](docs/CODE_STYLE.md) | Writing Go code, SQL migrations, tests |
| [DEVELOPMENT.md](docs/DEVELOPMENT.md) | Environment setup, Make targets, adding new scraping ops or tables, debugging |

## Key Patterns

- **Use Case**: Domain logic orchestrator — fetches via Gateway, saves via Repository
- **Repository**: Persistence layer using `shared.Save()` with hash-based change detection
- **Gateway**: External API abstraction — Connector (HTTP) -> Gateway (JSON parsing) -> Domain objects
- **Handler**: Message processor — extracts params from message metadata, delegates to Use Case
- **Intent**: CLI command -> Message conversion with argument/option validation
- **ServicesRegistry**: Dependency injection via `sync.Once` lazy initialization

See `docs/PATTERNS.md` for code examples.

## Design Principles

- **Simplest approach first** — Do NOT introduce abstractions (interfaces, wrappers, resolver patterns) unless explicitly requested. When in doubt, ask before adding indirection.
- **Fail loudly** — When errors or invalid states are detected, panic or return an explicit error. Do NOT add silent fallbacks or defensive nil guards that mask bugs.
- **Delete dead code completely** — When removing dead code, fully delete the files. Do not leave behind modified comments or empty shells.

## Workflow

- **Plan mode is sacred** — Stay in plan mode until the user explicitly approves the plan. Do not begin implementation or exit planning prematurely.
- **Verify docs against code** — After making code changes, always verify documentation values against actual source code. Never guess values — read them from the code.
- **CLAUDE.md is the single source of context** — Do not create memory files, personal notes, or auxiliary tracking files.

## Communication Style

- **Be direct and challenge me** — Push back when you think I'm wrong. No flattery, no sugarcoating, no sycophancy.
