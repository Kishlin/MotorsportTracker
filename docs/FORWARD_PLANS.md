# Forward Plans

Decisions made before the work that needs them. Each plan records the problem, every option considered, and why the chosen one won, so the discussion does not have to happen twice.

File and line references were checked against the code on the date each plan gives. Check them again before acting on a plan.

---

## 1. Keeping Stored Data in Step With Upstream on Rescrape

Decided 2026-09-28. Not built: build it together with the rescrape option. Implementing it resolves review issue 16 in [review/ISSUES.md](review/ISSUES.md).

### The problem

`shared.Save()` only inserts and updates (`INSERT ... ON CONFLICT DO UPDATE`), and no repository deletes anything. When motorsportstats drops something the scraper stored earlier, the stored row stays and reads exactly like a current one. The history triggers fire on `INSERT OR UPDATE` only, so they could not record a delete either.

### Why it cannot happen yet

Every scrape goes through `CachedConnector` over `DatabaseCache`, with a `FileSystemCache` on top when `USE_FS_CACHE=true` (`GetMotorsportStatsGateway` in `services_registry.go`). Neither cache expires ([ARCHITECTURE.md](ARCHITECTURE.md), "Client Cache (Filesystem)"). A rescrape replays the bytes saved the first time, so the save never sees a payload missing something it stored.

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
- `src/Golang/motorsporttracker/dependencyinjection/infrastructure/services_registry.go` and `src/Golang/motorsportstats/connector/infrastructure/connector_decorator_with_cache.go` are where the rescrape option will bypass or clear the cache.
