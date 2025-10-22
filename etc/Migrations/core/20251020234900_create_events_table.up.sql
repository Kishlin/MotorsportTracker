CREATE TABLE IF NOT EXISTS events (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    season SERIAL NOT NULL,
    venue SERIAL,
    country SERIAL,
    name TEXT,
    short_name TEXT,
    short_code TEXT,
    status TEXT,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY (country) REFERENCES countries(id) ON DELETE RESTRICT,
    FOREIGN KEY (season) REFERENCES seasons(id) ON DELETE RESTRICT,
    FOREIGN KEY (venue) REFERENCES venues(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS events_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    season SERIAL NOT NULL,
    venue SERIAL,
    country SERIAL,
    name       TEXT,
    short_name TEXT,
    short_code TEXT,
    status TEXT,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN events_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_events_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE events_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO events_history (id, uuid, season, venue, country, name, short_name, short_code, status, start_date, end_date, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.season, NEW.venue, NEW.country, NEW.name, NEW.short_name, NEW.short_code, NEW.status, NEW.start_date, NEW.end_date, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_events_history
    AFTER INSERT OR UPDATE ON events
    FOR EACH ROW
    EXECUTE FUNCTION update_events_history();
