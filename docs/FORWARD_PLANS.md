# Forward Plans

Decisions made before the work that needs them. Each plan records the problem, every option considered, and why the chosen one won, so the discussion does not have to happen twice.

File and line references were checked against the code on the date each plan gives. Check them again before acting on a plan.

---

## 1. Keeping Stored Data in Step With Upstream on Rescrape

Decided 2026-09-28. Not built: build it together with the rescrape option. Implementing it resolves review issue 16 in [review/ISSUES.md](review/ISSUES.md). Plan 2 replaces the horizontal tables this plan protects with look tables, which are never deleted.

### The problem

`shared.Save()` only inserts and updates (`INSERT ... ON CONFLICT DO UPDATE`), and no repository deletes anything. When motorsportstats drops something the scraper stored earlier, the stored row stays and reads exactly like a current one. The history triggers fire on `INSERT OR UPDATE` only, so they could not record a delete either.

### Why it cannot happen yet

Every scrape goes through `CachedConnector` over `DatabaseCache`, with a `FileSystemCache` on top when `USE_FS_CACHE=true` (`GetConnector` in `motorsportstats/dependencyinjection/infrastructure/services_registry.go`). Neither cache expires ([ARCHITECTURE.md](ARCHITECTURE.md), "Client Cache (Filesystem)"). A rescrape replays the bytes saved the first time, so the save never sees a payload missing something it stored.

Drift reaches the database once commands can bypass or clear the cache for what they scrape. That option (`--rescrape`, `--clean-cache` or similar) is not designed yet. This plan is for that work.

One path exists today. Changing how a save derives its keys, then re-saving the same cached bytes, orphans the rows stored under the old keys. Item 15 did this with `occurrence`, and handled it by dropping and re-migrating core.

### How the data is shaped

**Horizontal data** models the real world and is shared across the hierarchy: countries, drivers, venues. Teams and garages probably belong here too, depending on how motorsportstats handles teams. A scrape never deletes horizontal rows. A classification payload lists only its own session's drivers, so a driver it leaves out has not been dropped.

**Everything else is a hierarchy**, and data flows down it:

```
Series → Season → Event → Session → Classification        (today)
Series → Season → Event → Session → Entry → Race Lap      (planned)
Series → Season → Analytics                               (planned)
Series → Season → Standings                               (planned)
```

The frontend consumes data derived from it. Events and sessions become calendars and schedules, standings and classifications become stats tables, race laps become graphs.

### What upstream changes

Observed so far:

- Series are renamed when they are renamed in real life. This is harmless, because rows are deduplicated on the motorsportstats UUID.
- Ongoing seasons change. Events are cancelled and sometimes replaced, timetables move, events are added.
- Classifications change for a few hours after a race, as post-race penalties land.

Past data has little reason to change. How often upstream drops rows has never been measured: the cache holds one snapshot per key, so there is nothing to compare.

Some changes have consequences downstream and some do not:

| Change | Downstream |
|---|---|
| An event disappears | Everything under it is dead data, down to race lap graphs |
| An event appears | Its sessions need classifications and race laps scraped, then tables and graphs built |
| A post-race penalty moves a driver | The session's results, and every stats table built from them, must be rebuilt |
| A series is renamed, a session rescheduled, an event moves venue | Nothing |

### Options considered

**A. Accept it: never delete.** Today's behavior. Rejected: a cancelled event and everything under it would stay forever, read like current data, and feed tables and graphs.

**B. Reconcile everywhere, row by row.** After scraping, compare with the database. Delete what is missing, leave what is identical, update what changed, and sort each change into harmless or needing a downstream rebuild. Rejected as the general mechanism, because it is a lot of logic for no gain at the leaves:

- Leaf rows have no upstream key. A classification row is keyed by car number and `occurrence`, which is a position in the payload.
- Every field of a leaf feeds the stats, so no change there is harmless.

Where rows have UUIDs, reconciling shrinks to one `DELETE` per table. That part is kept, see the decision.

**C. Replace everywhere: wipe the scope with cascading deletes, then re-insert.** This is simple and needs no natural key, and it is kept at the leaves. It is rejected above them:

