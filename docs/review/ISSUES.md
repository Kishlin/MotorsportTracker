# Go Codebase Issues — February 2026

Actionable issues identified during architecture review. Each includes the affected files, the problem, and a remediation approach. Ordered by impact.

Status as of 2026-09-26: items 1–8 and 10–14 are resolved. Item 9 remains open, but its premise was stale and has been corrected below. Item 15 is open and needs a decision before it can be fixed.

Re-verified against the code on 2026-09-25: items 9, 14 and 15 were open, and no resolved item had regressed. Line references below were refreshed where they had drifted. Item 14 was fixed the next day.

---

## 1. ~~Handler/Intent Registration Duplicated Across Apps~~ (Resolved)

**Resolution**: Centralized in `src/Golang/motorsporttracker/registration/registration.go`. `RegisterAllHandlers()` delegates to private per-module helpers. `GetIntent()` maps subcommand names to intents. All three apps now call these shared functions. CLI arg parsing was also deduplicated into `shared/application/infrastructure/parse_args.go`.

---

## 2. ~~Weak Metadata Typing in Handlers~~ (Resolved)

**Resolution**: Added `RequireString(msg, key)` and `RequireInt(msg, key)` helpers in `src/Golang/shared/messaging/infrastructure/metadata.go`. All four handlers (calendar, classification, seasons keyword, seasons ID) now use these helpers instead of manual metadata extraction. Tests in `metadata_test.go`.

---

## 3. ~~Inline JSON Schemas in Connector (580 lines)~~ (Resolved)

**Resolution**: The four schemas moved to `src/Golang/motorsportstats/connector/infrastructure/schemas/{series,seasons,calendar,classification}.json` and are loaded with `go:embed`. `connector_using_client.go` dropped from 579 to 105 lines, and the endpoint constants were grouped into a single `const` block.

Extraction was verified byte-identical against the original string literals, and the existing functional suite exercises all four schemas through `validate()`.

---

## 4. ~~`fmt.Println` in Environment Loading~~ (Resolved)

**Resolution**: The three calls in `src/Golang/shared/env/infrastructure/env.go` now go through a small `bootstrapDebug` helper: silent unless `LOG_LEVEL=debug`, and written to **stderr** rather than stdout.

**`slog` is deliberately not used here.** Every app calls `env.LoadEnv()` *before* `logger.SetupSlog()` (see `apps/Backend/CommandsProcessor/main.go:20-26`). Until `SetupSlog` runs, `slog.Default()` is Go's built-in handler: stderr, **Info** level. So `slog.Debug` in this file is swallowed and never reaches any output — verified as zero occurrences even with `LOG_LEVEL=DEBUG`. `bootstrapDebug` therefore reads `LOG_LEVEL` straight from the process environment, which is also why it cannot come from the `.env` files — they are not loaded yet at that point.

Moving off stdout matters independently: these are diagnostics, and the CLI's real output goes to stdout.

Verified both ways — `LOG_LEVEL` unset produces no output; `LOG_LEVEL=debug` produces the trace on stderr only, with stdout clean.

---

## 5. ~~Package Name Typo: `doman` Instead of `domain`~~ (Resolved)

**Resolution**: `package doman` → `package domain` in **both** files of the package, `crypto.go` and `crypto_test.go`. The original note was right to flag that the whole package had to move together. All importers already aliased the package as `crypto`, so no call sites changed.

---

## 6. ~~Casing Inconsistency: `clientCacheDBonce`~~ (Resolved)

**Resolution**: Renamed to `clientCacheDBOnce` in `services_registry.go` — declaration and use site.

---

## 7. ~~No Backoff in Queue Worker~~ (Resolved)

**Resolution**: `runWorker` now tracks `consecutiveErrors` and waits `backoffFor(n)` — the poll interval doubled per additional failure, capped at `maxBackoff` (60s) — resetting to zero after any successful receive.

Two hazards were handled beyond the original proposal:

