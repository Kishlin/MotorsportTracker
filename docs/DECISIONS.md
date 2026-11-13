# Decisions

Designs settled before, or during, the work that needs them. Each section states the decision as it stands today, with every option that lost and why, so the discussion does not have to happen twice. When a later decision changes an earlier one, the earlier section is rewritten, not amended: there is one current text per topic.

What gets built, and in which order, lives in [ROADMAP.md](ROADMAP.md).

File references were checked against the code on 2026-11-13. Check them again before acting on a section.

---

## Embedded entities: one current state, or one look per entry

Decided 2026-10-13, and split into two kinds on 2026-11-13. The standings tables are built this way. Classifications and calendars move over later.

### The problem

Every payload row embeds entity objects: a driver, a team, a country, a constructor, a venue. The question was how to store them so every source can share them, and what a page shows when the same UUID comes with different content.

### What the data shows

Measured on `etc/ConnectorCache/`, which holds 23 series over every season: single-seaters, sportscar and endurance series, GT series, MotoGP and the World Rally Championship. The first run, on 2026-10-13, covered 24,354 classifications and 956 standings. The second, on 2026-11-13, covered 2,853,453 entity mentions in 488 seasons, calendars included.

- **Entities repeat a lot.** Driver objects: 1,173,950 mentions, 20,107 distinct (58×). Teams: 837,629 and 5,400 (155×). Countries: 831,960 and 105 (7,923×). Constructors: 1,577 and 311 (5×). Race laps will multiply this: the PHP production database holds 2.2 million of them, in 1.1 GB.
- **A team's look belongs to an entry, not to the team.** 514 of 3,343 team UUIDs have more than one look, 164 of them within a single event, varying in name (510), colour (446) and car icon (451). The Lotus UUID is both "Gold Leaf Team Lotus" (`#0090FF`) and "John Player Team Lotus" (`#F92515`) at the 1971 Canadian Grand Prix.
- **A venue's look belongs to an event.** 15 of 233 venue UUIDs have more than one look, always and only through `picture`, the circuit layout drawn for that event. Indianapolis is the oval from 1950 to 1960 and the road course from 2000. F1 2020 has two Bahrain looks, for the Bahrain and the Sakhir Grands Prix. Melbourne and Yas Marina change after their reconfigurations. Some series send no layout at all (`null`), as Formula E does in Monaco. Name, short name and short code never vary.
- **Every other entity has one state per UUID, everywhere.** 15,748 drivers, 107 countries (event countries and nationalities alike), 311 constructors and 46 classes, each with exactly one look across every season, series and source. Upstream keeps one record per entity and embeds its current state in every payload, past or present. Kvyat is "Russia" from F1 2014 to IMSA 2025, through the years he raced as a neutral. Short codes are per driver, not per series: Norris is `NORL` in F1 too. They are not unique either: `MAN` is shared by several drivers, Mansell among them.
- **That state changes between fetches.** A refetch on 2026-11-13 found "Jean-Eric Vergne" renamed "Jean-Éric Vergne", in classifications and standings alike, and da Costa's first name gone from "António" to "António Félix".
- **Sources disagree on fields, not on values.** Team standings send a profile photo as `team.picture` (`teamProfilePicture-<uuid>.jpg`, or null) and the colour badge as the row's `icon`. Every other source sends the badge as `team.picture`. The badge is always `team/icon/teamColour_<colour>.svg` (2,924 of 2,924) and matches the other sources for the same team and colour (2,895 of 2,895 comparable). Standings drivers have no `firstName` or `lastName`.
- **First and last names add little to the full name.** For 15,255 of 15,693 drivers, `name` is `firstName + " " + lastName`. What they add is where the first name ends: 283 first names have several words ("Juan Carlos Peralta"), Zhou Guanyu is sent family name first, and mononyms come with an empty first name.

### Options considered

**A. Tables keyed by UUID, holding the current state.** Today's `drivers` and `countries`. Chosen for drivers, countries, constructors and classes. On 2026-10-13 it was rejected for every entity, on three grounds the 2026-11-13 measurements overturned for these four kinds:

- A source sending a different look would overwrite the other's. No source disagrees on a value for them.
- History would fill with the flip-flop. That needs disagreeing values.
- A rename would rewrite every past row's entity. That is what a page should show, since upstream itself shows the current state on past payloads.