- A calendar refresh, every few days during a season, would cascade-delete every session of the season with all their classifications and race laps. That includes rounds that did not change.
- All of it would then be re-scraped and rebuilt. Most re-scrapes would be cache hits, but every table and graph of the season would still be rebuilt, and every session would get a new id.
- The `DELETE ... uuid <> ALL(...)` statement in the decision does the same job in one statement, and leaves unchanged rows alone.

**D. Sort changes into harmless and consequential inside the save.** Rejected for the save path. Deciding what to re-scrape or rebuild after a save is orchestration, a separate concern. Whether a change matters depends on the column, not the table. A session's `start_time` moving is harmless. Its `has_results` turning true means a classification is waiting to be scraped.

### Decision

After a scrape, the scope it owns matches the payload. Deletes cascade down the hierarchy, never sideways into horizontal data.

**Series, seasons, events and sessions: upsert, then delete what the payload dropped.** These rows carry a motorsportstats UUID. `shared.Save()` already handles new, identical and changed rows. Add one statement per table, run after the upserts succeed:

```sql
DELETE FROM events WHERE season = $1 AND uuid <> ALL($2);
```

| Repository | Scope | Deletes |
|---|---|---|
| `SaveSeriesRepository` | the full series list | series missing from the payload |
| `SaveSeasonsRepository` | one series | that series' seasons missing from the payload |
| `SaveCalendarRepository` | one season | that season's events missing from the payload, then that season's sessions missing from it |

An event or session that moved to another parent is in the new parent's payload, so the upsert moves it and the delete never sees it.

**Classifications, and race laps later: wipe, then re-insert.** These rows have no upstream key. Delete the endpoint's rows for the session, then save as today.

The scope is the session and the endpoint, not the whole session. A classification rescrape deletes the session's classifications, whose driver links go with them through the cascade, and its retirements. It never touches race laps: they come from another endpoint, and the classification rescrape could not put them back.

Keep `shared.Save()` for the re-insert. After a wipe a plain insert would do, but the upsert lets two concurrent runs converge.

The wipe has two accepted costs:

- **History churn.** Every rescrape of a session rewrites its rows and adds a full copy of them to history, even when nothing changed, so item 15's check that a rescrape writes nothing no longer holds for leaves. A session is rescraped a few times after a race, so the volume does not matter. With the DELETE branch below, history keeps the provisional result next to the post-penalty one.
- **Leaf row ids change on every rescrape.** See the constraints below.

**The schema carries the rule.** Today all 12 foreign keys in `etc/Migrations/core/` are `ON DELETE RESTRICT`.

| Foreign keys | Become |
|---|---|
| `seasons.series`, `events.season`, `sessions.event`, `classifications.session`, `retirements.session`, `classification_drivers.classification` | `ON DELETE CASCADE` |
| `events.country`, `events.venue`, `classifications.team`, `classifications.garage`, `retirements.driver`, `classification_drivers.driver` | `ON DELETE RESTRICT`, unchanged |

Postgres then enforces "down, never sideways". Deleting a missing event takes its whole subtree, and deleting a horizontal row that anything references is refused. New tables follow the same split.

**Every history trigger gets a DELETE branch** that closes `valid_to`, and fires `AFTER INSERT OR UPDATE OR DELETE`. Cascaded deletes fire the row triggers of child tables too, so the subtree's history closes with it. The branch must return before the insert, because `NEW` is null on a delete:

```sql
IF (TG_OP = 'DELETE') THEN
    UPDATE <table>_history
    SET valid_to = NOW()
    WHERE id = OLD.id AND valid_to IS NULL;

    RETURN OLD;
END IF;
```

Update the template in `etc/Migrations/CLAUDE.md` as well, so new tables get the branch.

### No transaction

`PGXPoolAdapter` has no transaction support: `Exec` and `Query` run on the pool, and each statement commits on its own. Adding it for the wipe was considered and rejected:

- **A failure after the wipe.** The handler fails and the queue brings the message back, every 30 seconds, since nothing deletes a failed message. The wipe is a no-op on the rerun, and the save goes ahead.
- **A failure that repeats**, such as a payload tripping a constraint the validation does not cover. The session stays empty until the bug is fixed. The rows it lost were out of date anyway. The new payload is already cached, because the connector's `cache.Set` runs before the save. An empty session with a loudly failing message is more honest than one showing results that are no longer true.
- **Two workers on the same session.** They converge, or one fails and retries. The worst interleaving: B wipes between A's classification insert and A's driver-link insert. A's link insert then fails on the foreign key, and A retries. Scrape orders are manual, so this is an operator mistake, not a normal path.

