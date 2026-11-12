-- A team's look belongs to an entry, not to the team: one team UUID is sent with different names,
-- colours and icons, even within one event. One row per distinct content as motorsportstats sent it,
-- keyed by a hash of every field. Insert-only: a different look is a new row, so a row never changes,
-- and has no history and no updated_at.
CREATE TABLE IF NOT EXISTS team_looks (
    id         SERIAL PRIMARY KEY,
    uuid       UUID NOT NULL,
    name       TEXT,
    colour     TEXT,
    badge      TEXT,
    car_icon   TEXT,
    photo      TEXT,
    hash       TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS team_looks_uuid_idx ON team_looks (uuid);
