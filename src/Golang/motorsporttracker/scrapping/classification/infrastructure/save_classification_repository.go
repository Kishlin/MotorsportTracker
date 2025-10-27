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

type Entry struct {
	carNumber, teamUUID string
}

type SaveClassificationRepository struct {
	db *database.PGXPoolAdapter
}

func NewSaveClassificationRepository(db *database.PGXPoolAdapter) *SaveClassificationRepository {
	return &SaveClassificationRepository{db: db}
}

// SaveClassification saves a classification into the database
func (s *SaveClassificationRepository) SaveClassification(ctx context.Context, session string, classification *motorsportstats.Classification) error {
	sessionID, err := s.getSessionID(ctx, session)
	if err != nil {
		return fmt.Errorf("getting session ID: %w", err)
	}

	driverUUIDsPerCarNumbers := make(map[string][]string)
	uniqueDrivers := make([]*motorsportstats.Driver, 0)
	driversUUIDs := make(map[string]struct{})
	uniqueTeams := make([]*motorsportstats.Team, 0)
	teamsUUIDs := make(map[string]struct{})
	uniqueNationalities := make([]*motorsportstats.Country, 0)
	nationalitiesUUIDs := make(map[string]struct{})
	uniqueEntries := make([]*Entry, 0)
	carNumbers := make(map[string]struct{})

	for _, classificationDetails := range classification.Details {
		for _, driver := range classificationDetails.Drivers {
			if _, exists := driversUUIDs[driver.UUID]; exists == false {
				driversUUIDs[driver.UUID] = struct{}{}
				uniqueDrivers = append(uniqueDrivers, driver)
				if _, exists = driverUUIDsPerCarNumbers[classificationDetails.CarNumber]; exists == false {
					driverUUIDsPerCarNumbers[classificationDetails.CarNumber] = make([]string, 0)
				}
				driverUUIDsPerCarNumbers[classificationDetails.CarNumber] = append(
					driverUUIDsPerCarNumbers[classificationDetails.CarNumber], driver.UUID,
				)
			}
		}
		if classificationDetails.Nationality != nil {
			if _, exists := nationalitiesUUIDs[classificationDetails.Nationality.UUID]; exists {
				nationalitiesUUIDs[classificationDetails.Nationality.UUID] = struct{}{}
				uniqueNationalities = append(uniqueNationalities, classificationDetails.Nationality)
			}
		}

		if _, exists := carNumbers[classificationDetails.CarNumber]; exists {
			slog.Warn(
				"Duplicate Entry for car number",
				slog.String("car_number", classificationDetails.CarNumber),
				slog.String("session", session),
			)
			continue
		}
		if classificationDetails.Team == nil {
			slog.Warn(
				"Classification is missing a team",
				slog.String("car_number", classificationDetails.CarNumber),
				slog.String("session", session),
			)
			continue
		}
		if _, exists := teamsUUIDs[classificationDetails.Team.UUID]; exists == false {
			teamsUUIDs[classificationDetails.Team.UUID] = struct{}{}
			uniqueTeams = append(uniqueTeams, classificationDetails.Team)
		}
		uniqueEntries = append(uniqueEntries, &Entry{
			carNumber: classificationDetails.CarNumber,
			teamUUID:  classificationDetails.Team.UUID,
		})
		carNumbers[classificationDetails.CarNumber] = struct{}{}
	}
	for _, retirement := range classification.Retirements {
		if _, exists := carNumbers[retirement.CarNumber]; exists {
			slog.Warn(
				fmt.Sprintf("Retirement for missing car number %s", retirement.CarNumber),
				slog.String("car_number", retirement.CarNumber),
				slog.String("session", session),
			)
			continue
		}
		if retirement.Driver == nil {
			continue
		}
		if _, exists := driversUUIDs[retirement.Driver.UUID]; exists {
			continue
		}
		driversUUIDs[retirement.Driver.UUID] = struct{}{}
		uniqueDrivers = append(uniqueDrivers, retirement.Driver)
	}

	err = shared.SaveCountries(ctx, s.db, uniqueNationalities)
	if err != nil {
		return fmt.Errorf("saving nationalities: %w", err)
	}

	err = s.saveDrivers(ctx, uniqueDrivers)
	if err != nil {
		return fmt.Errorf("saving drivers: %w", err)
	}

	driversIDsPerUUIDs, err := shared.GetIDsForUUIDs(ctx, s.db, "drivers", driversUUIDs)
	if err != nil {
		return fmt.Errorf("getting drivers IDs for UUIDs: %w", err)
	}

	err = s.saveTeams(ctx, uniqueTeams)
	if err != nil {
		return fmt.Errorf("saving teams: %w", err)
	}

	teamsIDsPerUUIDs, err := shared.GetIDsForUUIDs(ctx, s.db, "teams", teamsUUIDs)
	if err != nil {
		return fmt.Errorf("getting teams IDs for UUIDs: %w", err)
	}

	err = s.saveEntries(ctx, uniqueEntries, sessionID, teamsIDsPerUUIDs)
	if err != nil {
		return fmt.Errorf("saving entries: %w", err)
	}

	entryIDsPerCarNumbers, err := s.getEntryIDsForCarNumbers(ctx, sessionID)
	if err != nil {
		return fmt.Errorf("getting entry IDs for car numbers: %w", err)
	}

	err = s.saveEntryDrivers(ctx, driverUUIDsPerCarNumbers, entryIDsPerCarNumbers, driversIDsPerUUIDs)
	if err != nil {
		return fmt.Errorf("saving entry drivers: %w", err)
	}

	err = s.saveRetirements(ctx, classification.Retirements, entryIDsPerCarNumbers, driversIDsPerUUIDs)
	if err != nil {
		return fmt.Errorf("saving retirements: %w", err)
	}

	err = s.saveClassificationDetails(ctx, classification.Details, entryIDsPerCarNumbers)
	if err != nil {
		return fmt.Errorf("saving classification details: %w", err)
	}

	return nil
}

