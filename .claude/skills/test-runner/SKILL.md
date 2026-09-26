---
name: test-runner
description: Run the Go test suites inside the Docker golang container, scoped to all modules, a single area (scrapping, gateway, shared), or an explicit package path, with optional verbose output, a -run pattern, and test-cache clearing. Use whenever Go tests need running or a specific suite needs reproducing — prefer this over hand-writing docker compose exec invocations.
---

# Test Runner

Tests run **inside the `golang` container** — never on the host.

```bash
./scripts/test-runner.sh [scope] [--verbose] [--run <pattern>] [--pristine]
```

Scope defaults to `all`:

- `all` → every module listed in `go.work`
- `scrapping` → `src/Golang/motorsporttracker/scrapping/...`
- `gateway` → `src/Golang/motorsportstats/...`
- `shared` → `src/Golang/shared/...`
- anything else is passed through verbatim as a package path

```bash
./scripts/test-runner.sh shared --run 'TestUnit_Crypto'   # one suite
./scripts/test-runner.sh gateway --verbose                # adds -v
./scripts/test-runner.sh --pristine                       # go clean -testcache, then everything
```

Targets run one after another and the script stops at the first failing one.

## Preconditions

The `golang` container must be up (`make containers`, or `make start` for a cold repo). Integration suites additionally need the **test** databases migrated:

```bash
make run-dbmigrate-core.test && make run-dbmigrate-client-cache.test
```

A suite whose name ends in `IntegrationTestSuite` or `FunctionalTestSuite` hits real Postgres. If those fail with connection or missing-relation errors, the migrations above are the first thing to check — not the test code.
