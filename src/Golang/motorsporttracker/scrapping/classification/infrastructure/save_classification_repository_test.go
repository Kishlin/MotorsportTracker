package infrastructure

import (
	"fmt"
	"os"
	"testing"

	"github.com/jackc/pgx/v5"
	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SaveClassificationRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveClassificationRepository
	helper     *shared.SaveRepositoryHelper

	resetEnv func()
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.sessionFixtures()))

	suite.repository = NewSaveClassificationRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) TearDownSuite() {
	cleanUps := []string{
		`DELETE FROM entry_drivers WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM entry_drivers_history WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM classifications WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM classifications_history WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM retirements WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM retirements_history WHERE entry IN (SELECT e.id from entries e INNER JOIN sessions s ON s.id = e.session WHERE s.uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM entries WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM entries_history WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE 'dbc082d8-53c0-468b-0006-%');`,
		`DELETE FROM drivers WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM drivers_history WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM teams WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM teams_history WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM garages WHERE unique_key::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM garages_history WHERE unique_key::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM sessions WHERE uuid::text LIKE 'dbc082d8-53c0-468b-0006-%';`,
		`DELETE FROM sessions_history WHERE uuid::text LIKE 'dbc082d8-53c0-468b-0006-%';`,
		`DELETE FROM events WHERE uuid::text = 'dbc082d8-53c0-468b-0005-000000000001';`,
		`DELETE FROM events_history WHERE uuid::text = 'dbc082d8-53c0-468b-0005-000000000001';`,
		`DELETE FROM venues WHERE uuid::text = 'dbc082d8-53c0-468b-0001-000000000001';`,
		`DELETE FROM venues_history WHERE uuid::text = 'dbc082d8-53c0-468b-0001-000000000001';`,
		`DELETE FROM countries WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM countries_history WHERE uuid::text LIKE 'dbc082d8-53c0-468b-%';`,
		`DELETE FROM seasons WHERE uuid::text = 'dbc082d8-53c0-468b-0004-000000000001';`,
		`DELETE FROM seasons_history WHERE uuid::text = 'dbc082d8-53c0-468b-0004-000000000001';`,
		`DELETE FROM series WHERE uuid::text = 'dbc082d8-53c0-468b-0003-000000000001';`,
		`DELETE FROM series_history WHERE uuid::text = 'dbc082d8-53c0-468b-0003-000000000001';`,
	}
	for _, sql := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) TestSaveClassification() {
	suite.T().Run("no-op when no classifications or retirements", func(t *testing.T) {
		emptyClassification := suite.emptyClassification()
		err := suite.repository.SaveClassification(t.Context(), "dbc082d8-53c0-468b-0006-000000000001", emptyClassification)
		suite.NoError(err)

		suite.Equal(0, suite.count(t, countEntriesQuery, "dbc082d8-53c0-468b-0006-000000000001"))
		suite.Equal(0, suite.count(t, countEntryDriversQuery, "dbc082d8-53c0-468b-0006-000000000001"))
		suite.Equal(len(emptyClassification.Retirements), suite.count(t, countRetirementsQuery, "dbc082d8-53c0-468b-0006-000000000001"))
		suite.Equal(len(emptyClassification.Details), suite.count(t, countClassificationDetailsQuery, "dbc082d8-53c0-468b-0006-000000000001"))
	})

	suite.T().Run("saves a very simple classification", func(t *testing.T) {
		classification := suite.verySimpleClassification()
		err := suite.repository.SaveClassification(t.Context(), "dbc082d8-53c0-468b-0006-000000000002", classification)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "teams", "dbc082d8-53c0-468b-0007-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "drivers", "dbc082d8-53c0-468b-0007-%"))
		suite.Equal(1, suite.count(t, countEntriesQuery, "dbc082d8-53c0-468b-0006-000000000002"))
		suite.Equal(1, suite.count(t, countEntryDriversQuery, "dbc082d8-53c0-468b-0006-000000000002"))
		suite.Equal(len(classification.Retirements), suite.count(t, countRetirementsQuery, "dbc082d8-53c0-468b-0006-000000000002"))
		suite.Equal(len(classification.Details), suite.count(t, countClassificationDetailsQuery, "dbc082d8-53c0-468b-0006-000000000002"))
	})

	suite.T().Run("saves data when there are nil values", func(t *testing.T) {
		classification := suite.classificationWithNilValues()
		err := suite.repository.SaveClassification(t.Context(), "dbc082d8-53c0-468b-0006-000000000003", classification)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "teams", "dbc082d8-53c0-468b-0008-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "drivers", "dbc082d8-53c0-468b-0008-%"))
		suite.Equal(1, suite.count(t, countEntriesQuery, "dbc082d8-53c0-468b-0006-000000000003"))
		suite.Equal(1, suite.count(t, countEntryDriversQuery, "dbc082d8-53c0-468b-0006-000000000003"))
		suite.Equal(len(classification.Retirements), suite.count(t, countRetirementsQuery, "dbc082d8-53c0-468b-0006-000000000003"))
		suite.Equal(len(classification.Details), suite.count(t, countClassificationDetailsQuery, "dbc082d8-53c0-468b-0006-000000000003"))
	})

	suite.T().Run("saves everything from a complex classification", func(t *testing.T) {
		classification := suite.complexClassification()
		err := suite.repository.SaveClassification(t.Context(), "dbc082d8-53c0-468b-0006-000000000004", classification)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(t.Context(), "teams", "dbc082d8-53c0-468b-0009-%"))
		suite.Equal(10, suite.helper.Count(t.Context(), "drivers", "dbc082d8-53c0-468b-0009-%"))
		suite.Equal(4, suite.count(t, countEntriesQuery, "dbc082d8-53c0-468b-0006-000000000004"))
		suite.Equal(10, suite.count(t, countEntryDriversQuery, "dbc082d8-53c0-468b-0006-000000000004"))
		suite.Equal(len(classification.Retirements), suite.count(t, countRetirementsQuery, "dbc082d8-53c0-468b-0006-000000000004"))
		suite.Equal(len(classification.Details), suite.count(t, countClassificationDetailsQuery, "dbc082d8-53c0-468b-0006-000000000004"))
	})
}

