CREATE TABLE IF NOT EXISTS entries (
    id         SERIAL PRIMARY KEY,
    session SERIAL NOT NULL,
    team SERIAL NOT NULL,
    garage SERIAL NOT NULL,
    car_number TEXT NOT NULL,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    UNIQUE(session, car_number),
    FOREIGN KEY (session) REFERENCES sessions(id) ON DELETE RESTRICT,
    FOREIGN KEY (team) REFERENCES teams(id) ON DELETE RESTRICT,
    FOREIGN KEY (garage) REFERENCES garages(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS entries_history (
    history_id SERIAL PRIMARY KEY,
    id SERIAL NOT NULL,
    session SERIAL NOT NULL,
    team SERIAL NOT NULL,
    garage SERIAL NOT NULL,
    car_number TEXT NOT NULL,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN entries_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_entries_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE entries_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO entries_history (id, session, team, garage, car_number, hash, valid_from)
    VALUES (NEW.id, NEW.session, NEW.team, NEW.garage, NEW.car_number, NEW.hash, NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_entries_history
    AFTER INSERT OR UPDATE ON entries
    FOR EACH ROW
    EXECUTE FUNCTION update_entries_history();
