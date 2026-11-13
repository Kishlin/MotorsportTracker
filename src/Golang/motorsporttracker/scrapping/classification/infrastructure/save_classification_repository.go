package infrastructure

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// ClassificationKey identifies a classification row within its session. A car
// number can appear on several rows (shared drives, a number reused by another
// driver), so occurrence numbers the rows carrying the same car number, in
// payload order, starting at 1.
type ClassificationKey struct {
	carNumber  string
	occurrence int
}

type ClassificationRow struct {
	key       ClassificationKey
	teamUUID  string
	garageKey string
	details   *motorsportstats.ClassificationDetail
}

type Garage struct {
	team      *motorsportstats.Team
	uniqueKey string
}

type SaveClassificationRepository struct {
	db *database.PGXPoolAdapter
}

func NewSaveClassificationRepository(db *database.PGXPoolAdapter) *SaveClassificationRepository {
	return &SaveClassificationRepository{db: db}
}

// SaveClassification saves a classification into the database. Every detail row
// and every retirement is stored as motorsportstats sends it.
func (s *SaveClassificationRepository) SaveClassification(ctx context.Context, session string, classification *motorsportstats.Classification) error {
	err := validateClassification(classification)
	if err != nil {
		return fmt.Errorf("validating classification of session %s: %w", session, err)
	}

	sessionID, err := s.getSessionID(ctx, session)
	if err != nil {
		return fmt.Errorf("getting session ID: %w", err)
	}

	uniqueDrivers := make([]*motorsportstats.Driver, 0)
	driversUUIDs := make(map[string]struct{})
	uniqueTeams := make([]*motorsportstats.Team, 0)
	teamsUUIDs := make(map[string]struct{})
	uniqueGarages := make([]*Garage, 0)
	garagesUniqueKeys := make(map[string]struct{})
	uniqueNationalities := make([]*motorsportstats.Country, 0)
	nationalitiesUUIDs := make(map[string]struct{})
	classificationRows := make([]*ClassificationRow, 0, len(classification.Details))
	occurrencesPerCarNumbers := make(map[string]int)

	for _, classificationDetails := range classification.Details {
		for _, driver := range classificationDetails.Drivers {
			if _, exists := driversUUIDs[driver.UUID]; exists == false {
				driversUUIDs[driver.UUID] = struct{}{}
				uniqueDrivers = append(uniqueDrivers, driver)
			}
		}
		if classificationDetails.Nationality != nil {
			if _, exists := nationalitiesUUIDs[classificationDetails.Nationality.UUID]; exists == false {
				nationalitiesUUIDs[classificationDetails.Nationality.UUID] = struct{}{}
				uniqueNationalities = append(uniqueNationalities, classificationDetails.Nationality)
			}
		}

		if _, exists := teamsUUIDs[classificationDetails.Team.UUID]; exists == false {
			teamsUUIDs[classificationDetails.Team.UUID] = struct{}{}
			uniqueTeams = append(uniqueTeams, classificationDetails.Team)
		}
		garageUniqueKey := fmt.Sprintf("%s:%s:%s",
			classificationDetails.Team.UUID,
			fn.Deref(classificationDetails.Team.Name, ""),
			fn.Deref(classificationDetails.Team.Colour, ""),
		)
		if _, exists := garagesUniqueKeys[garageUniqueKey]; exists == false {
			garagesUniqueKeys[garageUniqueKey] = struct{}{}
			uniqueGarages = append(uniqueGarages,
				&Garage{team: classificationDetails.Team, uniqueKey: garageUniqueKey},
			)
		}

		occurrencesPerCarNumbers[classificationDetails.CarNumber]++
		classificationRows = append(classificationRows, &ClassificationRow{
			key: ClassificationKey{
				carNumber:  classificationDetails.CarNumber,
				occurrence: occurrencesPerCarNumbers[classificationDetails.CarNumber],
			},
			teamUUID:  classificationDetails.Team.UUID,
			garageKey: garageUniqueKey,
			details:   classificationDetails,
		})
	}
	for _, retirement := range classification.Retirements {
		if _, exists := driversUUIDs[retirement.Driver.UUID]; exists == false {
			driversUUIDs[retirement.Driver.UUID] = struct{}{}
			uniqueDrivers = append(uniqueDrivers, retirement.Driver)
		}
	}

	err = shared.SaveCountries(ctx, s.db, uniqueNationalities)
	if err != nil {
		return fmt.Errorf("saving nationalities: %w", err)
	}

	err = s.saveDrivers(ctx, uniqueDrivers)
	if err != nil {
		return fmt.Errorf("saving drivers: %w", err)
	}

	driversIDsPerUUIDs, err := shared.GetIDsForValues(ctx, s.db, "drivers", "uuid", driversUUIDs)
	if err != nil {
		return fmt.Errorf("getting drivers IDs for UUIDs: %w", err)
	}

	err = s.saveTeams(ctx, uniqueTeams)
	if err != nil {
		return fmt.Errorf("saving teams: %w", err)
	}

	teamsIDsPerUUIDs, err := shared.GetIDsForValues(ctx, s.db, "teams", "uuid", teamsUUIDs)
	if err != nil {
		return fmt.Errorf("getting teams IDs for Keys: %w", err)
	}

	err = s.saveGarages(ctx, uniqueGarages)
	if err != nil {
		return fmt.Errorf("saving garages: %w", err)
	}

	garagesIDsPerKeys, err := shared.GetIDsForValues(ctx, s.db, "garages", "unique_key", garagesUniqueKeys)
	if err != nil {
		return fmt.Errorf("getting teams IDs for Keys: %w", err)
	}

	err = s.saveClassifications(ctx, classificationRows, sessionID, teamsIDsPerUUIDs, garagesIDsPerKeys)
	if err != nil {
		return fmt.Errorf("saving classifications: %w", err)
	}

	classificationIDsPerKeys, err := s.getClassificationIDsForKeys(ctx, sessionID)
	if err != nil {
		return fmt.Errorf("getting classification IDs for keys: %w", err)
	}

	err = s.saveClassificationDrivers(ctx, classificationRows, classificationIDsPerKeys, driversIDsPerUUIDs)
	if err != nil {
		return fmt.Errorf("saving classification drivers: %w", err)
	}

	err = s.saveRetirements(ctx, classification.Retirements, sessionID, driversIDsPerUUIDs)
	if err != nil {
		return fmt.Errorf("saving retirements: %w", err)
	}

	return nil
}