func TestIntegration_SaveClassificationRepository(t *testing.T) {
	suite.Run(t, new(SaveClassificationRepositoryIntegrationTestSuite))
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) sessionFixtures() string {
	return `
INSERT INTO venues (uuid, hash) VALUES 
('dbc082d8-53c0-468b-0001-000000000001', 'venues-hash')
ON CONFLICT (uuid) DO NOTHING;
INSERT INTO countries (uuid, hash) VALUES 
('dbc082d8-53c0-468b-0002-000000000001', 'countries-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO series(uuid, name, hash) VALUES 
('dbc082d8-53c0-468b-0003-000000000001', 'series', 'series-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, year, hash) VALUES
('dbc082d8-53c0-468b-0004-000000000001',
(SELECT id FROM series WHERE series.uuid = 'dbc082d8-53c0-468b-0003-000000000001'),
2025, '2025-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO events (uuid, season, venue, country, name, hash) VALUES
('dbc082d8-53c0-468b-0005-000000000001',
(SELECT id FROM seasons WHERE seasons.uuid = 'dbc082d8-53c0-468b-0004-000000000001'),
(SELECT id FROM venues WHERE uuid = 'dbc082d8-53c0-468b-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = 'dbc082d8-53c0-468b-0002-000000000001'),
'event', 'event-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO sessions (uuid, event, name, hash) VALUES 
('dbc082d8-53c0-468b-0006-000000000001',
(SELECT id FROM events WHERE events.uuid = 'dbc082d8-53c0-468b-0005-000000000001'),
'session', 'session-hash-1'),
('dbc082d8-53c0-468b-0006-000000000002',
(SELECT id FROM events WHERE events.uuid = 'dbc082d8-53c0-468b-0005-000000000001'),
'session', 'session-hash-2'),
('dbc082d8-53c0-468b-0006-000000000003',
(SELECT id FROM events WHERE events.uuid = 'dbc082d8-53c0-468b-0005-000000000001'),
'session', 'session-hash-3'),
('dbc082d8-53c0-468b-0006-000000000004',
(SELECT id FROM events WHERE events.uuid = 'dbc082d8-53c0-468b-0005-000000000001'),
'session', 'session-hash-4')
ON CONFLICT (uuid) DO NOTHING;
`
}

const countEntriesQuery = `
	SELECT count(e.id)
	FROM entries e
	INNER JOIN sessions s ON s.id = e.session
	WHERE s.uuid::text = '%s'
`

const countRetirementsQuery = `
	SELECT count(r.id)
	FROM retirements r
	INNER JOIN entries e ON r.entry = e.id
	INNER JOIN sessions s ON s.id = e.session
	WHERE s.uuid::text = '%s'
`

const countClassificationDetailsQuery = `
	SELECT count(c.id)
	FROM classifications c
	INNER JOIN entries e ON c.entry = e.id
	INNER JOIN sessions s ON s.id = e.session
	WHERE s.uuid::text = '%s'
`