Rejected for teams and venues, whose looks vary per entry and event: each save would overwrite the others' looks.

**B. Look tables keyed by part of their content.** Today's `garages`. Rejected: fields outside the key get overwritten. Within classifications this hits 6 of 4,522 garage keys, all on the car icon, as with a placeholder team whose car icon is `RaceCar` in some series and `Bike` in MotoGP. Shared with team standings, it would hit every photo.

**C. Entity fields as columns on every fact row.** Rejected after measuring: the 58× to 7,923× repetition above, multiplied again by race laps.

**D. UUID-only identity tables, next to look tables.** Today's `teams`. Rejected. A foreign key to a table filled from the same payload a moment earlier proves nothing, and analysis joins need the UUID on both sides, not a table between them.

**E. Look tables keyed by their full content.** Chosen for teams and venues. Rejected for the other four, after being chosen for every entity on 2026-10-13. Showing their current state needs the last-fetched look for each UUID, so every look would carry a last-fetched date that every payload bumps. Looks would stop being insert-only and become a table keyed by UUID with history, made more complicated. "Last fetched" would also flip between sources that send different fields, such as classifications with first names and standings without.

### Decision

**Two kinds of embedded entity, told apart by how upstream treats them.**

**Drivers, countries, constructors and classes keep one current state per UUID**, in `drivers`, `countries`, `constructors` and `classes`: keyed by `uuid`, upserted by `shared.Save()`, with history.

- **A save writes what the payload says.** History keeps the earlier state: "Jean-Eric Vergne" stays in `drivers_history`, valid until the rename.
- **Every source writes the same columns.** Otherwise each source would overwrite the others' columns with NULL, and history would fill with the flip-flop. Driver first and last names are dropped for that reason: standings do not send them, and the full name holds them. The payloads keep them, so a rebuild from the connector cache can bring them back if a page ever needs the split.
- **Fact rows reference them by id**: `driver`, `nationality`, `constructor`, `class`, `country`, per the migration conventions. Analyses join on these ids as they would on UUIDs. They never change, since these rows are never deleted.

**Teams and venues are stored as looks: one row per distinct content, keyed by a hash of every field**, in `team_looks` and `venue_looks`.

- **Insert-only.** Identical content hashes the same and is skipped. A difference in any field makes a new row. Nothing is ever overwritten, so a past row keeps the look it was sent with. `shared.Save()` with `hash` as the conflict column already behaves this way: its `WHERE hash IS DISTINCT FROM EXCLUDED.hash` can never hold on a hash conflict.
- **No history table.** A look row never changes, so there is nothing to record. `etc/Migrations/CLAUDE.md` states this exception.
- **Looks are never deleted.** A rescrape that wipes leaf rows leaves their looks behind. An orphaned look is harmless.
- **Fact rows carry the entity's UUID inline, next to the look reference:** `team_uuid` and `team_look`. There is no team identity table. Analyses filter and join on the UUID, display reads the look. Every UUID column analyses filter by is indexed.

**For both kinds:**

- **Every save sorts its rows by the conflict key before batching.** Two batch saves touching the same keys in a different order can deadlock: Postgres aborts one and the queue retries it, which is loud but avoidable. Sorting gives every worker the same lock order. Any consistent total order works, so `shared.Save()` can do it once for every caller, from the conflict columns it already receives.
- **The hash must tell null from `""`, and no value may be able to break it.** Hash a JSON array of the fields in a fixed column order, or an equivalent encoding. Today's helpers do not qualify: `SaveCountries` hashes `uuid|name|flag` with nulls as `""`, and the cache holds empty team names. On a look table, a collision would silently give a row another look's values. On an identity table, a change would go unseen.
- **References are `ON DELETE RESTRICT`.**

**Columns are named by meaning, and the repository maps each source's keys onto them.** Values are placed, never changed.