### Constraints on later work

- **Rebuild after the save, never alongside it.** Something reading a session during its save sees it empty or half written: classifications with no driver links, no retirements yet. A Worker that builds a session's tables after its save returns, as planned, needs nothing more. If the Worker ever reads on its own schedule, reconsider the transaction.
- **Key derived data by session, never by leaf rows.** Leaf ids change on every rescrape, so nothing outside a leaf's scope may reference them. `classification_drivers` points at classification ids, which is fine: it sits inside the wiped scope, and the cascade takes it along. The frontend fetches by session anyway: a series, then its seasons, then their events and sessions, then each session's visualizations.
- **Fetch fresh data once per rescrape, not once per retry.** Suppose the rescrape option travels in the message and every run of the handler clears the cache. A failure that repeats would then fetch from motorsportstats every 30 seconds until it is fixed. Clear or fetch once, and let retries read from the cache.

### Open questions

These were not settled. Decide them while building.

- **Empty and truncated payloads above the leaves.** With the delete, an empty calendar deletes everything under the season, and an empty series list deletes everything. `SaveCalendar` returns early on an empty calendar today (`save_calendar_repository.go`), which would skip the delete without a word. Decide whether an empty list at a UUID level means "delete all" or fails loudly. A truncated list that still validates cascades the same way. The payloads under it stay in the cache, unless the rescrape cleared them, so re-running the scrapes from the cache restores them.
- **Teams and garages.** Whether they are horizontal depends on how motorsportstats handles teams. Treat them as horizontal (`RESTRICT`, never deleted) until that is decided.
- **Orphaned horizontal rows.** A driver or venue that nothing references any more stays. Cleaning those up would be a separate job, and none has been asked for.
- **Existing core databases.** Item 15 rewrote the core migrations in place, because the database was disposable. Check that it still is before doing the same for the foreign keys and triggers. Otherwise, write forward migrations, following `etc/Migrations/CLAUDE.md`.
- **Planned levels:** standings, analytics, race laps. They should follow the same rule: upsert and delete where upstream gives UUIDs, wipe per scope and endpoint where it does not. Check their payloads first.

### Where to start

- `src/Golang/motorsporttracker/scrapping/{series,seasons,calendar,classification}/infrastructure/save_*_repository.go` hold the four repositories that gain a delete.
- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go` holds `shared.Save()`, which stays as it is.
- `etc/Migrations/core/` holds 12 tables, 12 history triggers and 12 foreign keys.
- `src/Golang/motorsportstats/dependencyinjection/infrastructure/services_registry.go` and `src/Golang/motorsportstats/connector/infrastructure/connector_decorator_with_cache.go` are where the rescrape option will bypass or clear the cache.

---

## 2. Storing Embedded Entities as Content-Addressed Looks

Decided 2026-10-13, while designing the standings tables. Standings are built this way first. Classifications move over later, as a separate job (see "Classifications, later"). This plan amends plan 1: the horizontal tables plan 1 protects go away.

### The problem

Every payload row embeds entity objects: a driver, a team, a country, a constructor. The classification save splits them into identity tables keyed by UUID (`drivers`, `teams`, `countries`) and a look table keyed by part of its content (`garages`, keyed `uuid:name:colour`). Standings embed the same entities, so the question was whether to share those tables, and how to key them.

### What the data shows

First measured on 2026-10-13 on 9,952 classifications and 568 standings of F1, MotoGP, WEC and IMSA, then re-measured the same day once `etc/ConnectorCache/` held 23 series over every season: 24,354 classifications and 956 standings, from single-seaters, sportscar and endurance series, GT series, MotoGP and the World Rally Championship, all fetched within a few days. Every hypothesis held on both runs. The numbers below are from the second.

- **Looks repeat a lot.** Driver objects: 1,173,950 mentions, 20,107 distinct (58×). Teams: 837,629 and 5,400 (155×). Countries: 831,960 and 105 (7,923×). Constructors: 1,577 and 311 (5×). Race laps will multiply this: the PHP production database holds 2.2 million of them, in 1.1 GB.
- **A team's look belongs to an entry, not to the team.** 508 of the 3,299 team UUIDs in classifications have more than one look, and a UUID has several looks within one season 731 times. The Lotus UUID is both "Gold Leaf Team Lotus" (`#0090FF`) and "John Player Team Lotus" (`#F92515`) at the 1971 Canadian Grand Prix. This is what `garages` exists for.
- **A driver's look never varies.** None of 15,748 driver UUIDs has more than one name, short code, colour, picture or nationality, within a source or across sources. Upstream keeps one record per driver and embeds its current state in every payload, past or present. Kvyat is "Russia" from F1 2014 to IMSA 2025, through the years he raced as a neutral. Short codes are per driver, not per series: Norris is `NORL` in F1 too. They are not unique either: `MAN` is shared by several drivers, Mansell among them. The only axis of change is time between fetches, which a single snapshot cannot show.
- **Sources disagree on fields, not on values.** Team standings send a profile photo as `team.picture` (`teamProfilePicture-<uuid>.jpg`, or null) and the colour badge as the row's `icon`. Every other source sends the badge as `team.picture`. The badge is always `team/icon/teamColour_<colour>.svg` (2,924 of 2,924) and matches the other sources for the same team and colour (2,895 of 2,895 comparable). Every source keeps its own image fields to these shapes, rallies included, and every country UUID has one look (105 of 105). Standings drivers have no `firstName` or `lastName`.

