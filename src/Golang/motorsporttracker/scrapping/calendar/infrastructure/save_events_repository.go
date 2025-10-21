package infrastructure

import (
	"context"
	"crypto/md5"
	"fmt"
	"log/slog"
	"time"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type UpsertStats struct {
	Inserted int
	Updated  int
}

type UpsertResult struct {
	ID      int
	Updated bool
}

type SaveCalendarRepository struct {
	db *database.PGXPoolAdapter
}

func NewSaveCalendarRepository(db *database.PGXPoolAdapter) *SaveCalendarRepository {
	return &SaveCalendarRepository{db: db}
}

// SaveCalendar saves events into the database.
func (s *SaveCalendarRepository) SaveCalendar(ctx context.Context, season string, calendar *motorsportstats.Calendar) error {
	if len(calendar.Events) == 0 {
		slog.Info("No events to save")

		return nil
	}

	seasonID, err := s.getSeasonID(ctx, season)
	if err != nil {
		return fmt.Errorf("getting season ID: %w", err)
	}

	uniqueCountries := make([]*motorsportstats.Country, 0)
	countriesUUIDs := make(map[string]struct{})
	uniqueVenues := make([]*motorsportstats.Venue, 0)
	venuesUUIDs := make(map[string]struct{})
	eventsUUIDs := make(map[string]struct{})
	sessionsPerEventUUID := make(map[string][]*motorsportstats.Session)
	sessionsCount := 0

	for _, event := range calendar.Events {
		if event.Country != nil {
			if _, exists := countriesUUIDs[event.Country.UUID]; exists == false {
				uniqueCountries = append(uniqueCountries, event.Country)
				countriesUUIDs[event.Country.UUID] = struct{}{}
			}
		}
		if event.Venue != nil {
			if _, exists := venuesUUIDs[event.Venue.UUID]; exists == false {
				uniqueVenues = append(uniqueVenues, event.Venue)
				venuesUUIDs[event.Venue.UUID] = struct{}{}
			}
		}
		sessionsPerEventUUID[event.UUID] = event.Sessions
		sessionsCount += len(event.Sessions)
		eventsUUIDs[event.UUID] = struct{}{}
	}

	stats, err := s.saveCountries(ctx, uniqueCountries)
	if err != nil {
		return fmt.Errorf("saving uniqueCountries: %w", err)
	}

	slog.Info("Countries upserted successfully", "countries_count", len(uniqueCountries), "inserted", stats.Inserted, "updated", stats.Updated)

	stats, err = s.saveVenues(ctx, uniqueVenues)
	if err != nil {
		return fmt.Errorf("saving uniqueVenues: %w", err)
	}

	slog.Info("Venues upserted successfully", "venues_count", len(uniqueVenues), "inserted", stats.Inserted, "updated", stats.Updated)

	stats, err = s.saveEvents(ctx, calendar.Events, seasonID, venuesUUIDs, countriesUUIDs)
	if err != nil {
		return fmt.Errorf("saving events: %w", err)
	}

	slog.Info("Events upserted successfully", "events_count", len(calendar.Events), "inserted", stats.Inserted, "updated", stats.Updated)

	stats, err = s.saveSessions(ctx, sessionsPerEventUUID, eventsUUIDs)
	if err != nil {
		return fmt.Errorf("saving sessions: %w", err)
	}

	slog.Info("Sessions upserted successfully", "sessions_count", sessionsCount, "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

const seasonIDQuery = "SELECT id FROM seasons WHERE uuid = $1 LIMIT 1;"

func (s *SaveCalendarRepository) getSeasonID(ctx context.Context, season string) (int, error) {
	ret, err := s.db.Query(ctx, seasonIDQuery, season)
	if err != nil {
		return 0, fmt.Errorf("fetching season ID: %w", err)
	}
	defer ret.Close()

	if ret.Next() == false {
		return 0, fmt.Errorf("season with UUID %s not found", season)
	}

	var seasonID int
	err = ret.Scan(&seasonID)
	if err != nil {
		return 0, fmt.Errorf("scanning season ID: %w", err)
	}

	return seasonID, nil
}

func (s *SaveCalendarRepository) saveCountries(ctx context.Context, countries []*motorsportstats.Country) (UpsertStats, error) {
	if len(countries) == 0 {
		return UpsertStats{}, nil
	}

	queryValues := ""
	var args []interface{}
	for i, country := range countries {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*4 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3)
		hash := s.toMD5(fmt.Sprintf("%s|%s", country.Name, country.Flag))
		args = append(args, country.UUID, country.Name, country.Flag, hash)
	}

	stats, err := s.upsertData(ctx, saveCountriesQuery, queryValues, args)
	if err != nil {
		return stats, fmt.Errorf("upserting countries: %w", err)
	}

	return stats, nil
}

func (s *SaveCalendarRepository) saveVenues(ctx context.Context, venues []*motorsportstats.Venue) (UpsertStats, error) {
	if len(venues) == 0 {
		return UpsertStats{}, nil
	}

	queryValues := ""
	var args []interface{}
	for i, venue := range venues {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*5 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4)
		hash := s.toMD5(fmt.Sprintf("%s|%s|%s", venue.Name, venue.ShortName, venue.ShortCode))
		args = append(args, venue.UUID, venue.Name, venue.ShortName, venue.ShortCode, hash)
	}

	stats, err := s.upsertData(ctx, saveVenueQuery, queryValues, args)
	if err != nil {
		return stats, fmt.Errorf("upserting venues: %w", err)
	}

	return stats, nil
}

func (s *SaveCalendarRepository) saveEvents(
	ctx context.Context,
	events []*motorsportstats.Event,
	seasonID int,
	venuesUUIDs map[string]struct{},
	countryUUIDs map[string]struct{},
) (UpsertStats, error) {
	if len(events) == 0 {
		return UpsertStats{}, nil
	}

	venuesIDsPerUUIDs, err := s.getIDsForUUIDs(ctx, "venues", venuesUUIDs)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("getting venue IDs for UUIDs: %w", err)
	}

	countryIDPerUUID, err := s.getIDsForUUIDs(ctx, "countries", countryUUIDs)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("getting country IDs for UUIDs: %w", err)
	}

	queryValues := ""
	var args []interface{}
	for i, event := range events {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*11 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4, argPosition+5, argPosition+6, argPosition+7, argPosition+8, argPosition+9, argPosition+10)

		if event.Venue == nil {
			return UpsertStats{}, fmt.Errorf("event %s has no venue", event.UUID)
		}
		venueID, ok := venuesIDsPerUUIDs[event.Venue.UUID]
		if ok == false {
			return UpsertStats{}, fmt.Errorf("venue UUID %s not found in saved venues", event.Venue.UUID)
		}

		if event.Country == nil {
			return UpsertStats{}, fmt.Errorf("event %s has no country", event.UUID)
		}
		countryID, ok := countryIDPerUUID[event.Country.UUID]
		if ok == false {
			return UpsertStats{}, fmt.Errorf("country UUID %s not found in saved countries", event.Country.UUID)
		}

		startDate := time.Unix(event.StartDate, 0)
		endDate := time.Unix(event.EndDate, 0)

		hash := s.toMD5(fmt.Sprintf("%v|%v|%s|%s|%s|%s|%d|%d", venueID, countryID, event.Name, event.ShortName, event.ShortCode, event.Status, event.StartDate, event.EndDate))
		args = append(args, event.UUID, seasonID, venueID, countryID, event.Name, event.ShortName, event.ShortCode, event.Status, startDate, endDate, hash)
	}

	stats, err := s.upsertData(ctx, saveEventQuery, queryValues, args)
	if err != nil {
		return stats, fmt.Errorf("upserting events: %w", err)
	}

	return stats, nil
}

