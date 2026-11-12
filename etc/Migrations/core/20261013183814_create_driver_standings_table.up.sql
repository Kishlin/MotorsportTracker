-- Rows keep the payload order in payload_index: every read orders by it.
CREATE TABLE IF NOT EXISTS driver_standings (
    id                     SERIAL PRIMARY KEY,
    standing               INTEGER NOT NULL,
    payload_index          INTEGER NOT NULL,
    driver                 INTEGER NOT NULL,
    team_uuid              UUID,
    team_look              INTEGER,
    nationality            INTEGER,
    position               INTEGER NOT NULL,
    points                 DOUBLE PRECISION NOT NULL,
    avg_finish_position    DOUBLE PRECISION NOT NULL,
    class_wins             INTEGER NOT NULL,
    fastest_laps           INTEGER NOT NULL,
    final_appearances      INTEGER NOT NULL,
    hat_tricks             INTEGER NOT NULL,
    podiums                INTEGER NOT NULL,
    poles                  INTEGER NOT NULL,
    races_led              INTEGER NOT NULL,
    rallies_led            INTEGER NOT NULL,
    retirements            INTEGER NOT NULL,
    semi_final_appearances INTEGER NOT NULL,
    stage_wins             INTEGER NOT NULL,
    starts                 INTEGER NOT NULL,
    top_10s                INTEGER NOT NULL,
    top_5s                 INTEGER NOT NULL,
    wins                   INTEGER NOT NULL,
    wins_percentage        DOUBLE PRECISION NOT NULL,
    hash                   TEXT UNIQUE NOT NULL,
    created_at             TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at             TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (standing, payload_index),
    FOREIGN KEY (standing) REFERENCES standings(id) ON DELETE CASCADE,
    FOREIGN KEY (driver) REFERENCES drivers(id) ON DELETE RESTRICT,
    CHECK ((team_uuid IS NULL) = (team_look IS NULL)),
    FOREIGN KEY (team_look) REFERENCES team_looks(id) ON DELETE RESTRICT,
    FOREIGN KEY (nationality) REFERENCES countries(id) ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS driver_standings_driver_idx ON driver_standings (driver);
CREATE INDEX IF NOT EXISTS driver_standings_team_uuid_idx ON driver_standings (team_uuid);
CREATE INDEX IF NOT EXISTS driver_standings_nationality_idx ON driver_standings (nationality);

CREATE TABLE IF NOT EXISTS driver_standings_history (
    history_id             SERIAL PRIMARY KEY,
    id                     INTEGER NOT NULL,
    standing               INTEGER NOT NULL,
    payload_index          INTEGER NOT NULL,
    driver                 INTEGER NOT NULL,
    team_uuid              UUID,
    team_look              INTEGER,
    nationality            INTEGER,
    position               INTEGER NOT NULL,
    points                 DOUBLE PRECISION NOT NULL,
    avg_finish_position    DOUBLE PRECISION NOT NULL,
    class_wins             INTEGER NOT NULL,
    fastest_laps           INTEGER NOT NULL,
    final_appearances      INTEGER NOT NULL,
    hat_tricks             INTEGER NOT NULL,
    podiums                INTEGER NOT NULL,
    poles                  INTEGER NOT NULL,
    races_led              INTEGER NOT NULL,
    rallies_led            INTEGER NOT NULL,
    retirements            INTEGER NOT NULL,
    semi_final_appearances INTEGER NOT NULL,
    stage_wins             INTEGER NOT NULL,
    starts                 INTEGER NOT NULL,
    top_10s                INTEGER NOT NULL,
    top_5s                 INTEGER NOT NULL,
    wins                   INTEGER NOT NULL,
    wins_percentage        DOUBLE PRECISION NOT NULL,
    hash                   TEXT NOT NULL,
    valid_from             TIMESTAMP NOT NULL DEFAULT NOW(),
    valid_to               TIMESTAMP
);

COMMENT ON COLUMN driver_standings_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_driver_standings_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE driver_standings_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO driver_standings_history (id, standing, payload_index, driver, team_uuid, team_look, nationality, position, points, avg_finish_position, class_wins, fastest_laps, final_appearances, hat_tricks, podiums, poles, races_led, rallies_led, retirements, semi_final_appearances, stage_wins, starts, top_10s, top_5s, wins, wins_percentage, hash, valid_from)
    VALUES (NEW.id, NEW.standing, NEW.payload_index, NEW.driver, NEW.team_uuid, NEW.team_look, NEW.nationality, NEW.position, NEW.points, NEW.avg_finish_position, NEW.class_wins, NEW.fastest_laps, NEW.final_appearances, NEW.hat_tricks, NEW.podiums, NEW.poles, NEW.races_led, NEW.rallies_led, NEW.retirements, NEW.semi_final_appearances, NEW.stage_wins, NEW.starts, NEW.top_10s, NEW.top_5s, NEW.wins, NEW.wins_percentage, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_driver_standings_history
    AFTER INSERT OR UPDATE ON driver_standings
    FOR EACH ROW
    EXECUTE FUNCTION update_driver_standings_history();