const sessionIDQuery = "SELECT id FROM sessions WHERE uuid = $1 LIMIT 1;"

func (s *SaveClassificationRepository) getSessionID(ctx context.Context, session string) (int, error) {
	rows, err := s.db.Query(ctx, sessionIDQuery, session)
	if err != nil {
		return 0, fmt.Errorf("fetching session ID: %w", err)
	}

	if rows.Next() == false {
		return 0, fmt.Errorf("session with UUID %s not found", session)
	}

	var sessionID int
	err = rows.Scan(&sessionID)
	if err != nil {
		return 0, fmt.Errorf("scanning session ID: %w", err)
	}

	rows.Close()
	if rows.Err() != nil {
		return 0, fmt.Errorf("after closing rows: %w", rows.Err())
	}

	return sessionID, nil
}

func (s *SaveClassificationRepository) saveDrivers(ctx context.Context, drivers []*motorsportstats.Driver) error {
	if len(drivers) == 0 {
		slog.Debug("No drivers to save")

		return nil
	}

	var rows [][]interface{}
	for _, driver := range drivers {
		nameVal := fn.Deref(driver.Name, "")
		firstNameVal := fn.Deref(driver.FirstName, "")
		lastNameVal := fn.Deref(driver.LastName, "")
		shortCodeVal := fn.Deref(driver.ShortCode, "")
		colourVal := fn.Deref(driver.Colour, "")
		pictureVal := fn.Deref(driver.Picture, "")

		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s|%s|%s|%s|%s",
			driver.UUID,
			nameVal,
			firstNameVal,
			lastNameVal,
			shortCodeVal,
			colourVal,
			pictureVal,
		))
		vals := []interface{}{driver.UUID, driver.Name, driver.FirstName, driver.LastName, driver.ShortCode, driver.Colour, driver.Picture, hash}
		rows = append(rows, vals)
	}

	cols := []string{"uuid", "name", "first_name", "last_name", "short_code", "colour", "picture", "hash"}

	stats, err := shared.Save(ctx, s.db, "drivers", "uuid", cols, rows)
	if err != nil {
		return fmt.Errorf("saving drivers: %w", err)
	}

	slog.Info("Drivers saved successfully", "count", len(drivers), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

func (s *SaveClassificationRepository) saveTeams(
	ctx context.Context,
	teams []*motorsportstats.Team,
) error {
	if len(teams) == 0 {
		slog.Debug("No teams to save")

		return nil
	}

	var rows [][]interface{}
	for _, team := range teams {
		nameVal := fn.Deref(team.Name, "")
		colourVal := fn.Deref(team.Colour, "")
		pictureVal := fn.Deref(team.Picture, "")
		carIconVal := fn.Deref(team.CarIcon, "")

		hash := crypto.Hash(fmt.Sprintf(
			"%s|%s|%s|%s|%s",
			team.UUID, nameVal, colourVal, pictureVal, carIconVal,
		))
		vals := []interface{}{team.UUID, team.Name, team.Colour, team.Picture, team.CarIcon, hash}
		rows = append(rows, vals)
	}

	cols := []string{"uuid", "name", "colour", "picture", "car_icon", "hash"}

	stats, err := shared.Save(ctx, s.db, "teams", "uuid", cols, rows)
	if err != nil {
		return fmt.Errorf("saving teams: %w", err)
	}

	slog.Info(
		"Teams saved successfully",
		slog.Int("count", len(teams)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

func (s *SaveClassificationRepository) saveEntries(
	ctx context.Context,
	entries []*Entry,
	sessionID int,
	teamsIDsPerUUIDs map[string]int,
) error {
	if len(entries) == 0 {
		slog.Debug("No entries to save")

		return nil
	}

	var rows [][]interface{}
	for _, entry := range entries {
		teamID, ok := teamsIDsPerUUIDs[entry.teamUUID]
		if ok == false {
			return fmt.Errorf("team ID for UUID %s not found", entry.teamUUID)
		}

		hash := crypto.Hash(fmt.Sprintf("%d|%d|%s", sessionID, teamID, entry.carNumber))
		rows = append(rows, []interface{}{sessionID, teamID, entry.carNumber, hash})
	}

	cols := []string{"session", "team", "car_number", "hash"}

	stats, err := shared.Save(ctx, s.db, "entries", "session, car_number", cols, rows)
	if err != nil {
		return fmt.Errorf("saving entries: %w", err)
	}

	slog.Info(
		"Entries saved successfully",
		slog.Int("count", len(entries)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

const entryIDsForCarNumbersQuery = "SELECT id, car_number FROM entries WHERE session = $1;"

func (s *SaveClassificationRepository) getEntryIDsForCarNumbers(
	ctx context.Context,
	sessionID int,
) (map[string]int, error) {
	idPerCarNumber := make(map[string]int)

	rows, err := s.db.Query(ctx, entryIDsForCarNumbersQuery, sessionID)
	if err != nil {
		return nil, fmt.Errorf("getting entry IDs for car number: %w", err)
	}

	for rows.Next() {
		var id int
		var carNumber string
		err = rows.Scan(&id, &carNumber)
		if err != nil {
			return nil, fmt.Errorf("getting entry IDs for car number: %w", err)
		}
		idPerCarNumber[carNumber] = id
	}

	rows.Close()

	err = rows.Err()
	if err != nil {
		return nil, fmt.Errorf("getting entry IDs for car number: %w", err)
	}

	return idPerCarNumber, nil
}

func (s *SaveClassificationRepository) saveRetirements(
	ctx context.Context,
	retirements []*motorsportstats.Retirement,
	entryIDPerCarNumber map[string]int,
	driverIDPerUUID map[string]int,
) error {
	if len(retirements) == 0 {
		slog.Debug("No retirements to save")

		return nil
	}

	var rows [][]interface{}
	for _, retirement := range retirements {
		var entryID, driverID *int = nil, nil
		if retirement.Driver != nil {
			storedDriverID, ok := driverIDPerUUID[retirement.Driver.UUID]
			if ok == false {
				return fmt.Errorf("driver ID for UUID %s not found", retirement.Driver.UUID)
			}
			driverID = &storedDriverID
		}
		storedEntryID, ok := entryIDPerCarNumber[retirement.CarNumber]
		if ok == false {
			return fmt.Errorf("entry ID for car number %s not found", retirement.CarNumber)
		}
		entryID = &storedEntryID

		entryIDVal := fn.Deref(entryID, 0)
		driverIDVal := fn.Deref(driverID, 0)
		reasonVal := fn.Deref(retirement.Reason, "")
		typeVal := fn.Deref(retirement.Type, "")
		dnsVal := fn.Deref(retirement.DNS, false)
		lapVal := fn.Deref(retirement.Lap, 0)
		detailsVal := fn.Deref(retirement.Details, "")

		hash := crypto.Hash(fmt.Sprintf(
			"%d|%d|%s|%s|%t|%d|%s",
			entryIDVal, driverIDVal, reasonVal, typeVal, dnsVal, lapVal, detailsVal,
		))
		rows = append(rows, []interface{}{
			entryID,
			driverID,
			retirement.Reason,
			retirement.Type,
			retirement.DNS,
			retirement.Lap,
			retirement.Details,
			hash,
		})
	}

	cols := []string{"entry", "driver", "reason", "type", "dns", "lap", "details", "hash"}

	stats, err := shared.Save(ctx, s.db, "retirements", "entry", cols, rows)
	if err != nil {
		return fmt.Errorf("saving retirements: %w", err)
	}

	slog.Info(
		"Retirements saved successfully",
		slog.Int("count", len(retirements)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

func (s *SaveClassificationRepository) saveClassificationDetails(
	ctx context.Context,
	classifications []*motorsportstats.ClassificationDetail,
	entryIDPerCarNumber map[string]int,
) error {
	if len(classifications) == 0 {
		slog.Debug("No classification details to save")

		return nil
	}

	var rows [][]interface{}
	for _, details := range classifications {
		var entryID *int = nil
		storedEntryID, ok := entryIDPerCarNumber[details.CarNumber]
		if ok == false {
			return fmt.Errorf("car number for UUID %s not found", details.CarNumber)
		}
		entryID = &storedEntryID

		entryIDVal := fn.Deref(entryID, 0)
		finishPositionVal := fn.Deref(details.FinishPosition, 0)
		gridPositionVal := fn.Deref(details.GridPosition, 0)
		lapsVal := fn.Deref(details.Laps, 0)
		pointsVal := fn.Deref(details.Points, 0.0)
		timeVal := fn.Deref(details.Time, 0)
		statusVal := fn.Deref(details.ClassifiedStatus, "")
		avgLapSpeedVal := fn.Deref(details.AvgLapSpeed, 0)
		fastestLapTime := fn.Deref(details.FastestLapTime, 0)
		gapTimeToLeadVal := fn.Deref(details.ClassificationGap.TimeToLead, 0.0)
		gapTimeToNextVal := fn.Deref(details.ClassificationGap.TimeToNext, 0.0)
		gapLapsToLeadVal := fn.Deref(details.ClassificationGap.LapsToLead, 0)
		gapLapsToNextVal := fn.Deref(details.ClassificationGap.LapsToNext, 0)
		bestLapVal := fn.Deref(details.ClassificationBest.Lap, 0)
		bestTimeVal := fn.Deref(details.ClassificationBest.Time, 0.0)
		bestIsFastestVal := fn.Deref(details.ClassificationBest.Fastest, false)
		bestSpeedVal := fn.Deref(details.ClassificationBest.Speed, 0.0)

		hash := crypto.Hash(fmt.Sprintf(
			"%d|%d|%d|%d|%.2f|%.3f|%s|%.3f|%.3f|%.2f|%.2f|%d|%d|%d|%.2f|%t|%.3f",
			entryIDVal,
			finishPositionVal,
			gridPositionVal,
			lapsVal,
			pointsVal,
			timeVal,
			statusVal,
			avgLapSpeedVal,
			fastestLapTime,
			gapTimeToLeadVal,
			gapTimeToNextVal,
			gapLapsToLeadVal,
			gapLapsToNextVal,
			bestLapVal,
			bestTimeVal,
			bestIsFastestVal,
			bestSpeedVal,
		))
		rows = append(rows, []interface{}{
			entryID,
			details.FinishPosition,
			details.GridPosition,
			details.Laps,
			details.Points,
			details.Time,
			details.ClassifiedStatus,
			details.AvgLapSpeed,
			details.FastestLapTime,
			details.ClassificationGap.TimeToLead,
			details.ClassificationGap.TimeToNext,
			details.ClassificationGap.LapsToLead,
			details.ClassificationGap.LapsToNext,
			details.ClassificationBest.Lap,
			details.ClassificationBest.Time,
			details.ClassificationBest.Fastest,
			details.ClassificationBest.Speed,
			hash,
		})
	}

	cols := []string{
		"entry",
		"finish_position",
		"grid_position",
		"laps",
		"points",
		"time",
		"status",
		"avg_lap_speed",
		"fastest_lap_time",
		"gap_time_to_lead",
		"gap_time_to_next",
		"gap_laps_to_lead",
		"gap_laps_to_next",
		"best_lap",
		"best_time",
		"best_is_fastest",
		"best_speed",
		"hash",
	}

	stats, err := shared.Save(ctx, s.db, "classifications", "entry", cols, rows)
	if err != nil {
		return fmt.Errorf("saving classification details: %w", err)
	}

	slog.Info(
		"Classification details saved successfully",
		slog.Int("count", len(classifications)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

func (s *SaveClassificationRepository) saveEntryDrivers(
	ctx context.Context,
	driverUUIDsPerCarNumbers map[string][]string,
	entryIDPerCarNumber map[string]int,
	driverIDPerUUID map[string]int,
) error {
	if len(driverUUIDsPerCarNumbers) == 0 {
		slog.Debug("No entry drivers to save")

		return nil
	}

	var rows [][]interface{}
	for carNumber, driverUUIDs := range driverUUIDsPerCarNumbers {
		var entryID *int = nil
		storedEntryID, ok := entryIDPerCarNumber[carNumber]
		if ok == false {
			return fmt.Errorf("entry ID for car number %s not found", carNumber)
		}
		entryID = &storedEntryID

		for _, driverUUID := range driverUUIDs {
			var driverID *int = nil
			storedDriverID, ok := driverIDPerUUID[driverUUID]
			if ok == false {
				return fmt.Errorf("driver ID for UUID %s not found", driverUUID)
			}
			driverID = &storedDriverID

			entryIDVal := fn.Deref(entryID, 0)
			driverIDVal := fn.Deref(driverID, 0)

			hash := crypto.Hash(fmt.Sprintf("%d|%d", entryIDVal, driverIDVal))
			rows = append(rows, []interface{}{entryID, driverID, hash})
		}
	}

	cols := []string{"entry", "driver", "hash"}

	stats, err := shared.Save(ctx, s.db, "entry_drivers", "entry, driver", cols, rows)
	if err != nil {
		return fmt.Errorf("saving entry drivers: %w", err)
	}

	slog.Info(
		"Entry drivers saved successfully",
		slog.Int("count", len(rows)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}
