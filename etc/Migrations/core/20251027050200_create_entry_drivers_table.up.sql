CREATE TABLE IF NOT EXISTS entry_drivers (
    id         SERIAL PRIMARY KEY,
    entry SERIAL NOT NULL,
    driver SERIAL NOT NULL,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    UNIQUE(entry, driver),
    FOREIGN KEY (entry) REFERENCES entries(id) ON DELETE RESTRICT,
    FOREIGN KEY (driver) REFERENCES drivers(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS entry_drivers_history (
    history_id SERIAL PRIMARY KEY,
    id SERIAL NOT NULL,
    entry SERIAL NOT NULL,
    driver SERIAL NOT NULL,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN entry_drivers_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_entry_drivers_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE entry_drivers_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO entry_drivers_history (id, entry, driver, hash, valid_from)
    VALUES (NEW.id, NEW.entry, NEW.driver, NEW.hash,NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_entry_drivers_history
    AFTER INSERT OR UPDATE ON entry_drivers
    FOR EACH ROW
    EXECUTE FUNCTION update_entry_drivers_history();