const countEntryDriversQuery = `
	SELECT count(ed.id)
	FROM entry_drivers ed
	INNER JOIN entries e ON ed.entry = e.id
	INNER JOIN sessions s ON s.id = e.session
	WHERE s.uuid::text = '%s'
`

func (suite *SaveClassificationRepositoryIntegrationTestSuite) count(t *testing.T, query string, session string) int {
	t.Helper()

	rows := fn.MustReturn(suite.repository.db.Query(t.Context(), fmt.Sprintf(query, session))).(pgx.Rows)
	defer rows.Close()

	rows.Next()

	var count int
	fn.Must(rows.Scan(&count))

	return count
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) emptyClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details:     []*motorsportstats.ClassificationDetail{},
		Retirements: []*motorsportstats.Retirement{},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) verySimpleClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:      "1",
				FinishPosition: fn.Ptr(1),
				GridPosition:   fn.Ptr(2),
				Drivers: []*motorsportstats.Driver{
					{
						UUID:      "dbc082d8-53c0-468b-0007-000000000001",
						Name:      fn.Ptr("Max Verstappen"),
						FirstName: fn.Ptr("Max"),
						LastName:  fn.Ptr("Verstappen"),
						ShortCode: fn.Ptr("MV"),
						Colour:    fn.Ptr("Blue"),
						Picture:   fn.Ptr("some url"),
					},
				},
				Team: &motorsportstats.Team{
					UUID:    "dbc082d8-53c0-468b-0007-000000000001",
					Name:    fn.Ptr("Red Bull"),
					Colour:  fn.Ptr("Blue"),
					Picture: fn.Ptr("some url"),
					CarIcon: fn.Ptr("some url"),
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0007-000000000001",
					Name: fn.Ptr("Austria"),
					Flag: fn.Ptr("at.svg"),
				},
				Laps:             fn.Ptr(10),
				Points:           fn.Ptr(25.0),
				Time:             fn.Ptr(180.0),
				ClassifiedStatus: fn.Ptr("CLA"),
				AvgLapSpeed:      fn.Ptr(184.0),
				FastestLapTime:   fn.Ptr(18.0),
				ClassificationGap: motorsportstats.ClassificationGap{
					TimeToLead: fn.Ptr(0.0),
					TimeToNext: fn.Ptr(0.0),
					LapsToLead: fn.Ptr(0),
					LapsToNext: fn.Ptr(0),
				},
				ClassificationBest: motorsportstats.ClassificationBest{
					Lap:     fn.Ptr(5),
					Time:    fn.Ptr(18.0),
					Fastest: fn.Ptr(true),
					Speed:   fn.Ptr(187.5),
				},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "1",
				Driver: &motorsportstats.Driver{
					UUID:      "dbc082d8-53c0-468b-0007-000000000001",
					Name:      fn.Ptr("Max Verstappen"),
					FirstName: fn.Ptr("Max"),
					LastName:  fn.Ptr("Verstappen"),
					ShortCode: fn.Ptr("MV"),
					Colour:    fn.Ptr("Blue"),
					Picture:   fn.Ptr("some url"),
				},
				Reason:  fn.Ptr("mechanical"),
				Type:    fn.Ptr("failure"),
				DNS:     fn.Ptr(false),
				Lap:     fn.Ptr(8),
				Details: fn.Ptr("bla bla bla"),
			},
		},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) classificationWithNilValues() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "11",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: "dbc082d8-53c0-468b-0008-000000000001",
					},
				},
				Team: &motorsportstats.Team{
					UUID: "dbc082d8-53c0-468b-0008-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0008-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "11",
				Driver: &motorsportstats.Driver{
					UUID: "dbc082d8-53c0-468b-0008-000000000001",
				},
			},
		},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) complexClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "101",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000001",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000002",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000003",
					},
				},
				Team: &motorsportstats.Team{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "102",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000004",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000005",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000006",
					},
				},
				Team: &motorsportstats.Team{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "103",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000007",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000008",
					},
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000009",
					},
				},
				Team: &motorsportstats.Team{
					UUID: "dbc082d8-53c0-468b-0009-000000000002",
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "104",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: "dbc082d8-53c0-468b-0009-000000000010",
					},
				},
				Team: &motorsportstats.Team{
					UUID: "dbc082d8-53c0-468b-0009-000000000003",
				},
				Nationality: &motorsportstats.Country{
					UUID: "dbc082d8-53c0-468b-0009-000000000002",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "101",
				Driver: &motorsportstats.Driver{
					UUID: "dbc082d8-53c0-468b-0009-000000000001",
				},
			},
			{
				CarNumber: "103",
				Driver: &motorsportstats.Driver{
					UUID: "dbc082d8-53c0-468b-0009-000000000008",
				},
			},
		},
	}
}
