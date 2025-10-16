CREATE TABLE IF NOT EXISTS seasons (
    uuid UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    series UUID NOT NULL REFERENCES series(uuid) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    end_year INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);
