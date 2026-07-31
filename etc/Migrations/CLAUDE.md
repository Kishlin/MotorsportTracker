# Migrations

Rules for `etc/Migrations/`. Loaded on top of the root `CLAUDE.md`.

## Only two directories are live

```
etc/Migrations/core/           <- active, Go core database
etc/Migrations/client-cache/   <- active, connector response cache
etc/Migrations/Core/           <- DEAD (PHP era, 20 files)
etc/Migrations/Cache/          <- DEAD
etc/Migrations/Client/         <- DEAD
etc/Migrations/Admin/          <- DEAD
```

The Makefile passes `DB_MIGRATE_SOURCE="file:///app/etc/Migrations/$(DB)"` with `DB` set to `core` or `client-cache`. **Lowercase only.**

A migration written into `Core/` is never executed and produces no error — the runner simply never looks there. Case is the entire difference between a working migration and a silent no-op.

## Naming

`YYYYMMDDHHMMSS_<verb>_<subject>.up.sql`. Generate the timestamp with `date -u +%Y%m%d%H%M%S` rather than inventing one; `golang-migrate` orders strictly by that prefix, and a timestamp below an already-applied migration will not run.

**Migrations here are forward-only.** All 13 files in `core/` and all 4 in `client-cache/` are `.up.sql`; there is not a single `.down.sql` in either directory. Do not add one unless asked — a lone down migration implies a rollback path the rest of the project does not have.

## Column conventions

- `TEXT` over `VARCHAR` unless a length constraint is genuinely required
- `UUID` for identifiers coming from motorsportstats.com
- `SERIAL` primary key for internal foreign keys
- `hash TEXT UNIQUE NOT NULL` — required by `shared.Save()` change detection
- `created_at` / `updated_at`, both `TIMESTAMP NOT NULL DEFAULT NOW()`
- foreign keys default to `ON DELETE RESTRICT`

## Every table needs history

Main table, `_history` table, trigger function, trigger. The history table repeats the main table's columns and swaps the timestamps for `valid_from` / `valid_to`.

```sql
CREATE TABLE IF NOT EXISTS <table> (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    -- columns
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS <table>_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    uuid UUID NOT NULL,
    -- same columns
    hash TEXT NOT NULL,
    valid_from TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to TIMESTAMP
);

CREATE OR REPLACE FUNCTION update_<table>_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE <table>_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO <table>_history (id, uuid, /* columns */, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, /* NEW.columns */, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_<table>_history
    AFTER INSERT OR UPDATE ON <table>
    FOR EACH ROW
    EXECUTE FUNCTION update_<table>_history();
```

Adding a column later means editing the trigger function too — it lists columns explicitly, so a new column is silently dropped from history until the function is updated.

## Applying

```bash
make run-dbmigrate-core && make run-dbmigrate-core.test
```

Always both. Skipping `.test` leaves integration suites running against a stale schema, which surfaces as confusing missing-relation failures in code that looks correct.
