CREATE TABLE IF NOT EXISTS standings_teams (
    uuid       UUID PRIMARY KEY      DEFAULT gen_random_uuid(),
    key        TEXT NOT NULL UNIQUE,
    value      TEXT         NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP    NOT NULL DEFAULT NOW()
);