// validateClassification rejects a payload that cannot be stored as sent, before
// anything is written.
func validateClassification(classification *motorsportstats.Classification) error {
	for _, classificationDetails := range classification.Details {
		if classificationDetails.Team == nil {
			return fmt.Errorf("car number %s has no team", classificationDetails.CarNumber)
		}

		driversUUIDs := make(map[string]struct{})
		for _, driver := range classificationDetails.Drivers {
			if _, exists := driversUUIDs[driver.UUID]; exists {
				return fmt.Errorf("car number %s lists driver %s twice", classificationDetails.CarNumber, driver.UUID)
			}
			driversUUIDs[driver.UUID] = struct{}{}
		}
	}
	for _, retirement := range classification.Retirements {
		if retirement.Driver == nil {
			return fmt.Errorf("retirement for car number %s has no driver", retirement.CarNumber)
		}
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
		vals := []interface{}{driver.UUID, driver.Name, driver.FirstName, driver.LastName, driver.ShortCode, driver.Colour, driver.Picture}
		rows = append(rows, vals)
	}

	cols := []string{"uuid", "name", "first_name", "last_name", "short_code", "colour", "picture"}

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
		vals := []interface{}{team.UUID}
		rows = append(rows, vals)
	}

	cols := []string{"uuid"}

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

