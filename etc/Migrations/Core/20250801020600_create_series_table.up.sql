CREATE TABLE IF NOT EXISTS series (
    uuid UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    external_uuid VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    short_name VARCHAR(255),
    short_code VARCHAR(50) NOT NULL,
    category VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_series_external_uuid ON series(external_uuid);

COMMENT ON COLUMN series.external_uuid IS 'UUID from external motorsport API (reference identifier)';