### Options considered

**A. Identity tables keyed by UUID, holding the look.** Today's `drivers` and `countries`. Rejected: there is one look per UUID, so a source sending a different look overwrites the other, and history fills with the flip-flop. It only works for drivers today because driver looks never vary.

**B. Look tables keyed by part of their content.** Today's `garages`. Rejected: fields outside the key get overwritten. Within classifications this hits 6 of 4,522 garage keys, all on the car icon, as with a placeholder team whose car icon is `RaceCar` in some series and `Bike` in MotoGP. Shared with team standings, it would hit every photo.

**C. Look fields as columns on every fact row.** Rejected after measuring: the 58× to 7,923× repetition above, multiplied again by race laps.

**D. UUID-only identity tables, next to the look tables.** Rejected. A foreign key to a table filled from the same payload a moment earlier proves nothing, and analysis joins need the UUID on both sides, not a table between them.

**E. Look tables keyed by their full content.** Chosen.

### Decision

**Every embedded entity is stored as a look: one row per distinct content, keyed by a hash of every field.** There is one look table per entity, shared across sources: `driver_looks`, `team_looks`, `country_looks`, `constructor_looks` and `class_looks`. The rule holds everywhere, even where a table stays small: the cache has 311 constructor looks over 1,577 mentions, and a few dozen classes.

- **Insert-only.** Identical content hashes the same and is skipped. A difference in any field makes a new row. Nothing is ever overwritten, so a past row keeps the look it was sent with. `shared.Save()` with `hash` as the conflict column already behaves this way: its `WHERE hash IS DISTINCT FROM EXCLUDED.hash` can never hold on a hash conflict.
- **No history table.** A look row never changes, so there is nothing to record. `etc/Migrations/CLAUDE.md` gets this as an explicit exception to "every table needs history".
- **Every save sorts its rows by the conflict key before batching.** Two batch saves touching the same keys in a different order can deadlock: Postgres aborts one and the queue retries it, which is loud but avoidable. Sorting gives every worker the same lock order. Any consistent total order works, so `shared.Save()` can do it once for every caller, from the conflict columns it already receives.
- **The hash must tell null from `""`, and no value may be able to break it.** Hash a JSON array of the fields in a fixed column order, or an equivalent encoding. Today's helpers do not qualify: `SaveCountries` hashes `uuid|name|flag` with nulls as `""`, and the cache holds empty team names. Under insert-only, a collision would silently give a row another look's values.
- **Looks are never deleted.** A rescrape that wipes leaf rows leaves their looks behind. An orphaned look is harmless.

**Columns are named by meaning, and the repository maps each source's keys onto them.** Values are placed, never changed.

