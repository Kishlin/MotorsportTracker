-- Every source must write the same driver columns, or each would overwrite the others' with NULL.
-- Standings send no first or last name, and the full name holds them.
CREATE OR REPLACE FUNCTION update_drivers_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE drivers_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO drivers_history (id, uuid, name, short_code, colour, picture, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.name, NEW.short_code, NEW.colour, NEW.picture, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

ALTER TABLE drivers DROP COLUMN first_name, DROP COLUMN last_name;

ALTER TABLE drivers_history DROP COLUMN first_name, DROP COLUMN last_name;
