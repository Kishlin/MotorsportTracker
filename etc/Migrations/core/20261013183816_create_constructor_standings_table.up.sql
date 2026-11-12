-- Rows keep the payload order in payload_index: every read orders by it.
CREATE TABLE IF NOT EXISTS constructor_standings (
    id            SERIAL PRIMARY KEY,
    standing      INTEGER NOT NULL,
    payload_index INTEGER NOT NULL,
    constructor   INTEGER NOT NULL,
    team_uuid     UUID,
    team_look     INTEGER,
    country       INTEGER,
    position      INTEGER NOT NULL,
    points        DOUBLE PRECISION NOT NULL,
    wins          INTEGER NOT NULL,
    hash          TEXT UNIQUE NOT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (standing, payload_index),
    FOREIGN KEY (standing) REFERENCES standings(id) ON DELETE CASCADE,
    FOREIGN KEY (constructor) REFERENCES constructors(id) ON DELETE RESTRICT,
    CHECK ((team_uuid IS NULL) = (team_look IS NULL)),
    FOREIGN KEY (team_look) REFERENCES team_looks(id) ON DELETE RESTRICT,
    FOREIGN KEY (country) REFERENCES countries(id) ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS constructor_standings_constructor_idx ON constructor_standings (constructor);
CREATE INDEX IF NOT EXISTS constructor_standings_team_uuid_idx ON constructor_standings (team_uuid);
CREATE INDEX IF NOT EXISTS constructor_standings_country_idx ON constructor_standings (country);

CREATE TABLE IF NOT EXISTS constructor_standings_history (
    history_id    SERIAL PRIMARY KEY,
    id            INTEGER NOT NULL,
    standing      INTEGER NOT NULL,
    payload_index INTEGER NOT NULL,
    constructor   INTEGER NOT NULL,
    team_uuid     UUID,
    team_look     INTEGER,
    country       INTEGER,
    position      INTEGER NOT NULL,
    points        DOUBLE PRECISION NOT NULL,
    wins          INTEGER NOT NULL,
    hash          TEXT NOT NULL,
    valid_from    TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to      TIMESTAMP
);

COMMENT ON COLUMN constructor_standings_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_constructor_standings_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE constructor_standings_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO constructor_standings_history (id, standing, payload_index, constructor, team_uuid, team_look, country, position, points, wins, hash, valid_from)
    VALUES (NEW.id, NEW.standing, NEW.payload_index, NEW.constructor, NEW.team_uuid, NEW.team_look, NEW.country, NEW.position, NEW.points, NEW.wins, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_constructor_standings_history
    AFTER INSERT OR UPDATE ON constructor_standings
    FOR EACH ROW
    EXECUTE FUNCTION update_constructor_standings_history();
