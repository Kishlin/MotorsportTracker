CREATE TABLE IF NOT EXISTS garages (
    id         SERIAL PRIMARY KEY,
    unique_key   TEXT UNIQUE NOT NULL,
    name       TEXT,
    colour     TEXT,
    picture    TEXT,
    car_icon    TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS garages_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    unique_key   TEXT NOT NULL,
    name       TEXT,
    colour     TEXT,
    picture    TEXT,
    car_icon    TEXT,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN garages_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_garages_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE garages_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO garages_history (id, unique_key, name, colour, picture, car_icon, hash, valid_from)
    VALUES (NEW.id, NEW.unique_key, NEW.name, NEW.colour, NEW.picture, NEW.car_icon, NEW.hash, NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_garages_history
    AFTER INSERT OR UPDATE ON garages
    FOR EACH ROW
    EXECUTE FUNCTION update_garages_history();
