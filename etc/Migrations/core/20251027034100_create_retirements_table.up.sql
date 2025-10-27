CREATE TABLE IF NOT EXISTS retirements (
    id         SERIAL PRIMARY KEY,
    entry SERIAL UNIQUE NOT NULL,
    driver SERIAL NOT NULL,
    reason TEXT,
    type TEXT,
    dns BOOLEAN,
    lap INT,
    details TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    FOREIGN KEY (entry) REFERENCES entries(id) ON DELETE RESTRICT,
    FOREIGN KEY (driver) REFERENCES drivers(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS retirements_history (
    history_id SERIAL PRIMARY KEY,
    id SERIAL NOT NULL,
    entry SERIAL NOT NULL,
    driver SERIAL NOT NULL,
    reason TEXT,
    type TEXT,
    dns BOOLEAN,
    lap INT,
    details TEXT,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN retirements_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_retirements_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE retirements_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO retirements_history (id, entry, driver, reason, type, dns, lap, details, hash, valid_from)
    VALUES (NEW.id, NEW.entry, NEW.driver, NEW.reason, NEW.type, NEW.dns, NEW.lap, NEW.details, NEW.hash, NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_retirements_history
    AFTER INSERT OR UPDATE ON retirements
    FOR EACH ROW
    EXECUTE FUNCTION update_retirements_history();
