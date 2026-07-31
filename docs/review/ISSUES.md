# Go Codebase Issues — February 2026

Actionable issues identified during architecture review. Each includes the affected files, the problem, and a remediation approach. Ordered by impact.

Status as of 2026-08-01: items 1–8 and 10 are resolved. Item 9 remains open, but its premise was stale and has been corrected below. Items 11–14 are open; 12–14 were found on 2026-08-01 while tracing the scraping chain to build the API canary, and all three are silent — nothing fails, the data is just wrong.

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

**Resolution**: `assertValidNamespace` rejects anything not matching `^[a-z_]+$` before interpolation, guarding **both** call sites — `Get` (line 26) and `Set` (line 49). The original write-up mentioned only `Get`.

Covered by `TestUnit_CacheUsingDatabaseNamespace`, which asserts rejection of injection, hyphen, uppercase, digit, empty, schema-qualified and trailing-space namespaces, and acceptance of the four live table names. The suite passes a `nil` pool deliberately, proving validation short-circuits before any database access.

---

## 11. Parallel Integration Suites Share One Test Database

Found 2026-07-30 while verifying unrelated work. Open.

**Impact**: Intermittent false failures locally and in CI. No production impact.

**Files**: 8 suites call `t.Parallel()` across `src/Golang/motorsporttracker/scrapping/`; 10 test files connect to the same `core-test` via `POSTGRES_CORE_URL`.

**Problem**: Each suite seeds fixtures into shared tables (`series`, `seasons`, `events`, `sessions`) and cleans up only its own uuid-prefixed rows. But the repositories under test issue **global** queries — that is their job. So a "not found" case can observe a row another suite inserted concurrently, and fails with `expected: false, actual: true`.

Observed on two different suites:

- `TestIntegration_SearchSeriesIdentifierRepository/TestGetSeriesIdentifier`
- `TestIntegration_SearchSessionIdentifierRepository` (`search_session_identifier_repository_test.go:110`)

Both pass in isolation, and under `-count=5` within their own package. Reproduced by running the four series-touching packages together under `-count=6`. Pre-existing — it reproduces with `scrapping/shared/...` excluded from the run entirely.

**Remediation**, simplest first:

1. Drop `t.Parallel()` from the integration suites that share tables. Serialises them; each runs in ~0.1s, so the cost is negligible.
2. Or give each suite its own PostgreSQL schema and set `search_path` per connection, keeping parallelism.

Option 1 matches the project's "simplest approach first" principle.

---

## 12. Inverted `exists` Check Means Nationalities Are Never Saved

Found 2026-08-01. Open.

**Impact**: `countries` is never populated from classification payloads, so driver nationalities are silently dropped. No error, no warning.

Scope is narrower than it first looks: `uniqueNationalities` is passed to `SaveCountries` (line 129) and nowhere else — nothing in this repository resolves a nationality back to an ID, so no foreign key is left dangling and no row references a missing country. The loss is that `countries` only ever receives the **event** countries written by the calendar path (`save_calendar_repository.go:62`). A driver whose nationality never hosts an event is absent from the table entirely.

**File**: `src/Golang/motorsporttracker/scrapping/classification/infrastructure/save_classification_repository.go:65-68`

**Problem**: the dedup branch is inverted.

```go
if _, exists := nationalitiesUUIDs[classificationDetails.Nationality.UUID]; exists {
	nationalitiesUUIDs[classificationDetails.Nationality.UUID] = struct{}{}
	uniqueNationalities = append(uniqueNationalities, classificationDetails.Nationality)
}
```

`nationalitiesUUIDs` starts empty (line 47) and is written **only inside this branch**, so `exists` is false on every iteration and the body never runs. `uniqueNationalities` is therefore always empty when it reaches `shared.SaveCountries` at line 129, which returns without doing anything.

The drivers block twelve lines above (line 53) is the correct form of the same pattern — `exists == false` — which is what makes this a plain typo rather than a design choice. `src/Golang/CLAUDE.md` mandates the explicit `== false` comparison precisely because a bare `exists` reads as plausible.

**Remediation**: `if _, exists := ...; exists == false {`. One character class. Worth adding a repository test asserting a non-zero `countries` count after saving a classification, since nothing currently covers it.

---

## 13. A Driver Can Only Be Linked to One Car Per Session