| Table | Column | Sent as |
|---|---|---|
| `drivers` | `name`, `short_code`, `colour`, `picture` | the same keys in every source |
| `countries` | `name`, `flag` | `name`, `picture`, in every source |
| `constructors` | `name`, `logo` | constructor standings only: `name`, `picture` |
| `classes` | `name` | available standings only: `name` |
| `team_looks` | `name`, `colour`, `car_icon` | `team.name`, `team.colour`, `team.carIcon`, in every source |
| | `badge` | `team.picture`, or the row's `icon` in team standings |
| | `photo` | team standings only: `team.picture` |
| `venue_looks` | `name`, `short_name`, `short_code` | calendars only: as sent |
| | `layout` | calendars only: `venue.picture` |

A look field a source does not send stays NULL. Two sources sending the same look, apart from a column only one of them sends, give two rows. That is the price of not inventing values.

**Leaf rows keep payload order in a column, `payload_index`, and every read up to the frontend orders by it.** Postgres guarantees no row order, and `position` cannot stand in: unranked entries come last at position 0, and ties share a position.

### What a page shows

- **Drivers, countries, constructors and classes: their current state.** A rename shows everywhere, including on past seasons, as upstream does.
- **Teams and venues: the row's own look.** "Gold Leaf Team Lotus" at the 1971 Canadian Grand Prix stays that, and so does the Indianapolis oval in 1950. A team has no single name even within one season, so a team's season page lists its looks, or picks one. An upstream correction to a look reaches us when that payload is rescraped, and the leaf re-insert picks it up.
- **Upstream decides nationality, not us.** A per-event nationality would have to be invented, which "Not planned" rules out.

If a series turns out to vary one of the four identity kinds per entry, its rows would change back and forth with every save of that series, and history would show it. That is the signal to move the entity to looks. The schemas would not catch it, since the payload shape stays the same.

### Standings

Built as migrations (`etc/Migrations/core/20261013183809` to `20261013183816`).

- **`standings`**: one row per entry of the available standings. Columns: `season` (`ON DELETE CASCADE`), `kind`, `class` (NULL for the overall standing), and `available_index`, the entry's place in the available list. The key is `UNIQUE NULLS NOT DISTINCT (season, kind, class)`. Without `NULLS NOT DISTINCT`, `ON CONFLICT` never matches an overall standing and every re-save inserts a duplicate, without an error. A repository test must save an overall standing twice and assert one row. This table is upserted and has history.
- **`driver_standings`, `team_standings`, `constructor_standings`**: `standing` (`ON DELETE CASCADE`), `payload_index`, `UNIQUE (standing, payload_index)`, the entity references, `position`, `points`, and the analytics columns as sent. They have history. In endurance series a team standings row is a car, not a team, so two cars of one team are told apart by `payload_index` only.

### Classifications and calendars, later

`classifications` moves from `team` and `garage` to `team_uuid` and `team_look`, and `teams` and `garages` are replaced by `team_looks`. `events` moves from `venue` to `venue_uuid` and `venue_look`, and `venues` is replaced by `venue_looks`. Drivers and countries stay as they are. Check first whether the core database is still disposable. If it is, re-scraping from the connector cache rebuilds everything. Otherwise, the move needs a data migration.

### Not planned

- **Our own facts about an entity**, such as canonical names, or merging "Era Motorsport" and "Era Motorsports", two UUIDs in IMSA 2020. The data stays as close to what motorsportstats says as possible. If this is ever wanted, it belongs in a separate table keyed by UUID, and nothing here prevents adding it.

### Where to start

- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go` holds `shared.Save()`, where the sort by conflict key goes.
- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_countries_helper.go` shows the hash encoding that must not be reused.
- `src/Golang/motorsporttracker/scrapping/classification/infrastructure/save_classification_repository.go` writes `drivers`, first and last names included, and is the classification save to move later, garage keys included.
- `src/Golang/motorsportstats/gateway/domain/gateway.go` holds the domain structs the rows are built from.

---

## Upstream responses are versioned

Decided 2026-11-13. Not built.

### The problem

The database cache keeps one answer per key: `DatabaseCache.Set` runs `INSERT ... ON CONFLICT(key) DO UPDATE SET value`. Once a rescrape fetches fresh data, the previous response is gone. There is then no way to see what motorsportstats said before, to debug what a save did with it, or to measure how upstream changes over time.

### What the data shows

On 2026-11-13, 20 of the oldest cached responses were fetched again: four each of calendars, classifications, available standings, driver standings and seasons lists.

