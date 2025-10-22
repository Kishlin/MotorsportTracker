CREATE TABLE IF NOT EXISTS series (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    name TEXT,
    short_name TEXT,
    short_code TEXT,
    category TEXT,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS series_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    name TEXT,
    short_name TEXT,
    short_code TEXT,
    category TEXT,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN series_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_series_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE series_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO series_history (id, uuid, name, short_name, short_code, category, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.short_name, NEW.short_code, NEW.category, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_series_history
    AFTER INSERT OR UPDATE ON series
    FOR EACH ROW
    EXECUTE FUNCTION update_series_history();
