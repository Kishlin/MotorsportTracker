CREATE TABLE IF NOT EXISTS teams (
    id         SERIAL PRIMARY KEY,
    uuid       UUID UNIQUE NOT NULL,
    name       TEXT,
    colour     TEXT,
    picture    TEXT,
    car_icon    TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS teams_history (
    history_id SERIAL PRIMARY KEY,
    id INT NOT NULL,
    uuid UUID NOT NULL,
    name       TEXT,
    colour     TEXT,
    picture    TEXT,
    car_icon    TEXT,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN teams_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_teams_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE teams_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO teams_history (id, uuid, name, colour, picture, car_icon, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.colour, NEW.picture, NEW.car_icon, NEW.hash, NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_teams_history
    AFTER INSERT OR UPDATE ON teams
    FOR EACH ROW
    EXECUTE FUNCTION update_teams_history();
