CREATE TABLE IF NOT EXISTS seasons (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    series SERIAL NOT NULL,
    name TEXT NOT NULL,
    year INT NOT NULL,
    end_year INT NOT NULL,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY (series) REFERENCES series(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS seasons_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    series SERIAL NOT NULL,
    name       TEXT NOT NULL,
    year INT NOT NULL,
    end_year INT NOT NULL,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN seasons_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_seasons_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE seasons_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO seasons_history (id, uuid, series, name, year, end_year, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.series, NEW.name, NEW.year, NEW.end_year, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_seasons_history
    AFTER INSERT OR UPDATE ON seasons
    FOR EACH ROW
    EXECUTE FUNCTION update_seasons_history();