- **The waits are now interruptible.** `time.Sleep` ignored `stopChan`, so a 60s backoff would have made `Stop()` block for up to a minute. The new `wait()` selects over `stopChan`, `ctx.Done()` and the timer.
- **A non-positive `pollInterval`** returns `maxBackoff` rather than doubling zero forever.

Covered by `TestUnit_WorkerBackoff` (6 cases). The backoff and wait helpers are tested directly rather than introducing a queue interface purely for test injection.

---

## 8. ~~No Integration Tests for `shared.Save()`~~ (Resolved)

**Resolution**: `save_repository_helpers_integration_test.go` exercises `Save()` against `core-test` using a throwaway `save_helpers_probe` table created and dropped by the suite, so assertions never depend on migrated schema.

Eight cases: empty input, insert, unchanged-hash skip, hash-change update, mixed insert/update accounting, batching past `maxParamsPerQuery` (700 rows × 3 columns = 2100 params), updates across batch boundaries, and inconsistent row-width rejection.

Note the semantics this pinned down: a row whose hash is unchanged is counted as **neither** inserted nor updated, because `WHERE hash IS DISTINCT FROM EXCLUDED.hash` excludes it from `RETURNING` entirely.

The suite was mutation-tested — neutralising the hash guard fails exactly the two cases that encode that behaviour.

---

## 9. Database Pool Uses Defaults

**Impact**: Suboptimal connection management at scale. No immediate issue at current volume.

**File**: `src/Golang/shared/database/infrastructure/database_using_pgxpool.go`

**Problem**: ~~`pgxpool.New(ctx, connStr)` is called with no configuration~~ — **outdated**. The code already calls `pgxpool.ParseConfig` and then `pgxpool.NewWithConfig`. The remaining gap is narrower: the parsed config is passed straight through without setting `MaxConns`, `MaxConnLifetime` or `HealthCheckPeriod`, so pgx defaults still apply.

**Remediation**: If scaling becomes relevant, set those three fields on the already-parsed `pgxpool.Config` before `NewWithConfig`. That is now a three-line change, not a restructure.

Low priority — current single-digit concurrency works fine with defaults. Deliberately left open.

---

## 10. ~~SQL Interpolation in Database Cache~~ (Resolved)

**Resolution**: `assertValidNamespace` rejects anything not matching `^[a-z_]+$` before interpolation, guarding **both** call sites — `Get` (line 37) and `Set` (line 67). The original write-up mentioned only `Get`.

Covered by `TestUnit_CacheUsingDatabaseNamespace`, which asserts rejection of injection, hyphen, uppercase, digit, empty, schema-qualified and trailing-space namespaces, and acceptance of the four live table names. The suite passes a `nil` pool deliberately, proving validation short-circuits before any database access.

---

## 11. ~~Parallel Integration Suites Share One Test Database~~ (Resolved)

**Resolution**: The cause was fixture **strings** colliding, not parallelism as such. The search repositories match with `LIKE '%kw%'`, and suites seeded identical or generic searchable names:

- `search_series_identifier_repository_test.go` and `search_all_series_identifiers_repository_test.go` (same package) both seeded `'Test Series Match 1'`, `'ShortTest2'` and `'SerTest3'`. `LIMIT 1` returned the other suite's UUID: `expected "82b7cd85-…-000000000001", actual "90772f9f-…-000000000001"`.
- `search_session_identifier_repository_test.go` searched `%series%` / `%event%` / `%session%`. That matched `save_calendar_repository_test.go`'s `'Calendar Series'` → 2024 season → `'Event 1'` → `'Session 1'` chain in another package, so its "year is wrong" (2024) case came back found.

Neither original remedy would have worked. Dropping `t.Parallel()` only serialises tests *within* a package; `go test ./...` still runs packages as parallel processes, which is how the session collision happened. Per-suite schemas would have had to work around `run-dbmigrate-core.test` migrating a single schema.