- **Upstream bytes are stable.** Whenever the bytes differed, the parsed JSON differed too, so there is no serialisation noise to normalise away. Hashing the raw bytes is enough.
- **11 of the 20 had changed.** Most were seasons ongoing at fetch time: sessions added to an event, times moved, `hasResults` turning true, and a new season at the head of each seasons list. Two classifications and one driver standing of past seasons changed too, through the driver renames described under looks.

### Options considered

**A. Overwrite, as today.** Rejected: a rescrape erases what upstream said before.

**B. An archive next to the cache.** Rejected: the cache is the latest version of the archive. Two stores would hold the same thing twice.

**C. Bodies stored once by hash, with versions pointing at them.** Rejected: rescrapes are rare (ongoing seasons for calendar changes and new sessions, post-race corrections now and then), so a body per version costs little, and one table is simpler.

**D. Core rows referencing the version they were built from (`source_version`).** Rejected for now. How often that kind of exploration will happen is unknown, and it is work on every save path. Nothing here prevents adding it later.

**E. Versioning the filesystem cache too.** Rejected: it is a development and study tool. It keeps the latest version only.

### Decision

**The database cache keeps every distinct response of a key, in fetch order. Reads return the latest one.**

- **A refetch identical to the latest version only bumps that version's last-fetched date.** Identical means the same hash of the raw bytes.
- **Anything else is a new version, even when it matches an older one.** If upstream goes A, then B, then A again (a post-race penalty overturned, for instance), the key has three versions. Bumping the first A instead would claim A held throughout and hide B.
- **The core `_history` tables stay.** Replaying versions runs today's code. History records what the code of the time wrote.
- **An older fetch never overwrites a newer one in an identity table.** A replayed version of a payload carries the state upstream had then. If it overwrote `drivers`, "Jean-Éric Vergne" would turn back into "Jean-Eric". Each identity row therefore records the fetch time of the payload it was saved from, and a save from an older payload leaves it alone. The fetch time has to reach the save path, which nothing carries today. Until versioning is built, nothing can be replayed, so this waits for it.

### Open questions

- **Table shape.** The per-namespace tables in `etc/Migrations/client-cache/` would lose `UNIQUE (key)` and gain the hash and the first and last fetch dates. Not designed yet.
- **The filesystem cache during a rescrape.** With `USE_FS_CACHE=true` it sits on top of the database cache and would serve the old body. A rescrape must refetch past it and overwrite it.
- **Retention.** Nothing is ever pruned. None has been asked for.

### Where to start

- `src/Golang/shared/cache/infrastructure/cache_using_database.go` holds `setQuery` and the read.
- `src/Golang/motorsportstats/connector/infrastructure/connector_decorator_with_cache.go` is where a rescrape bypasses the cached value.
- `src/Golang/motorsportstats/dependencyinjection/infrastructure/services_registry.go` stacks the filesystem cache on the database cache.

---

## Rescrapes keep stored data in step with upstream

Decided 2026-09-28. Not built: it is built together with the rescrape option, and resolves review issue 16 in [review/ISSUES.md](review/ISSUES.md).

### The problem

`shared.Save()` only inserts and updates (`INSERT ... ON CONFLICT DO UPDATE`), and no repository deletes anything. When motorsportstats drops something the scraper stored earlier, the stored row stays and reads exactly like a current one. The history triggers fire on `INSERT OR UPDATE` only, so they could not record a delete either.

### Why it cannot happen yet

Every scrape goes through `CachedConnector` over `DatabaseCache`, with a `FileSystemCache` on top when `USE_FS_CACHE=true`. Neither expires. A rescrape replays the bytes saved the first time, so the save never sees a payload missing something it stored. Drift reaches the database once a rescrape option fetches fresh data. With versioned responses, that fetch adds a version or bumps the latest one, and every later read gets it.

One path exists today. Changing how a save derives its keys, then re-saving the same cached bytes, orphans the rows stored under the old keys. Review item 15 did this with `occurrence`, and handled it by dropping and re-migrating core.

### How the data is shaped

Facts form a hierarchy, and data flows down it:

```
Series → Season → Event → Session → Classification        (built)
Series → Season → Standings                               (tables built)
Series → Season → Event → Session → Entry → Race Lap      (planned)
Series → Season → Analytics                               (planned)
```

