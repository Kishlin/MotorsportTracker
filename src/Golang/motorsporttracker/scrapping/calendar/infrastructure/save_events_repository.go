package infrastructure

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	crypto "github.com/kishlin/MotorsportTracker/src/Golang/shared/crypto/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
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
		nameVal := fn.Deref(country.Name, "")
		flagVal := fn.Deref(country.Flag, "")

		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s", country.UUID, nameVal, flagVal))
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
		nameVal := fn.Deref(venue.Name, "")
		shortNameVal := fn.Deref(venue.ShortName, "")
		shortCodeVal := fn.Deref(venue.ShortCode, "")

		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s|%s", venue.UUID, nameVal, shortNameVal, shortCodeVal))
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
		var venueID, countryID *int = nil, nil
		if event.Venue != nil {
			storedVenueID, ok := venuesIDsPerUUIDs[event.Venue.UUID]
			if ok == false {
				return fmt.Errorf("venue UUID %s not found in saved venues", event.Venue.UUID)
			}
			venueID = &storedVenueID
		}
		if event.Country != nil {
			storedCountryID, ok := countryIDPerUUID[event.Country.UUID]
			if ok == false {
				return fmt.Errorf("country UUID %s not found in saved country IDs", event.Country.UUID)
			}
			countryID = &storedCountryID
		}

		venueIDVal := fn.Deref(venueID, 0)
		countryIDVal := fn.Deref(countryID, 0)
		nameVal := fn.Deref(event.Venue.Name, "")
		shortNameVal := fn.Deref(event.Venue.ShortName, "")
		shortCodeVal := fn.Deref(event.Venue.ShortCode, "")
		statusVal := fn.Deref(event.Status, "")
		startDateDBVal, startDateHashVal := shared.PrepareTimestamp(event.StartDate)
		endDateDBVal, endDateHashVal := shared.PrepareTimestamp(event.EndDate)

		hash := crypto.Hash(fmt.Sprintf("%s|%v|%v|%s|%s|%s|%s|%d|%d", event.UUID, venueIDVal, countryIDVal, nameVal, shortNameVal, shortCodeVal, statusVal, startDateHashVal, endDateHashVal))
		rows = append(rows, []interface{}{event.UUID, seasonID, venueID, countryID, event.Name, event.ShortName, event.ShortCode, event.Status, startDateDBVal, endDateDBVal, hash})
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
	for eventUUID, sessions := range sessionsPerEventUUID {
		eventID, ok := eventsIDsPerUUIDs[eventUUID]
		if ok == false {
			return fmt.Errorf("event UUID %s not found in saved events", eventUUID)
		}

		for _, session := range sessions {
			nameVal := fn.Deref(session.Name, "")
			shortNameVal := fn.Deref(session.ShortName, "")
			shortCodeVal := fn.Deref(session.ShortCode, "")
			statusVal := fn.Deref(session.Status, "")
			hasResultVal := fn.Deref(session.HasResults, false)
			startTimeDBVal, startTimeHashVal := shared.PrepareTimestamp(session.StartTime)
			endTimeDBVal, endTimeHashVal := shared.PrepareTimestamp(session.EndTime)

			hash := crypto.Hash(fmt.Sprintf("%s|%d|%s|%s|%s|%s|%t|%d|%v", session.UUID, eventID, nameVal, shortNameVal, shortCodeVal, statusVal, hasResultVal, startTimeHashVal, endTimeHashVal))
			rows = append(rows, []interface{}{session.UUID, eventID, session.Name, session.ShortName, session.ShortCode, session.Status, session.HasResults, startTimeDBVal, endTimeDBVal, hash})
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
