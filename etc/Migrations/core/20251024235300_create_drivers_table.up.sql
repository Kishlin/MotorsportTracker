CREATE TABLE IF NOT EXISTS drivers (
    id         SERIAL PRIMARY KEY,
    uuid       UUID UNIQUE NOT NULL,
    name       TEXT,
    first_name TEXT,
    last_name  TEXT,
    short_code TEXT,
    colour     TEXT,
    picture    TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS drivers_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    uuid UUID NOT NULL,
    name       TEXT,
    first_name TEXT,
    last_name  TEXT,
    short_code TEXT,
    colour     TEXT,
    picture    TEXT,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN drivers_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_drivers_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE drivers_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO drivers_history (id, uuid, name, first_name, last_name, short_code, colour, picture, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.first_name, NEW.last_name, NEW.short_code, NEW.colour, NEW.picture, NEW.hash, NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_drivers_history
    AFTER INSERT OR UPDATE ON drivers
    FOR EACH ROW
    EXECUTE FUNCTION update_drivers_history();
