package infrastructure

import (
	"context"
	"crypto/md5"
	"fmt"
	"log/slog"
	"time"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

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

	err = s.saveCountries(ctx, uniqueCountries)
	if err != nil {
		return fmt.Errorf("saving uniqueCountries: %w", err)
	}

	err = s.saveVenues(ctx, uniqueVenues)
	if err != nil {
		return fmt.Errorf("saving uniqueVenues: %w", err)
	}

	err = s.saveEvents(ctx, calendar.Events, seasonID, venuesUUIDs, countriesUUIDs)
	if err != nil {
		return fmt.Errorf("saving events: %w", err)
	}

	err = s.saveSessions(ctx, sessionsPerEventUUID, eventsUUIDs)
	if err != nil {
		return fmt.Errorf("saving sessions: %w", err)
	}

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

func (s *SaveCalendarRepository) saveCountries(ctx context.Context, countries []*motorsportstats.Country) error {
	if len(countries) == 0 {
		slog.Debug("No countries to save")

		return nil
	}

	var rows [][]interface{}
	for _, country := range countries {
		hash := s.toMD5(fmt.Sprintf("%s|%s", country.Name, country.Flag))
		rows = append(rows, []interface{}{country.UUID, country.Name, country.Flag, hash})
	}

	cols := []string{"uuid", "name", "flag", "hash"}

	stats, err := shared.Save(ctx, s.db, "countries", cols, rows)
	if err != nil {
		return fmt.Errorf("saving countries: %w", err)
	}

	slog.Info("Countries saved successfully", "count", len(countries), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

func (s *SaveCalendarRepository) saveVenues(ctx context.Context, venues []*motorsportstats.Venue) error {
	if len(venues) == 0 {
		slog.Debug("No venues to save")

		return nil
	}

	var rows [][]interface{}
	for _, venue := range venues {
		hash := s.toMD5(fmt.Sprintf("%s|%s|%s", venue.Name, venue.ShortName, venue.ShortCode))
		rows = append(rows, []interface{}{venue.UUID, venue.Name, venue.ShortName, venue.ShortCode, hash})
	}

	cols := []string{"uuid", "name", "short_name", "short_code", "hash"}

	stats, err := shared.Save(ctx, s.db, "venues", cols, rows)
	if err != nil {
		return fmt.Errorf("saving venues: %w", err)
	}

	slog.Info("Venues saved successfully", "count", len(venues), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

func (s *SaveCalendarRepository) saveEvents(
	ctx context.Context,
	events []*motorsportstats.Event,
	seasonID int,
	venuesUUIDs map[string]struct{},
	countryUUIDs map[string]struct{},
) error {
	if len(events) == 0 {
		slog.Debug("No events to save")

		return nil
	}

	venuesIDsPerUUIDs, err := shared.GetIDsForUUIDs(ctx, s.db, "venues", venuesUUIDs)
	if err != nil {
		return fmt.Errorf("getting venue IDs for UUIDs: %w", err)
	}

	countryIDPerUUID, err := shared.GetIDsForUUIDs(ctx, s.db, "countries", countryUUIDs)
	if err != nil {
		return fmt.Errorf("getting country IDs for UUIDs: %w", err)
	}

	var rows [][]interface{}
	for _, event := range events {
		if event.Venue == nil {
			return fmt.Errorf("event %s has no venue", event.UUID)
		}
		venueID, ok := venuesIDsPerUUIDs[event.Venue.UUID]
		if ok == false {
			return fmt.Errorf("venue UUID %s not found in saved venues", event.Venue.UUID)
		}

		if event.Country == nil {
			return fmt.Errorf("event %s has no country", event.UUID)
		}
		countryID, ok := countryIDPerUUID[event.Country.UUID]
		if ok == false {
			return fmt.Errorf("country UUID %s not found in saved countries", event.Country.UUID)
		}

		startDate := time.Unix(event.StartDate, 0)
		endDate := time.Unix(event.EndDate, 0)

		hash := s.toMD5(fmt.Sprintf("%v|%v|%s|%s|%s|%s|%d|%d", venueID, countryID, event.Name, event.ShortName, event.ShortCode, event.Status, event.StartDate, event.EndDate))
		rows = append(rows, []interface{}{event.UUID, seasonID, venueID, countryID, event.Name, event.ShortName, event.ShortCode, event.Status, startDate, endDate, hash})
	}

	cols := []string{"uuid", "season", "venue", "country", "name", "short_name", "short_code", "status", "start_date", "end_date", "hash"}

	stats, err := shared.Save(ctx, s.db, "events", cols, rows)
	if err != nil {
		return fmt.Errorf("saving events: %w", err)
	}

	slog.Info("Events saved successfully", "count", len(rows), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

func (s *SaveCalendarRepository) saveSessions(
	ctx context.Context,
	sessionsPerEventUUID map[string][]*motorsportstats.Session,
	eventsUUIDs map[string]struct{},
) error {
	eventsIDsPerUUIDs, err := shared.GetIDsForUUIDs(ctx, s.db, "events", eventsUUIDs)
	if err != nil {
		return fmt.Errorf("getting event IDs for UUIDs: %w", err)
	}

	var rows [][]interface{}
	for eventUUID, session := range sessionsPerEventUUID {
		eventID, ok := eventsIDsPerUUIDs[eventUUID]
		if ok == false {
			return fmt.Errorf("event UUID %s not found in saved events", eventUUID)
		}

		for _, sess := range session {
			startTime := time.Unix(sess.StartTime, 0)

			var endTime *time.Time
			endTimeForHash := int64(0)
			if sess.EndTime != nil {
				endTimeForHash = *sess.EndTime
				t := time.Unix(*sess.EndTime, 0)
				endTime = &t
			}

			hash := s.toMD5(fmt.Sprintf("%d|%s|%s|%s|%s|%t|%d|%v", eventID, sess.Name, sess.ShortName, sess.ShortCode, sess.Status, sess.HasResults, sess.StartTime, endTimeForHash))
			rows = append(rows, []interface{}{sess.UUID, eventID, sess.Name, sess.ShortName, sess.ShortCode, sess.Status, sess.HasResults, startTime, endTime, hash})
		}
	}

	if len(rows) == 0 {
		slog.Debug("No sessions to save")

		return nil
	}

	cols := []string{"uuid", "event", "name", "short_name", "short_code", "status", "has_results", "start_time", "end_time", "hash"}

	stats, err := shared.Save(ctx, s.db, "sessions", cols, rows)
	if err != nil {
		return fmt.Errorf("saving sessions: %w", err)
	}

	slog.Info("Sessions saved successfully", "count", len(rows), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

func (s *SaveCalendarRepository) toMD5(str string) string {
	h := md5.New()
	h.Write([]byte(str))
	return fmt.Sprintf("%x", h.Sum(nil))
}