func (s *SaveCalendarRepository) saveSessions(
	ctx context.Context,
	sessionsPerEventUUID map[string][]*motorsportstats.Session,
	eventsUUIDs map[string]struct{},
) (UpsertStats, error) {
	eventsIDsPerUUIDs, err := s.getIDsForUUIDs(ctx, "events", eventsUUIDs)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("getting event IDs for UUIDs: %w", err)
	}

	queryValues := ""
	var args []interface{}
	i := 0
	for eventUUID, session := range sessionsPerEventUUID {
		eventID, ok := eventsIDsPerUUIDs[eventUUID]
		if ok == false {
			return UpsertStats{}, fmt.Errorf("event UUID %s not found in saved events", eventUUID)
		}

		for _, sess := range session {
			if i > 0 {
				queryValues += ","
			}
			argPosition := i*10 + 1
			queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4, argPosition+5, argPosition+6, argPosition+7, argPosition+8, argPosition+9)

			startTime := time.Unix(sess.StartTime, 0)

			var endTime *time.Time
			if sess.EndTime != nil {
				t := time.Unix(*sess.EndTime, 0)
				endTime = &t
			}

			hash := s.toMD5(fmt.Sprintf("%d|%s|%s|%s|%s|%t|%d|%v", eventID, sess.Name, sess.ShortName, sess.ShortCode, sess.Status, sess.HasResults, sess.StartTime, sess.EndTime))
			args = append(args, sess.UUID, eventID, sess.Name, sess.ShortName, sess.ShortCode, sess.Status, sess.HasResults, startTime, endTime, hash)
			i++
		}
	}

	if i == 0 {
		return UpsertStats{}, nil
	}

	stats, err := s.upsertData(ctx, saveSessionQuery, queryValues, args)
	if err != nil {
		return stats, fmt.Errorf("upserting sessions: %w", err)
	}

	return stats, nil
}