The fix: each keyword-searched suite holds its UUID prefix in a `const` and appends it to every searchable string and keyword (e.g. `'Test Series Match 1 82b7cd85-ee6f-4c2c-a289'`). The prefixes are random hex, so they're unique by construction and nothing else contains one. `t.Parallel()` stays. The rule for new suites is in `src/Golang/CLAUDE.md` under Tests.

Prefixes must also be unique across **all** integration suites, not just the searching ones: teardown deletes `LIKE '<prefix>-%'`, so two suites on one prefix would delete each other's fixtures mid-run. `save_calendar_repository_test.go` used three prefixes and now uses one. `scripts/fixture-prefix-check.sh` runs before `make go-test` and `scripts/test-runner.sh` and fails if any suite uses more than one prefix or two suites share one.

Verified: the four series-touching packages failed under `-count=6` before the change. After it, all of `scrapping/...` passed three consecutive runs at `-count=6`.

---

## 12. ~~Inverted `exists` Check Means Nationalities Are Never Saved~~ (Resolved)

**Resolution**: `exists` → `exists == false` in `save_classification_repository.go:65`, the same form as the drivers dedup twelve lines above. Nationalities from classification payloads now reach `shared.SaveCountries` and are written to `countries`.

The cause was the inverted branch: `nationalitiesUUIDs` starts empty and was written only inside that branch, so the body never ran and `uniqueNationalities` was always empty. Nothing errored and nothing referenced the missing rows. Nationalities are only written to `countries`; nothing resolves them back to an ID, so no foreign key was left dangling.

The three data-bearing cases in `save_classification_repository_test.go` now assert the `countries` count. The complex fixture repeats one nationality across three entries and asserts 2 rows, which covers the dedup as well. All three assertions failed with `actual: 0` before the fix.

The fix is not retroactive. Classifications scraped before it never wrote their nationalities, and they get them only when they are scraped again.

---

## 13. ~~A Driver Can Only Be Linked to One Car Per Session~~ (Resolved)

**Resolution**: the per-car append in `save_classification_repository.go:56-58` now sits outside the driver dedup. `driversUUIDs` still deduplicates the driver rows across the session. `driverUUIDsPerCarNumbers` now records every car a driver is listed on.

The cause was that both were gated by the same check, so the first car to list a driver kept the link and later cars silently lost it. This does happen in real racing. In 1950s F1, drivers took over a teammate's car mid-race, and both entries were classified: at Monza in 1956, Fangio retired his own car and finished second in Collins'. None of the five cached classifications shows the case, but they are all modern sessions, so it is unverified whether motorsportstats lists such a driver on both entries.

`complexClassification` now lists one driver on two cars and asserts 11 `entry_drivers` rows. Before the fix it saved 10.

The original remediation said `UNIQUE(entry, driver)` would absorb a repeat within one car. It does not. `saveEntryDrivers` sends every row in one statement, and Postgres rejects an `ON CONFLICT DO UPDATE` that hits the same key twice in one command (`cannot affect row a second time`, SQLSTATE 21000). A driver listed twice on one car therefore fails the save. That is a malformed payload, so failing loudly is correct.

---

## 14. ~~Event Hash Is Built From Venue Fields, So Event Renames Never Persist~~ (Resolved)

**Resolution**: the events loop in `save_calendar_repository.go:179-186` now hashes `event.Name`, `event.ShortName` and `event.ShortCode`, and adds `seasonID`, so the hash covers every column the row stores.

The cause was copy-paste from the venue loop, where `fn.Deref(venue.Name, ...)` is correct. The hash was built from the **venue's** name fields while the row stored the **event's**. `shared.Save()` emits `WHERE hash IS DISTINCT FROM EXCLUDED.hash`, so a renamed event produced an identical hash and the `UPDATE` was skipped. Only a venue change, a status change or a reschedule could refresh an event's stored name. `season` was stored but not hashed either, so an event moved to another season was dropped the same way. The venues, sessions, seasons, series and countries hashes already covered every stored column.

