# CLAUDE.md

Guidance for Claude Code in this repository. Area-specific rules live in nested `CLAUDE.md` files and in `.claude/rules/`, and load automatically when you work in that subtree or on matching files.

## Quick Start

```bash
make start                      # Containers + migrations + vendored deps
make go-build                   # Build every Go app; APP=ApiCanary builds one
make go-test                    # Run all Go tests across all modules
make go-lint                    # Lint with golangci-lint
make go-run APP=MotorsportTracker ARGS="scrape:series"  # Build and run one app
make go-run APP=ApiCanary       # Check the live API against the connector schemas
make go-run APP=CacheWarmer ARGS='--series "FIA Formula One World Championship" --from 1950 --to 1950'  # Fill etc/ConnectorCache/
```

Go commands run inside the `golang` container. An app is any `main` package in `go.work`, named by its directory for `APP`, so a new app needs no Makefile entry. The CLI subcommand is the **full intent name** — `scrape:series`, not `series`.

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
    CacheWarmer/          # Fills etc/ConnectorCache/ for study, without scraping
  MotorsportTracker/
    Frontend/             # Next.js + Material-UI frontend
src/Golang/
  motorsporttracker/      # Core scraping modules (series, seasons, calendar, classification)
    registration/         # Centralized handler + intent registration
  motorsportstats/        # External API gateway (connector + gateway layers)
  shared/                 # Shared utilities (cache, database, messaging, crypto, env, fn)
etc/
  Migrations/             # SQL migrations — lowercase core/ and client-cache/ only
  ConnectorCache/         # Filesystem cache for API responses (gitignored)
docs/                     # PlantUML diagrams and documentation
go.work                   # Go workspace (7 modules)
```

## Scoped Instructions

| File | Loads when working in |
|---|---|
| [src/Golang/CLAUDE.md](src/Golang/CLAUDE.md) | Go core library — layers, naming, registration, persistence |
| [etc/Migrations/CLAUDE.md](etc/Migrations/CLAUDE.md) | SQL migrations — live directories, history tables, triggers |
| [apps/MotorsportTracker/Frontend/CLAUDE.md](apps/MotorsportTracker/Frontend/CLAUDE.md) | Next.js frontend |
| [.claude/rules/go-style.md](.claude/rules/go-style.md) | Any Go file under `src/Golang/` or `apps/Backend/` — booleans, errors, naming, idioms |
| [.claude/rules/go-tests.md](.claude/rules/go-tests.md) | Any `_test.go` file there — suite lifecycle, parallel isolation, `APP_ENV` |

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

## Commits

Subject: `<Type>: <Sentence-case summary>` — e.g. `Hotfix: Send bootstrap traces and logs to stderr, not stdout`.

`Feature`, `Hotfix` and `Refactor` commits get a body after a blank line: what changed and why — the bug's cause for a `Hotfix`, what moved and why for a `Refactor`. Wrap at 72 columns. The other types can stay subject-only when the subject says it all. Older history is mostly subject-only; don't copy that.

| Type | For |
|---|---|
| `Feature` | New behavior |
| `Hotfix` | Any bug fix, urgent or not |
| `Refactor` | Restructuring with no behavior change, dead-code removal |
| `Tests` | Test-only changes |
| `Documentation` | `docs/` and other prose |
| `Dependencies` | Go modules, `vendor/`, `go.work` |
| `Project` | Tooling, Makefile, Docker services, IDE and Claude config |

An `ask` rule on `Bash(git commit:*)` in `.claude/settings.json` prompts me on every commit, and that prompt is my approval. Don't stop to ask first. Before the call, say what's staged, then run a plain `git commit` from the repo root: not `git -C`, not inside `bash -c`, not chained after another command. That form is the one the rule is sure to match.

No other types — not `Doc`, `Refactoring`, `Cleanup`. Co-author trailers are disabled through `attribution` in `.claude/settings.json`.

## Communication Style

- **Be direct and challenge me** — Push back when you think I'm wrong. No flattery, no sugarcoating, no sycophancy.