| Look | Column | Classifications, driver and constructor standings | Team standings |
|---|---|---|---|
| `team_looks` | `name`, `colour` | `team.name`, `team.colour` | same |
| | `badge` | `team.picture` | the row's `icon` |
| | `car_icon` | `team.carIcon` | same |
| | `photo` | not sent | `team.picture` |
| `driver_looks` | `name`, `short_code`, `colour` | as sent | as sent |
| | `first_name`, `last_name` | sent by classifications | not sent by standings |
| | `photo` | `driver.picture` | same |
| `country_looks` | `name`, `flag` | `name`, `picture` | same |
| `constructor_looks` | `name`, `logo` | constructor standings only: `name`, `picture` | not sent |
| `class_looks` | `name` | available standings only: `name` | not sent |

A field a source does not send stays NULL. Two sources sending the same content, apart from a column only one of them sends, give two rows. That is the price of not inventing values.

**Fact rows carry the entity's UUID inline, next to the look reference:** `driver_uuid` and `driver_look`, for instance. Analyses filter and join on the UUID, as in classifications to race laps on `(session, driver_uuid)`. Display reads the look. There are no identity tables. Index every UUID column analyses filter by. Look references are `ON DELETE RESTRICT`.

**Leaf rows keep payload order in a column, `payload_index`, and every read up to the frontend orders by it.** Postgres guarantees no row order, and `position` cannot stand in: unranked entries come last at position 0, and ties share a position.

### Standings

- **`standings`**: one row per entry of the available standings. Columns: `season` (`ON DELETE CASCADE`), `kind`, `class_uuid` and `class_look` (both NULL for the overall standing), and `available_index`, the entry's place in the available list. The key is `UNIQUE NULLS NOT DISTINCT (season, kind, class_uuid)`. Without `NULLS NOT DISTINCT`, `ON CONFLICT` never matches an overall standing and every re-save inserts a duplicate, without an error. A repository test saves an overall standing twice and asserts one row. This table is upserted and has history.
- **`driver_standings`, `team_standings`, `constructor_standings`**: `standing` (`ON DELETE CASCADE`), `payload_index`, `UNIQUE (standing, payload_index)`, the entity UUIDs and look references, `position`, `points`, and the analytics columns as sent. They have history, per the migration rules. On rescrape, plan 1 applies: wipe the standing's rows, then re-insert.

### Classifications, later

Classifications move to the same model as a self-contained job. `classifications`, `classification_drivers` and `retirements` carry UUIDs plus look references, and `drivers`, `teams`, `garages` and `countries` are replaced by look tables. Check first whether the core database is still disposable (plan 1, "Existing core databases"). If it is, re-scraping from the connector cache rebuilds everything. Otherwise, the move needs a data migration.

### Changes to plan 1

- The horizontal tables (`drivers`, `teams`, `garages`, `countries`) and the `RESTRICT` foreign keys protecting them go, replaced by look tables, which are never deleted. Plan 1's foreign key table changes accordingly when classifications move.
- Plan 1's "orphaned horizontal rows" question becomes orphaned looks, which are harmless.

### Not planned

- **Our own facts about an entity**, such as canonical names, or merging "Era Motorsport" and "Era Motorsports", two UUIDs in IMSA 2020. The data stays as close to what motorsportstats says as possible. If this is ever wanted, it belongs in a separate table keyed by UUID, filled from the distinct UUIDs in the fact tables, and nothing here prevents adding it.

### Order of work

Classifications, standings and race histories come first. Then every series of interest gets scraped, and what the other series send is looked at then. 23 of the 86 series were measured, and none assigns short codes or nationalities per event. One that did would not change the payload shape, so the schemas would not catch it, but looks keyed by full content would store it correctly either way.

### Where to start

- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go` holds `shared.Save()`, which already handles insert-only with `hash` as the conflict column, and is where the sort by conflict key goes.
- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_countries_helper.go` shows the hash encoding that must not be reused.
- `src/Golang/motorsporttracker/scrapping/classification/infrastructure/save_classification_repository.go` is the classification save to move later, garage keys included.
- `src/Golang/motorsportstats/gateway/domain/gateway.go` holds the domain structs the looks are built from.
