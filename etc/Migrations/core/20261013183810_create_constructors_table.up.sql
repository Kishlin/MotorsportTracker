CREATE TABLE IF NOT EXISTS constructors (
    id         SERIAL PRIMARY KEY,
    uuid       UUID UNIQUE NOT NULL,
    name       TEXT,
    logo       TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS constructors_history (
    history_id SERIAL PRIMARY KEY,
    id         INTEGER NOT NULL,
    uuid       UUID NOT NULL,
    name       TEXT,
    logo       TEXT,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN constructors_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_constructors_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE constructors_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO constructors_history (id, uuid, name, logo, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.logo, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_constructors_history
    AFTER INSERT OR UPDATE ON constructors
    FOR EACH ROW
    EXECUTE FUNCTION update_constructors_history();
