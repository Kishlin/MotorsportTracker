CREATE TABLE IF NOT EXISTS countries (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    name TEXT NOT NULL,
    flag TEXT NOT NULL,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS countries_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    name       TEXT NOT NULL,
    flag       TEXT NOT NULL,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN countries_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_countries_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE countries_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO countries_history (id, uuid, name, flag, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.flag, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_countries_history
    AFTER INSERT OR UPDATE ON countries
    FOR EACH ROW
    EXECUTE FUNCTION update_countries_history();