`save_calendar_repository_test.go` gains two cases. One saves an event twice with only its names changed, and the other saves it twice with only its season changed. Each asserts the stored row carries the second values. Before the fix, both failed with the first values still stored.

**No backfill is needed.** The original remediation proposed `UPDATE events SET hash = ''` to force a rewrite. That fails on the second row, because `events.hash` is `UNIQUE NOT NULL`. It is also unnecessary: the hash formula changed, so every stored hash is stale, and the next calendar scrape of a season rewrites every event in it. The one exception is an event whose current season and names hash the same as the old venue names, which would need all three name fields to be nil both times. That first rescrape adds one `events_history` row per event, once. Events in seasons that are never scraped again keep their old names.

The secondary note about `event.Venue` is gone with the fix. The loop dereferenced `event.Venue` unguarded right after an `if event.Venue != nil` guard. `calendar.json` requires a non-null venue, so the panic was unreachable through the validated path, but cached payloads are never re-validated. The loop now reads `event.Venue` only inside the guard.

---

## 15. Classification Rows Logged as Skipped Still Abort the Save

Found 2026-09-25 while fixing item 13. Open, and it needs a decision before it can be fixed.

**Impact**: a classification with a duplicate car number, an entry without a team, or a retirement for an unknown car logs a warning saying the row is skipped, and then fails anyway. The whole session's save returns an error. `SaveClassification` has no transaction, so every step before the failing one is committed and nothing after it is written.

**File**: `src/Golang/motorsporttracker/scrapping/classification/infrastructure/save_classification_repository.go`

**Problem**: three checks log a warning and `continue`, but they only filter some of the data built in the loop. The later steps still receive the rows they skipped.

| Check | Lines | Still receives the row | Fails with |
|---|---|---|---|
| Duplicate car number | 68-75 | The driver loop at 52-59 runs before the check, so the duplicate's drivers are appended to that car again. `saveClassificationDetails` (181) also gets the unfiltered `classification.Details` | `saving entry drivers: … ON CONFLICT DO UPDATE command cannot affect row a second time (SQLSTATE 21000)` |
| Missing team | 76-83 | Same driver loop: the drivers are queued under a car number that never gets an entry | `saving entry drivers: entry ID for car number 302 not found` |
| Retirement for unknown car | 108-115 | `saveRetirements` (176) gets the unfiltered `classification.Retirements` | `saving retirements: entry ID for car number 999 not found` |

All three were reproduced with integration cases in `save_classification_repository_test.go` and fail exactly as shown. In the first two, `entries` had already been written when the save failed. Before item 13 was fixed, the duplicate case failed one step later, in `saveClassificationDetails`, with the same SQLSTATE. It has never actually skipped.

**Reachability**: `classification.json` requires `team` and types it as a non-null object, so a missing team cannot pass the validated path. It stays latent in the same way item 14's venue deref was, because cached payloads are never re-validated. Nothing in the schema prevents a duplicate car number, and it cannot check a retirement's car number against the details. None of the five cached classifications contains any of the three cases.

**Remediation**: decide which of these two behaviors is intended, then make the code match.

- **Skip for real.** Move the driver loop below the two checks, and pass filtered slices of details and retirements to `saveClassificationDetails` and `saveRetirements`. The warnings then describe what actually happens, and one bad row no longer blocks the session.
- **Fail loudly, before writing anything.** Replace the three warnings with returned errors and check every row before the first write. The error then names the bad row, and there is no partial write, but one bad row blocks the whole session.

The checks don't all need the same answer. A missing team can only mean a stale cache or schema drift, which argues for failing. A duplicate car number or an orphan retirement could be a quirk of the upstream data, which argues for skipping. Whichever way each one goes, the three integration cases above become the regression tests, with their assertions flipped to `Error` for any check that fails loudly.
