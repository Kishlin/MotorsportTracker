CREATE TABLE IF NOT EXISTS seasons (
    uuid UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    external_uuid VARCHAR(255) NOT NULL UNIQUE,
    year INT NOT NULL,
    end_year INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_seasons_external_uuid ON seasons(external_uuid);

COMMENT ON COLUMN seasons.external_uuid IS 'UUID from external motorsport API (reference identifier)';
