---
description: Scaffold a new scraping operation end to end — intent, use case, handler, repository, migration, and registration.
argument-hint: <module-name> (e.g. drivers, standings)
---

Scaffold a new scraping operation for module: **$1**

If `$1` is empty, ask which module to create before doing anything else.

Work through all seven steps. Do not stop halfway — a half-registered module fails at runtime with `unknown subcommand`, not at compile time.

## Layer placement (verified against the codebase)

| File | Package | Path |
|---|---|---|
| Intent | `infrastructure` | `src/Golang/motorsporttracker/scrapping/$1/infrastructure/intent.go` |
| Use case | `domain` | `src/Golang/motorsporttracker/scrapping/$1/domain/use_case.go` |
| Handler | `infrastructure` | `src/Golang/motorsporttracker/scrapping/$1/infrastructure/handler.go` |
| Save repository | `infrastructure` | `src/Golang/motorsporttracker/scrapping/$1/infrastructure/save_$1_repository.go` |
| Migration | — | `etc/Migrations/core/<YYYYMMDDHHMMSS>_create_<table>.up.sql` |

**Intents are infrastructure, not domain.** They import `shared/application/infrastructure` and construct a `messaging.Message`; both are infrastructure concerns. Commit `70c5ef1f` moved them. If you find a doc saying `domain/intent.go`, the doc is stale.

If the module needs more than one operation, drop the generic `intent.go` / `handler.go` / `use_case.go` names and prefix each file instead — follow `scrapping/seasons/`, which has `seasons_for_series_id_intent.go`, `seasons_for_series_keyword_handler.go`, and so on.

## 1. Intent

```go
package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const Scrape$1IntentName = "scrape:$1"

// Scrap$1Intent is an Intent to scrape $1.
type Scrap$1Intent struct {
	application.BaseIntent
}

// NewScrap$1Intent creates a new Scrap$1Intent.
func NewScrap$1Intent() *Scrap$1Intent {
	return &Scrap$1Intent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        Scrape$1IntentName,
				Description: "Scrape all available $1",
				Arguments:   []application.Argument{},
				Options:     []application.Option{},
			},
		},
	}
}
```

Every `Argument` is required — `BaseIntent.validate()` rejects the call when fewer are supplied than configured. Use an `Option` for anything genuinely optional. Argument names become message metadata keys, so they must match what the handler reads.

## 2. Use case (domain)

Define the repository interfaces this use case needs **in the domain package**, then depend on those interfaces only. Never import anything from `infrastructure`, `shared/database`, `shared/messaging`, `shared/client`, or `pgx` here — a `PostToolUse` hook blocks the edit if you do.

```go
type Save$1Repository interface {
	Save$1(ctx context.Context, items []*motorsportstats.$1) error
}

type Scrape$1UseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	save$1Repository       Save$1Repository
}
```

`Execute` fetches via the gateway, guards the empty result with a `slog.Warn` and an early `return nil`, then saves. Wrap every error with `%w`.

## 3. Handler

```go
func (h *Scrape$1Handler) Handle(ctx context.Context, message messaging.Message) error {
	// only if the intent declares arguments:
	// value, err := messaging.RequireString(message, "<argument-name>")
	// if err != nil {
	//     return fmt.Errorf("getting params from message: %w", err)
	// }

	return h.useCase.Execute(ctx)
}
```

Message metadata is `map[string]string`. Use `messaging.RequireString` / `messaging.RequireInt` — they check presence and emptiness and return descriptive errors. Do not reach into `message.Metadata` directly.

## 4. Save repository

Use `shared.Save()`. Build a hash from every significant field so change detection works; a hash over only the UUID means rows never update.

```go
hash := crypto.Hash(fmt.Sprintf("%s|%s", item.UUID, fn.Deref(item.Name, "")))
```

Return early with a `slog.Debug` when the slice is empty, and log `stats.Inserted` / `stats.Updated` on success.

## 5. Migration

Write to `etc/Migrations/core/` — **lowercase**. The capitalised `Core/` directory is dead PHP-era code and is never executed. Use the table + `_history` table + trigger template in `etc/Migrations/CLAUDE.md`.

`.up.sql` only — this project's migrations are forward-only, with no `.down.sql` anywhere.

## 6. Register

In `src/Golang/motorsporttracker/registration/registration.go`, both of these:

- add an entry to the `registeredIntents` map, keyed by `Scrape$1IntentName`
- add a `register$1Handlers(...)` func that wires the handler through the use case using `registry.GetMotorsportStatsGateway(ctx)` and `registry.GetCoreDatabase(ctx)`, then call it from `RegisterAllHandlers`

Missing the map entry compiles fine and fails at runtime. Missing the handler registration also compiles fine and fails when the message is consumed.

## 7. Migrate and verify

```bash
make run-dbmigrate-core && make run-dbmigrate-core.test
make go-build
./scripts/test-runner.sh scrapping
./scripts/architecture-check.sh
```

Then confirm the intent actually resolves — the subcommand is the **full intent name**:

```bash
make go-run ARGS="scrape:$1"
```

`ARGS="$1"` alone returns `unknown subcommand`.

## Tests

Colocate them. `use_case_test.go` beside the use case, `save_$1_repository_test.go` beside the repository. Use `testify/suite`, suffix the suite `UnitTestSuite` or `IntegrationTestSuite`, reset state in `SetupSubTest()` rather than `TearDownTest()`, and assert on counts and effects — not merely that `err` was nil.
