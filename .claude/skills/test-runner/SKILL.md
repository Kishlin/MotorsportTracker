---
name: test-runner
description: Run the Go test suites inside the Docker golang container, scoped to all modules, a single area (scrapping, gateway, shared), or an explicit package path, with optional verbose output, a -run pattern, and test-cache clearing. Use whenever Go tests need running or a specific suite needs reproducing — prefer this over hand-writing docker compose exec invocations.
---

# Test Runner

Tests run **inside the `golang` container** — never on the host. This script wraps that.

```bash
./scripts/test-runner.sh <scope> <verbose> <run-pattern> <pristine>
```

Arguments are **positional and all-or-nothing**: to reach a later argument you must supply every earlier one. `./scripts/test-runner.sh all false "" true` is the only way to get a pristine full run.

| Position | Values | Default |
|---|---|---|
| 1 `scope` | `all`, `scrapping`, `gateway`, `shared`, or a package path | `all` |
| 2 `verbose` | `true` / `false` → adds `-v` | `false` |
| 3 `run-pattern` | regex → `-run <pattern>` | `""` |
| 4 `pristine` | `true` / `false` → `go clean -testcache` first | `false` |

## Scope targets

- `all` → `src/Golang/...` plus the ApiCanary, DBMigrate, CommandsProcessor and CommandsPublisher apps
- `scrapping` → `src/Golang/motorsporttracker/scrapping/...`
- `gateway` → `src/Golang/motorsportstats/...`
- `shared` → `src/Golang/shared/...`
- anything else is passed through verbatim as a package path

## Examples

```bash
./scripts/test-runner.sh                                    # everything, cached
./scripts/test-runner.sh scrapping                          # scrapping modules only
./scripts/test-runner.sh all true                           # everything, verbose
./scripts/test-runner.sh shared false TestUnit_Crypto       # one suite
./scripts/test-runner.sh ./src/Golang/motorsportstats/...   # explicit path
./scripts/test-runner.sh all false "" true                  # clear cache, full run
```

## Preconditions

The `golang` container must be up (`make containers`, or `make start` for a cold repo). Integration suites additionally need the **test** databases migrated:

```bash
make run-dbmigrate-core.test && make run-dbmigrate-client-cache.test
```

A suite whose name ends in `IntegrationTestSuite` or `FunctionalTestSuite` hits real Postgres. If those fail with connection or missing-relation errors, the migrations above are the first thing to check — not the test code.

## Alternative

`make go-test` runs the same thing unscoped. Use this script when you need scoping, a `-run` filter, or a cache clear.
