---
name: architecture-check
description: Verify hexagonal architecture compliance across the Go modules — checks that domain/ packages import no infrastructure packages, no framework packages (database, messaging, client), and no external infrastructure libraries (pgx, aws-sdk, golang-migrate). Use before finishing work that touched any domain/ package, when reviewing a branch, or when the user asks about architecture or layering violations.
---

# Architecture Check

Runs the three-part hexagonal boundary audit over every `domain/` package.

```bash
./scripts/architecture-check.sh
```

Runs on the host — no Docker needed. Exits non-zero on any violation.

## What it checks

| # | Rule | Scope |
|---|------|-------|
| 1 | `domain/` must not import `*/infrastructure` | scrapping, gateway, shared |
| 2 | `domain/` must not import `shared/{database,messaging,client,cache/infrastructure}` | scrapping, gateway |
| 3 | `domain/` must not import `pgx`, `aws-sdk`, or `golang-migrate` | scrapping, gateway, shared |

`_test.go` files are excluded from all three — test doubles may reach across layers.

## Reading a failure

Output names the offending `file:line` and its import. The fix is almost never to loosen the check:

- **Domain needs a concrete type from infrastructure** → define an interface in `domain/` and have infrastructure implement it. This is the Repository/Gateway pattern already used throughout.
- **Domain needs `pgx` types** → the repository belongs in `infrastructure/`; domain should speak in its own structs.
- **A shared util is genuinely layer-free** → it belongs under a `domain/` package itself (see `shared/fn/domain`, `shared/crypto/domain`).

## Relationship to the automatic hook

A `PostToolUse` hook already runs this script whenever a file under a `domain/` directory is edited, and blocks on failure. Invoke this skill for a **full sweep** — before a commit, after a refactor that moved files, or when auditing work you did not do yourself.