func (s *SaveClassificationRepository) saveGarages(
	ctx context.Context,
	garages []*Garage,
) error {
	if len(garages) == 0 {
		slog.Debug("No garages to save")

		return nil
	}

	var rows [][]interface{}
	for _, garage := range garages {
		vals := []interface{}{garage.uniqueKey, garage.team.Name, garage.team.Colour, garage.team.Picture, garage.team.CarIcon}
		rows = append(rows, vals)
	}

	cols := []string{"unique_key", "name", "colour", "picture", "car_icon"}

	stats, err := shared.Save(ctx, s.db, "garages", "unique_key", cols, rows)
	if err != nil {
		return fmt.Errorf("saving garages: %w", err)
	}

	slog.Info(
		"Garages saved successfully",
		slog.Int("count", len(garages)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

func (s *SaveClassificationRepository) saveClassifications(
	ctx context.Context,
	classificationRows []*ClassificationRow,
	sessionID int,
	teamsIDsPerUUIDs map[string]int,
	garagesIDsPerKeys map[string]int,
) error {
	if len(classificationRows) == 0 {
		slog.Debug("No classifications to save")

		return nil
	}

	var rows [][]interface{}
	for _, classificationRow := range classificationRows {
		teamID, ok := teamsIDsPerUUIDs[classificationRow.teamUUID]
		if ok == false {
			return fmt.Errorf("team ID for UUID %s not found", classificationRow.teamUUID)
		}

		garageID, ok := garagesIDsPerKeys[classificationRow.garageKey]
		if ok == false {
			return fmt.Errorf("garage ID for key %s not found", classificationRow.garageKey)
		}

		details := classificationRow.details
		rows = append(rows, []interface{}{
			sessionID,
			teamID,
			garageID,
			classificationRow.key.carNumber,
			classificationRow.key.occurrence,
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
		})
	}

	cols := []string{
		"session",
		"team",
		"garage",
		"car_number",
		"occurrence",
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
	}

	stats, err := shared.Save(ctx, s.db, "classifications", "session, car_number, occurrence", cols, rows)
	if err != nil {
		return fmt.Errorf("saving classifications: %w", err)
	}

	slog.Info(
		"Classifications saved successfully",
		slog.Int("count", len(classificationRows)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

const classificationIDsForKeysQuery = "SELECT id, car_number, occurrence FROM classifications WHERE session = $1;"

func (s *SaveClassificationRepository) getClassificationIDsForKeys(
	ctx context.Context,
	sessionID int,
) (map[ClassificationKey]int, error) {
	idPerKey := make(map[ClassificationKey]int)

	rows, err := s.db.Query(ctx, classificationIDsForKeysQuery, sessionID)
	if err != nil {
		return nil, fmt.Errorf("getting classification IDs for keys: %w", err)
	}

	for rows.Next() {
		var id int
		var key ClassificationKey
		err = rows.Scan(&id, &key.carNumber, &key.occurrence)
		if err != nil {
			return nil, fmt.Errorf("getting classification IDs for keys: %w", err)
		}
		idPerKey[key] = id
	}

	rows.Close()

	err = rows.Err()
	if err != nil {
		return nil, fmt.Errorf("getting classification IDs for keys: %w", err)
	}

	return idPerKey, nil
}

func (s *SaveClassificationRepository) saveClassificationDrivers(
	ctx context.Context,
	classificationRows []*ClassificationRow,
	classificationIDPerKey map[ClassificationKey]int,
	driverIDPerUUID map[string]int,
) error {
	if len(classificationRows) == 0 {
		slog.Debug("No classification drivers to save")

		return nil
	}

	var rows [][]interface{}
	for _, classificationRow := range classificationRows {
		classificationID, ok := classificationIDPerKey[classificationRow.key]
		if ok == false {
			return fmt.Errorf(
				"classification ID for car number %s, occurrence %d not found",
				classificationRow.key.carNumber,
				classificationRow.key.occurrence,
			)
		}

		for _, driver := range classificationRow.details.Drivers {
			driverID, ok := driverIDPerUUID[driver.UUID]
			if ok == false {
				return fmt.Errorf("driver ID for UUID %s not found", driver.UUID)
			}

			rows = append(rows, []interface{}{classificationID, driverID})
		}
	}

	cols := []string{"classification", "driver"}

	stats, err := shared.Save(ctx, s.db, "classification_drivers", "classification, driver", cols, rows)
	if err != nil {
		return fmt.Errorf("saving classification drivers: %w", err)
	}

	slog.Info(
		"Classification drivers saved successfully",
		slog.Int("count", len(rows)),
		slog.Int("inserted", stats.Inserted),
		slog.Int("updated", stats.Updated),
	)

	return nil
}

func (s *SaveClassificationRepository) saveRetirements(
	ctx context.Context,
	retirements []*motorsportstats.Retirement,
	sessionID int,
	driverIDPerUUID map[string]int,
) error {
	if len(retirements) == 0 {
		slog.Debug("No retirements to save")

		return nil
	}

	var rows [][]interface{}
	for _, retirement := range retirements {
		driverID, ok := driverIDPerUUID[retirement.Driver.UUID]
		if ok == false {
			return fmt.Errorf("driver ID for UUID %s not found", retirement.Driver.UUID)
		}

		rows = append(rows, []interface{}{
			sessionID,
			retirement.CarNumber,
			driverID,
			retirement.Reason,
			retirement.Type,
			retirement.DNS,
			retirement.Lap,
			retirement.Details,
		})
	}

	cols := []string{"session", "car_number", "driver", "reason", "type", "dns", "lap", "details"}

	stats, err := shared.Save(ctx, s.db, "retirements", "session, car_number, driver", cols, rows)
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
