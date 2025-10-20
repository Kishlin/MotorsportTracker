CREATE TABLE IF NOT EXISTS calendar (
    uuid       UUID PRIMARY KEY      DEFAULT gen_random_uuid(),
    key        VARCHAR(255) NOT NULL UNIQUE,
    value      TEXT         NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP    NOT NULL DEFAULT NOW()
);
