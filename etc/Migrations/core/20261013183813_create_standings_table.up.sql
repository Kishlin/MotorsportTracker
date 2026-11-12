-- One row per entry of a season's available standings. A NULL class is the overall standing, which
-- the payload lists as null. NULLS NOT DISTINCT makes it conflict with itself: without it, ON CONFLICT
-- never matches an overall standing, and every save inserts a duplicate.
-- available_index is the entry's place in its kind's list, as sent.
CREATE TABLE IF NOT EXISTS standings (
    id              SERIAL PRIMARY KEY,
    season          INTEGER NOT NULL,
    kind            TEXT NOT NULL,
    class           INTEGER,
    available_index INTEGER NOT NULL,
    hash            TEXT UNIQUE NOT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE NULLS NOT DISTINCT (season, kind, class),
    CHECK (kind IN ('drivers', 'teams', 'constructors')),
    FOREIGN KEY (season) REFERENCES seasons(id) ON DELETE CASCADE,
    FOREIGN KEY (class) REFERENCES classes(id) ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS standings_class_idx ON standings (class);

CREATE TABLE IF NOT EXISTS standings_history (
    history_id      SERIAL PRIMARY KEY,
    id              INTEGER NOT NULL,
    season          INTEGER NOT NULL,
    kind            TEXT NOT NULL,
    class           INTEGER,
    available_index INTEGER NOT NULL,
    hash            TEXT NOT NULL,
    valid_from      TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to        TIMESTAMP
);

COMMENT ON COLUMN standings_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_standings_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE standings_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO standings_history (id, season, kind, class, available_index, hash, valid_from)
    VALUES (NEW.id, NEW.season, NEW.kind, NEW.class, NEW.available_index, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_standings_history
    AFTER INSERT OR UPDATE ON standings
    FOR EACH ROW
    EXECUTE FUNCTION update_standings_history();
