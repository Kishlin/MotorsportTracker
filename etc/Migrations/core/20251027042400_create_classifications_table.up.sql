CREATE TABLE IF NOT EXISTS classifications (
    id         SERIAL PRIMARY KEY,
    entry SERIAL UNIQUE NOT NULL,
    finish_position INT,
    grid_position INT,
    laps INT,
    points DOUBLE PRECISION,
    time DOUBLE PRECISION,
    status TEXT,
    avg_lap_speed DOUBLE PRECISION,
    fastest_lap_time DOUBLE PRECISION,
    gap_time_to_lead DOUBLE PRECISION,
    gap_time_to_next DOUBLE PRECISION,
    gap_laps_to_lead INT,
    gap_laps_to_next INT,
    best_lap INT,
    best_time DOUBLE PRECISION,
    best_is_fastest BOOLEAN,
    best_speed DOUBLE PRECISION,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    FOREIGN KEY (entry) REFERENCES entries(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS classifications_history (
    history_id SERIAL PRIMARY KEY,
    id SERIAL NOT NULL,
    entry SERIAL NOT NULL,
    finish_position INT,
    grid_position INT,
    laps INT,
    points DOUBLE PRECISION,
    time DOUBLE PRECISION,
    status TEXT,
    avg_lap_speed DOUBLE PRECISION,
    fastest_lap_time DOUBLE PRECISION,
    gap_time_to_lead DOUBLE PRECISION,
    gap_time_to_next DOUBLE PRECISION,
    gap_laps_to_lead INT,
    gap_laps_to_next INT,
    best_lap INT,
    best_time DOUBLE PRECISION,
    best_is_fastest BOOLEAN,
    best_speed DOUBLE PRECISION,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN classifications_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_classifications_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE classifications_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO classifications_history (
        id,
        entry,
        finish_position,
        grid_position,
        laps,
        points,
        time,
        status,
        avg_lap_speed,
        fastest_lap_time,
        gap_time_to_lead,
        gap_time_to_next,
        gap_laps_to_lead,
        gap_laps_to_next,
        best_lap,
        best_time,
        best_is_fastest,
        best_speed,
        hash,
        valid_from
    ) VALUES (
        NEW.id,
        NEW.entry,
        NEW.finish_position,
        NEW.grid_position,
        NEW.laps,
        NEW.points,
        NEW.time,
        NEW.status,
        NEW.avg_lap_speed,
        NEW.fastest_lap_time,
        NEW.gap_time_to_lead,
        NEW.gap_time_to_next,
        NEW.gap_laps_to_lead,
        NEW.gap_laps_to_next,
        NEW.best_lap,
        NEW.best_time,
        NEW.best_is_fastest,
        NEW.best_speed,
        NEW.hash,
        NOW()
    );

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_classifications_history
    AFTER INSERT OR UPDATE ON classifications
    FOR EACH ROW
    EXECUTE FUNCTION update_classifications_history();