Entities sit beside the hierarchy: the identity tables (`drivers`, `countries`, `constructors`, `classes`) and the looks (`team_looks`, and `venue_looks` later). Facts reference them, and they are never deleted. Until classifications and calendars move, `teams`, `garages` and `venues` play the looks' part.

The frontend consumes data derived from the hierarchy. Events and sessions become calendars and schedules, standings and classifications become stats tables, race laps become graphs.

### What upstream changes

- Series are renamed when they are renamed in real life. This is harmless, because rows are deduplicated on the motorsportstats UUID.
- Ongoing seasons change. Events are cancelled and sometimes replaced, timetables move, events and sessions are added.
- Classifications change for a few hours after a race, as post-race penalties land.
- Seasons lists grow a new season at their head.
- Entities change on past data too, as with the Vergne rename.

How often upstream drops rows has still not been measured.

| Change | Downstream |
|---|---|
| An event disappears | Everything under it is dead data, down to race lap graphs |
| An event appears | Its sessions need classifications and race laps scraped, then tables and graphs built |
| A post-race penalty moves a driver | The session's results, and every stats table built from them, must be rebuilt |
| A series is renamed, a session rescheduled, an event moves venue | Nothing |

### Options considered

**A. Accept it: never delete.** Today's behaviour. Rejected: a cancelled event and everything under it would stay forever, read like current data, and feed tables and graphs.

**B. Reconcile everywhere, row by row.** After scraping, compare with the database. Delete what is missing, leave what is identical, update what changed, and sort each change into harmless or needing a downstream rebuild. Rejected as the general mechanism, because it is a lot of logic for no gain at the leaves:

- Leaf rows have no upstream key. A classification row is keyed by car number and `occurrence`, which is a position in the payload.
- Every field of a leaf feeds the stats, so no change there is harmless.

Where rows have UUIDs, reconciling shrinks to one `DELETE` per table. That part is kept, see the decision.

**C. Replace everywhere: wipe the scope with cascading deletes, then re-insert.** Simple, and needs no natural key, so it is kept at the leaves. Rejected above them:

- A calendar refresh, every few days during a season, would cascade-delete every session of the season with all their classifications and race laps, including rounds that did not change.
- All of it would then be re-scraped and rebuilt, and every session would get a new id.
- The `DELETE ... uuid <> ALL(...)` statement in the decision does the same job in one statement, and leaves unchanged rows alone.

**D. Sort changes into harmless and consequential inside the save.** Rejected for the save path. Deciding what to re-scrape or rebuild after a save is orchestration, a separate concern. Whether a change matters depends on the column, not the table. A session's `start_time` moving is harmless. Its `has_results` turning true means a classification is waiting to be scraped.

### Decision

After a scrape, the scope it owns matches the payload. Deletes cascade down the hierarchy, never sideways into entities.

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

**Leaves (classifications, standings rows, race laps later): wipe, then re-insert.** These rows have no upstream key. Delete the endpoint's rows for the scope, then save as usual. For standings, the scope is one standing.

The scope is the endpoint, not everything under the parent. A classification rescrape deletes the session's classifications, whose driver links go with them through the cascade, and its retirements. It never touches race laps: they come from another endpoint, and the classification rescrape could not put them back.

Keep `shared.Save()` for the re-insert. After a wipe a plain insert would do, but the upsert lets two concurrent runs converge.

The wipe has two accepted costs:

- **History churn.** Every rescrape of a scope rewrites its rows and adds a full copy of them to history, even when nothing changed. A session is rescraped a few times after a race, so the volume does not matter. With the DELETE branch below, history keeps the provisional result next to the post-penalty one.
- **Leaf row ids change on every rescrape.** See the constraints below.

**The schema carries the rule.** Foreign keys up the hierarchy are `ON DELETE CASCADE`. Foreign keys to entities, identity tables and looks alike, stay `ON DELETE RESTRICT`. The standings tables already follow this. The older tables do not yet:

| Foreign keys | Become |
|---|---|
| `seasons.series`, `events.season`, `sessions.event`, `classifications.session`, `retirements.session`, `classification_drivers.classification` | `ON DELETE CASCADE` |
| `events.country`, `events.venue`, `classifications.team`, `classifications.garage`, `retirements.driver`, `classification_drivers.driver` | `ON DELETE RESTRICT`, unchanged |

