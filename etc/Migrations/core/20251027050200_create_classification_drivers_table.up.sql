CREATE TABLE IF NOT EXISTS classification_drivers (
    id         SERIAL PRIMARY KEY,
    classification SERIAL NOT NULL,
    driver SERIAL NOT NULL,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP   NOT NULL DEFAULT NOW(),
    UNIQUE(classification, driver),
    FOREIGN KEY (classification) REFERENCES classifications(id) ON DELETE RESTRICT,
    FOREIGN KEY (driver) REFERENCES drivers(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS classification_drivers_history (
    history_id SERIAL PRIMARY KEY,
    id SERIAL NOT NULL,
    classification SERIAL NOT NULL,
    driver SERIAL NOT NULL,
    hash       TEXT NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN classification_drivers_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_classification_drivers_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE classification_drivers_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO classification_drivers_history (id, classification, driver, hash, valid_from)
    VALUES (NEW.id, NEW.classification, NEW.driver, NEW.hash,NOW());

    RETURN NEW;
END;
$$ language plpgsql;

CREATE TRIGGER trg_update_classification_drivers_history
    AFTER INSERT OR UPDATE ON classification_drivers
    FOR EACH ROW
    EXECUTE FUNCTION update_classification_drivers_history();