func (s *SaveCalendarRepository) upsertData(ctx context.Context, queryTemplate string, queryValues string, args []interface{}) (UpsertStats, error) {
	stats := UpsertStats{
		Inserted: 0,
		Updated:  0,
	}

	finalQuery := fmt.Sprintf(queryTemplate, queryValues)

	ret, err := s.db.Query(ctx, finalQuery, args...)
	if err != nil {
		return stats, fmt.Errorf("executing save data query: %w", err)
	}

	for ret.Next() {
		var res UpsertResult
		if err = ret.Scan(&res.ID, &res.Updated); err != nil {
			return stats, fmt.Errorf("scanning upsert result: %w", err)
		}

		if res.Updated {
			stats.Updated++
		} else {
			stats.Inserted++
		}
	}

	ret.Close()

	err = ret.Err()
	if err != nil {
		return stats, fmt.Errorf("after iterating upsert results: %w", err)
	}

	return stats, nil
}

func (s *SaveCalendarRepository) getIDsForUUIDs(ctx context.Context, table string, uuids map[string]struct{}) (map[string]int, error) {
	if len(uuids) == 0 {
		return make(map[string]int), nil
	}

	queryValues := ""
	var args []interface{}
	i := 0
	for uuid := range uuids {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i + 1
		queryValues += fmt.Sprintf(" $%d", argPosition)
		args = append(args, uuid)
		i++
	}

	finalQuery := fmt.Sprintf("SELECT uuid, id FROM %s WHERE uuid IN (%s);", table, queryValues)

	idPerUUID := make(map[string]int)

	ret, err := s.db.Query(ctx, finalQuery, args...)
	if err != nil {
		return idPerUUID, fmt.Errorf("executing get IDs for UUIDs query: %w", err)
	}
	defer ret.Close()

	for ret.Next() {
		var id int
		var uuid string
		if err = ret.Scan(&uuid, &id); err != nil {
			return idPerUUID, fmt.Errorf("scanning ID for UUID: %w", err)
		}
		idPerUUID[uuid] = id
	}

	return idPerUUID, nil
}

func (s *SaveCalendarRepository) toMD5(str string) string {
	h := md5.New()
	h.Write([]byte(str))
	return fmt.Sprintf("%x", h.Sum(nil))
}

const saveCountriesQuery = `
INSERT INTO countries (uuid, name, flag, hash)
VALUES %s
ON CONFLICT (uuid) DO UPDATE SET
  name = EXCLUDED.name,
  flag = EXCLUDED.flag,
  hash = EXCLUDED.hash,
  updated_at = NOW()
WHERE countries.hash IS DISTINCT FROM EXCLUDED.hash
RETURNING
  id,
  CASE WHEN xmax = 0 THEN false ELSE true END as updated;
`

const saveVenueQuery = `
INSERT INTO venues (uuid, name, short_name, short_code, hash)
VALUES %s
ON CONFLICT (uuid) DO UPDATE SET
  name = EXCLUDED.name,
  short_name = EXCLUDED.short_name,
  short_code = EXCLUDED.short_code,
  hash = EXCLUDED.hash,
  updated_at = NOW()
WHERE venues.hash IS DISTINCT FROM EXCLUDED.hash
RETURNING
  id,
  CASE WHEN xmax = 0 THEN false ELSE true END as updated;
`

const saveEventQuery = `
INSERT INTO events (uuid, season, venue, country, name, short_name, short_code, status, start_date, end_date, hash)
VALUES %s
ON CONFLICT (uuid) DO UPDATE SET
  venue = EXCLUDED.venue,
  country = EXCLUDED.country,
  name = EXCLUDED.name,
  short_name = EXCLUDED.short_name,
  short_code = EXCLUDED.short_code,
  status = EXCLUDED.status,
  start_date = EXCLUDED.start_date,
  end_date = EXCLUDED.end_date,
  hash = EXCLUDED.hash,
  updated_at = NOW()
WHERE events.hash IS DISTINCT FROM EXCLUDED.hash
RETURNING
  id,
  CASE WHEN xmax = 0 THEN false ELSE true END as updated;
`

const saveSessionQuery = `
INSERT INTO sessions (uuid, event, name, short_name, short_code, status, has_results, start_time, end_time, hash)
VALUES %s
ON CONFLICT (uuid) DO UPDATE SET
  name = EXCLUDED.name,
  short_name = EXCLUDED.short_name,
  short_code = EXCLUDED.short_code,
  status = EXCLUDED.status,
  has_results = EXCLUDED.has_results,
  start_time = EXCLUDED.start_time,
  end_time = EXCLUDED.end_time,
  hash = EXCLUDED.hash,
  updated_at = NOW()
WHERE sessions.hash IS DISTINCT FROM EXCLUDED.hash
RETURNING
  id,
  CASE WHEN xmax = 0 THEN false ELSE true END as updated;
`