Found 2026-08-01. Open.

**Impact**: missing `entry_drivers` rows in multi-driver series. Affects endurance racing specifically — the case the schema was designed for.

**File**: `src/Golang/motorsporttracker/scrapping/classification/infrastructure/save_classification_repository.go:53-62`

**Problem**: the per-car driver list is built **inside** the global driver dedup.

```go
for _, driver := range classificationDetails.Drivers {
	if _, exists := driversUUIDs[driver.UUID]; exists == false {
		driversUUIDs[driver.UUID] = struct{}{}
		uniqueDrivers = append(uniqueDrivers, driver)
		// ... driverUUIDsPerCarNumbers[carNumber] = append(...)  ← also gated by the check above
	}
}
```

The two maps answer different questions. `driversUUIDs` deduplicates the driver rows to insert — correctly global to the session. `driverUUIDsPerCarNumbers` records which drivers sat in which car — that is per car, and must not be suppressed just because the driver was already seen on another entry.

So if car 7 lists drivers A, B, C and car 8 lists A, D, E, then car 8 gets only D and E: the A→car 8 link is silently dropped. First car to mention a driver wins. `saveEntryDrivers` (line 601) then writes an incomplete set.

**Remediation**: lift the `driverUUIDsPerCarNumbers` append out of the `if`, leaving only `driversUUIDs`/`uniqueDrivers` inside it. `entry_drivers` has `UNIQUE(entry, driver)` so a repeat within one car is absorbed by the upsert. `save_classification_repository_test.go:331` (`complexClassification`) already builds a multi-driver fixture and asserts 10 `entry_drivers` rows — extend it with a driver shared across two entries.

---

## 14. Event Hash Is Built From Venue Fields, So Event Renames Never Persist

Found 2026-08-01. Open.

**Impact**: an event renamed upstream is never updated in `events`. Silent — the upsert reports the row as unchanged.

**File**: `src/Golang/motorsporttracker/scrapping/calendar/infrastructure/save_calendar_repository.go:179-187`

**Problem**: the hash is computed from the **venue's** name fields while the row stores the **event's**.

```go
nameVal := fn.Deref(event.Venue.Name, "")
shortNameVal := fn.Deref(event.Venue.ShortName, "")
shortCodeVal := fn.Deref(event.Venue.ShortCode, "")

hash := crypto.Hash(fmt.Sprintf("...", event.UUID, venueIDVal, countryIDVal, nameVal, shortNameVal, shortCodeVal, ...))
rows = append(rows, []interface{}{event.UUID, seasonID, venueID, countryID, event.Name, event.ShortName, event.ShortCode, ...})
```

`shared.Save()` emits `WHERE hash IS DISTINCT FROM EXCLUDED.hash`, so a renamed event produces an identical hash and the `UPDATE` is skipped. Only a venue change, a status change or a reschedule can ever refresh an event's stored name.

This is copy-paste from the venue loop at lines 116-118, where `fn.Deref(venue.Name, ...)` is correct. The sessions loop at lines 220-222 gets it right too, leaving the events loop as the only one reading the wrong struct.

**Remediation**: `event.Name`, `event.ShortName`, `event.ShortCode`.

The fix is retroactive-unsafe on its own: existing rows carry venue-derived hashes, so renames already missed stay missed until the stored hash changes for some other reason. A one-off `UPDATE events SET hash = ''` before the next scrape forces every row to be rewritten.

### Secondary: the file contradicts itself on whether `event.Venue` can be nil

Line 162 guards `if event.Venue != nil` before the ID lookup; lines 179-181 then dereference it unguarded. One of the two is wrong.

Today the guard is the redundant one, not the deref: `calendar.json` lists `venue` in the event `required` array **and** types it `"object"` (not `["object", "null"]`), so a payload that reaches `SaveCalendar` through `ConnectorUsingClient` has already been rejected if a venue were missing or null. The panic is therefore unreachable via the validated path — worth recording as latent rather than as a live crash.

It becomes reachable if the schema is relaxed, or through a `CachedConnector` hit serving bytes stored before the schema required a venue, since cached payloads are never re-validated (see the Caching section of [PATTERNS.md](../PATTERNS.md)). Applying the remediation above removes the venue access from this loop entirely and the question disappears with it.