Postgres then enforces "down, never sideways". Deleting a missing event takes its whole subtree, and deleting an entity that anything references is refused.

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
- **A failure that repeats**, such as a payload tripping a constraint the validation does not cover. The scope stays empty until the bug is fixed. The rows it lost were out of date anyway. The new payload is already cached, because the connector's `cache.Set` runs before the save. An empty session with a loudly failing message is more honest than one showing results that are no longer true.
- **Two workers on the same scope.** They converge, or one fails and retries. The worst interleaving: B wipes between A's classification insert and A's driver-link insert. A's link insert then fails on the foreign key, and A retries. Scrape orders are manual, so this is an operator mistake, not a normal path.

### Constraints on later work

- **Rebuild after the save, never alongside it.** Something reading a scope during its save sees it empty or half written. A Worker that builds a session's tables after its save returns needs nothing more. If the Worker ever reads on its own schedule, reconsider the transaction.
- **Key derived data by session or standing, never by leaf rows.** Leaf ids change on every rescrape, so nothing outside a leaf's scope may reference them. `classification_drivers` points at classification ids, which is fine: it sits inside the wiped scope, and the cascade takes it along.
- **Fetch fresh data once per rescrape, not once per retry.** If every run of the handler refetched, a failure that repeats would hit motorsportstats every 30 seconds until it is fixed. Fetch once, store the version, and let retries read the latest version.

### Open questions

- **Empty and truncated payloads above the leaves.** With the delete, an empty calendar deletes everything under the season, and an empty series list deletes everything. `SaveCalendar` returns early on an empty calendar today (`save_calendar_repository.go`), which would skip the delete without a word. Decide whether an empty list at a UUID level means "delete all" or fails loudly. A truncated list that still validates cascades the same way. Its earlier versions stay in the cache, so the data can be rebuilt.
- **The `standings` level.** Its rows are keyed by season, kind and class, not by a single UUID. Deleting the standings the available list dropped follows the same rule, with that key in the `DELETE`.
- **Existing core databases.** Review item 15 rewrote the core migrations in place, because the database was disposable. Check that it still is before doing the same for the foreign keys and triggers. Otherwise, write forward migrations, following `etc/Migrations/CLAUDE.md`.
- **Planned levels:** analytics and race laps. They follow the same rule: upsert and delete where upstream gives UUIDs, wipe per scope and endpoint where it does not. Check their payloads first.

### Where to start

- `src/Golang/motorsporttracker/scrapping/{series,seasons,calendar,classification}/infrastructure/save_*_repository.go` hold the repositories that gain a delete or a wipe.
- `src/Golang/motorsporttracker/scrapping/shared/infrastructure/save_repository_helpers.go` holds `shared.Save()`.
- `etc/Migrations/core/` holds the tables, history triggers and foreign keys.

---

## Fan-outs log failed publishes and move on

Decided 2026-10-13. Built for seasons of all series. The standings fan-outs follow it.

### The problem

A fan-out use case, such as `ScrapeSeasonsForAllSeriesUseCase`, calls a `*ScrapperUsingIntents` that only publishes one message per child to the queue. The question was what the parent does when some publishes fail.

The error can only mean the publish failed, after the AWS SDK's default retryer has already tried several times with backoff, or that building the message failed, which no retry fixes. It never means motorsportstats, the schema or the database failed: each child message retries on its own when its own handler fails.

### Options considered

**A. Return the joined failures, so the queue redelivers the parent.** Rejected: redelivery re-publishes every child that already went through, and those re-run. Cache reads and no-op upserts are still database round trips, and a standings fan-out over every series has hundreds to thousands of children.

**B. A FIFO queue with deduplication ids.** Rejected for this problem. It may answer another one some day, such as ordering.

**C. Attempt every publish, log each failure at error level, and return nil.** Chosen.

### Decision

Every fan-out attempts all its publishes, logs each failure at error level with the child it was for, and returns nil. The error log is the loud part. If retries need tuning, configure the SDK retryer in `SQSQueue`'s session config, never a loop in a use case.
