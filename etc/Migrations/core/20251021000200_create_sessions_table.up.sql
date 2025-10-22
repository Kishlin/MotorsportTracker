CREATE TABLE IF NOT EXISTS sessions (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    event SERIAL NOT NULL,
    name TEXT,
    short_name TEXT,
    short_code TEXT,
    status TEXT,
    has_results BOOLEAN,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY (event) REFERENCES events(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS sessions_history (
    history_id SERIAL PRIMARY KEY,
    id         INT          NOT NULL,
    uuid       UUID         NOT NULL,
    event SERIAL NOT NULL,
    name       TEXT,
    short_name TEXT,
    short_code TEXT,
    status TEXT,
    has_results BOOLEAN,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    hash       TEXT         NOT NULL,
    valid_from TIMESTAMP    NOT NULL DEFAULT NOW(),
    valid_to   TIMESTAMP
);

COMMENT ON COLUMN sessions_history.valid_to IS 'NULL = current version';

CREATE OR REPLACE FUNCTION update_sessions_history()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'UPDATE') THEN
        UPDATE sessions_history
        SET valid_to = NOW()
        WHERE id = OLD.id AND valid_to IS NULL;
    END IF;

    INSERT INTO sessions_history (id, uuid, event, name, short_name, short_code, status, has_results, start_time, end_time, hash, valid_from)
    VALUES (NEW.id, NEW.uuid, NEW.event, NEW.name, NEW.short_name, NEW.short_code, NEW.status, NEW.has_results, NEW.start_time, NEW.end_time, NEW.hash, NOW());

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_sessions_history
    AFTER INSERT OR UPDATE ON sessions
    FOR EACH ROW
    EXECUTE FUNCTION update_sessions_history();
