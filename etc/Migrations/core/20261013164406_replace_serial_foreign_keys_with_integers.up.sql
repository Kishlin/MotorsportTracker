-- Foreign key columns were declared SERIAL, which is not a type but INTEGER NOT NULL DEFAULT nextval(...).
-- That made events.venue and events.country NOT NULL, though an event can come without a country,
-- and gave every foreign key a sequence an insert would silently draw from if it left the column out.
-- Every one becomes a plain INTEGER, in the main table and its history table alike.

ALTER TABLE seasons ALTER COLUMN series DROP DEFAULT;
DROP SEQUENCE seasons_series_seq;
ALTER TABLE seasons_history ALTER COLUMN series DROP DEFAULT;
DROP SEQUENCE seasons_history_series_seq;

ALTER TABLE events ALTER COLUMN season DROP DEFAULT;
DROP SEQUENCE events_season_seq;
ALTER TABLE events_history ALTER COLUMN season DROP DEFAULT;
DROP SEQUENCE events_history_season_seq;

ALTER TABLE events ALTER COLUMN venue DROP DEFAULT;
ALTER TABLE events ALTER COLUMN venue DROP NOT NULL;
DROP SEQUENCE events_venue_seq;
ALTER TABLE events_history ALTER COLUMN venue DROP DEFAULT;
ALTER TABLE events_history ALTER COLUMN venue DROP NOT NULL;
DROP SEQUENCE events_history_venue_seq;

ALTER TABLE events ALTER COLUMN country DROP DEFAULT;
ALTER TABLE events ALTER COLUMN country DROP NOT NULL;
DROP SEQUENCE events_country_seq;
ALTER TABLE events_history ALTER COLUMN country DROP DEFAULT;
ALTER TABLE events_history ALTER COLUMN country DROP NOT NULL;
DROP SEQUENCE events_history_country_seq;

ALTER TABLE sessions ALTER COLUMN event DROP DEFAULT;
DROP SEQUENCE sessions_event_seq;
ALTER TABLE sessions_history ALTER COLUMN event DROP DEFAULT;
DROP SEQUENCE sessions_history_event_seq;

ALTER TABLE retirements ALTER COLUMN session DROP DEFAULT;
DROP SEQUENCE retirements_session_seq;
ALTER TABLE retirements_history ALTER COLUMN session DROP DEFAULT;
DROP SEQUENCE retirements_history_session_seq;

ALTER TABLE retirements ALTER COLUMN driver DROP DEFAULT;
DROP SEQUENCE retirements_driver_seq;
ALTER TABLE retirements_history ALTER COLUMN driver DROP DEFAULT;
DROP SEQUENCE retirements_history_driver_seq;

ALTER TABLE classifications ALTER COLUMN session DROP DEFAULT;
DROP SEQUENCE classifications_session_seq;
ALTER TABLE classifications_history ALTER COLUMN session DROP DEFAULT;
DROP SEQUENCE classifications_history_session_seq;

ALTER TABLE classifications ALTER COLUMN team DROP DEFAULT;
DROP SEQUENCE classifications_team_seq;
ALTER TABLE classifications_history ALTER COLUMN team DROP DEFAULT;
DROP SEQUENCE classifications_history_team_seq;

ALTER TABLE classifications ALTER COLUMN garage DROP DEFAULT;
DROP SEQUENCE classifications_garage_seq;
ALTER TABLE classifications_history ALTER COLUMN garage DROP DEFAULT;
DROP SEQUENCE classifications_history_garage_seq;

ALTER TABLE classification_drivers ALTER COLUMN classification DROP DEFAULT;
DROP SEQUENCE classification_drivers_classification_seq;
ALTER TABLE classification_drivers_history ALTER COLUMN classification DROP DEFAULT;
DROP SEQUENCE classification_drivers_history_classification_seq;

ALTER TABLE classification_drivers ALTER COLUMN driver DROP DEFAULT;
DROP SEQUENCE classification_drivers_driver_seq;
ALTER TABLE classification_drivers_history ALTER COLUMN driver DROP DEFAULT;
DROP SEQUENCE classification_drivers_history_driver_seq;
