---
paths:
  - "src/Golang/**/*_test.go"
  - "apps/Backend/**/*_test.go"
---

# Go Tests

Colocated with the code they test. `testify/suite`, with the suite suffixed `UnitTestSuite` (no external dependencies), `IntegrationTestSuite` (real Postgres), or `FunctionalTestSuite` (end-to-end flows). Assert counts and effects, not just a nil error, and cover the error paths as well as the success path.

## Lifecycle

- Reset state in `SetupSubTest()`, not `TearDownTest()`.
- Use `s.Run()`, never `s.T().Run()`. `s.Run()` points `s.T()` at the subtest, so a failed assertion is reported on the right case, and it is what makes `SetupSubTest()` fire at all.
- Hook names are exact: testify silently ignores a misspelled `TeardownSuite()`.
- `fn.Must(err)` and `fn.MustReturn(value, err)` for setup that should never fail.

## Integration isolation

Integration suites share the `core-test` and client-cache test databases and run concurrently: every integration and functional suite calls `t.Parallel()`, and separate packages run as parallel processes under `go test ./...`. Two rules keep them apart:

- **One UUID prefix per suite, unique across suites.** The suite declares its prefix, the first three UUID groups (`xxxxxxxx-xxxx-xxxx`), once in a `const`, and builds every UUID it writes from it: `prefix + "-0001-000000000001"` in Go, `%[1]s` in `fmt.Sprintf` fixtures. Teardown deletes `LIKE '<prefix>-%'`. Two suites sharing a prefix delete each other's fixtures mid-run. When a suite seeds several entities, give each its own group after the prefix (`-0001-`, `-0002-`, …). `scripts/fixture-prefix-check.sh` enforces all of this (one prefix, declared once in a `const`, unique across suites) and runs before `make go-test` and `scripts/test-runner.sh`; generate a new prefix for a new suite, never copy one. The same goes for every other `UNIQUE` column: each core table has a `UNIQUE` `hash`, and `ON CONFLICT (uuid)` does not absorb a hash clash, so two suites seeding `'venues-hash'` fail with SQLSTATE 23505 as soon as they overlap. In fixtures, use the row's own UUID as its hash.
- **Searched strings carry the prefix.** Any string a repository **searches** on (name, short name, short code, and the keywords the test passes in) ends with the suite's prefix `const`. The search queries use `LIKE '%kw%'`, so a generic name like `'series'` or a name shared between suites matches another suite's rows mid-run. See `scrapping/classification/infrastructure/search_session_identifier_repository_test.go`.

## Environment

Suites call `env.OverrideAppEnv("tests")` before `env.LoadEnv()` in `SetupSuite()` and deliberately discard the reset function. Restoring `APP_ENV` in one suite's teardown would flip it back (to `dev` in the container) while a parallel suite is between setting it and loading its env files. The variable only lives as long as the test binary, so leaving it set changes nothing outside the tests. The one exception is `shared/env`'s own tests, which exercise the reset.
