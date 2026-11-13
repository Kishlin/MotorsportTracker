# Roadmap

What gets built, in order. The reasoning behind each item lives in [DECISIONS.md](DECISIONS.md): this file only says what, in which order, and where it stands. Remove an item once it is built; the git history keeps the record.

## Now

### 1. Save groundwork

Not started. See [Embedded entities: one current state, or one look per entry](DECISIONS.md#embedded-entities-one-current-state-or-one-look-per-entry).

- `shared.Save()` sorts its rows by the conflict key before batching.
- A hash that tells null from `""`: a JSON array of the fields in a fixed column order.
- Driver first and last names go: a forward migration on `drivers`, `drivers_history` and its trigger function, and the classification save stops writing them. This comes before standings, which write `drivers` without them.

### 2. Standings scraping

Tables built. Repositories, use cases, handlers, intents and registration not started.

- Save the entities (`drivers`, `countries`, `constructors`, `classes`, `team_looks`), then `standings`, then the driver, team and constructor rows. Test that an overall standing saved twice gives one row, and that `payload_index` order survives a re-save.
- Two levels, each publishing to the next one down: a `StandingsScrapper` scrapes a season's available standings and publishes one message per standing, and a `StandingScrapper` scrapes one standing. Fan-outs follow [Fan-outs log failed publishes and move on](DECISIONS.md#fan-outs-log-failed-publishes-and-move-on).
- Five entry points, which need proper intent names:

  | Arguments | Scrapes the standings of |
  |---|---|
  | season UUID | that season |
  | series UUID | every season of that series |
  | series keyword, year | the season resolved from them |
  | series keyword | every season of the series resolved from it |
  | none | every season of every series |

- `/new-scraping-op` is outdated for a multi-level module. Follow `scrapping/seasons/`, which has the same kinds of targets.
- On 2026-10-13, a fix was agreed for seasons too, in a separate commit. What that fix was got lost with the session transcript. Ask before starting.

### 3. ApiCanary covers the standings

Not started. ApiCanary checks no standings endpoint yet: available standings, then driver, team and constructor standings.

## Next

### 4. Classifications and calendars move teams and venues to looks

Not started. See [Classifications and calendars, later](DECISIONS.md#classifications-and-calendars-later). `teams` and `garages` become `team_looks`, `venues` becomes `venue_looks`. Drivers and countries stay. Check whether the core database is still disposable.

### 5. Versioned database cache

Not started. See [Upstream responses are versioned](DECISIONS.md#upstream-responses-are-versioned). Comes before the rescrape option, which is what creates new versions.

### 6. Rescrape option

Not started. See [Rescrapes keep stored data in step with upstream](DECISIONS.md#rescrapes-keep-stored-data-in-step-with-upstream). Resolves review issue 16.

### 7. Scrape every series of interest

Then study what the series not measured yet send, against the assumptions in [DECISIONS.md](DECISIONS.md).

### 8. Race histories

Not designed. Study the payloads first. The rows are leaves: they follow the wipe-and-reinsert rule per session and endpoint.

## Later

- **Shared throttling in the connector.** A decorator, with throttling shared across apps rather than per process. Deferred on 2026-10-09.

## Housekeeping

- `TearDownTest()` is still used in `save_seasons_repository_test.go`, `save_series_repository_test.go`, `worker_test.go` and `cache_using_database_test.go`. `.claude/rules/go-tests.md` says to reset state in `SetupSubTest()`.
- Review issue 9 (pgx pool defaults) is parked on purpose.
