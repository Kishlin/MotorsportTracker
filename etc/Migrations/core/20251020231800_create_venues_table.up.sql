CREATE TABLE IF NOT EXISTS venues (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    name TEXT,
    short_name TEXT,
    short_code TEXT,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS venues_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    name       TEXT,
    short_name TEXT,
    short_code TEXT,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN venues_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_venues_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE venues_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO venues_history (id, uuid, name, short_name, short_code, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.short_name, NEW.short_code, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_venues_history
    AFTER INSERT OR UPDATE ON venues
    FOR EACH ROW
    EXECUTE FUNCTION update_venues_history();
