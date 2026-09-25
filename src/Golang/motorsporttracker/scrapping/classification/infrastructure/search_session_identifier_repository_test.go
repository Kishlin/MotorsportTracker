package infrastructure

import (
	"fmt"
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// searchSessionIdentifierPrefix namespaces this suite's UUIDs and searchable strings.
// Suites share core-test and run concurrently, so a keyword without it can match another suite's rows.
const searchSessionIdentifierPrefix = "27b3d55f-62be-4058"

type SearchSessionIdentifierRepositoryTestSuite struct {
	suite.Suite

	repository *SearchSessionIdentifierRepository

	resetEnv func()
}

func (suite *SearchSessionIdentifierRepositoryTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.sessionFixtures()))

	suite.repository = NewSearchSessionIdentifierRepository(db)
}

func (suite *SearchSessionIdentifierRepositoryTestSuite) TearDownSuite() {
	tables := []string{
		"sessions", "sessions_history",
		"events", "events_history",
		"seasons", "seasons_history",
		"series", "series_history",
		"venues", "venues_history",
		"countries", "countries_history",
	}

	for _, table := range tables {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, searchSessionIdentifierPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SearchSessionIdentifierRepositoryTestSuite) TestSearchSessionIdentifierRepository() {
	for name, tc := range map[string]struct {
		seriesKeyword, eventKeyword, sessionKeyword string
		year                                        int
		expectedHit                                 bool
		expectedRef                                 string
	}{
		"it finds the right session": {
			seriesKeyword:  "series " + searchSessionIdentifierPrefix,
			year:           2025,
			eventKeyword:   "event " + searchSessionIdentifierPrefix,
			sessionKeyword: "session " + searchSessionIdentifierPrefix,
			expectedHit:    true,
			expectedRef:    searchSessionIdentifierPrefix + "-0006-000000000001",
		},
		"it fails if the series is wrong": {
			seriesKeyword:  "wrong " + searchSessionIdentifierPrefix,
			year:           2025,
			eventKeyword:   "event " + searchSessionIdentifierPrefix,
			sessionKeyword: "session " + searchSessionIdentifierPrefix,
			expectedHit:    false,
		},
		"it fails if the year is wrong": {
			seriesKeyword:  "series " + searchSessionIdentifierPrefix,
			year:           2024,
			eventKeyword:   "event " + searchSessionIdentifierPrefix,
			sessionKeyword: "session " + searchSessionIdentifierPrefix,
			expectedHit:    false,
		},
		"it fails if the event is wrong": {
			seriesKeyword:  "series " + searchSessionIdentifierPrefix,
			year:           2025,
			eventKeyword:   "wrong " + searchSessionIdentifierPrefix,
			sessionKeyword: "session " + searchSessionIdentifierPrefix,
			expectedHit:    false,
		},
		"it fails if the session is wrong": {
			seriesKeyword:  "series " + searchSessionIdentifierPrefix,
			year:           2025,
			eventKeyword:   "event " + searchSessionIdentifierPrefix,
			sessionKeyword: "wrong " + searchSessionIdentifierPrefix,
			expectedHit:    false,
		},
	} {
		suite.T().Run(name, func(t *testing.T) {
			actualRef, actualHit, err := suite.repository.GetSessionIdentifier(
				suite.T().Context(),
				tc.seriesKeyword,
				tc.year,
				tc.eventKeyword,
				tc.sessionKeyword,
			)
			suite.NoError(err)
			suite.Equal(tc.expectedHit, actualHit)

			if tc.expectedHit {
				suite.Equal(tc.expectedRef, actualRef)
			}
		})
	}
}

func TestIntegration_SearchSessionIdentifierRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SearchSessionIdentifierRepositoryTestSuite))
}

func (suite *SearchSessionIdentifierRepositoryTestSuite) sessionFixtures() string {
	return fmt.Sprintf(`
INSERT INTO venues (uuid, hash) VALUES 
('%[1]s-0001-000000000001', 'venues-hash')
ON CONFLICT (uuid) DO NOTHING;
INSERT INTO countries (uuid, hash) VALUES 
('%[1]s-0002-000000000001', 'countries-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO series(uuid, name, hash) VALUES 
('%[1]s-0003-000000000001', 'series %[1]s', 'series-hash'),
('%[1]s-0003-000000000002', 'wrong %[1]s', 'wrong-series-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, year, hash) VALUES
('%[1]s-0004-000000000001',
(SELECT id FROM series WHERE series.uuid = '%[1]s-0003-000000000001'),
2025, '2025-hash'),
('%[1]s-0004-000000000002',
(SELECT id FROM series WHERE series.uuid = '%[1]s-0003-000000000002'),
2024, 'wrong-year-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO events (uuid, season, venue, country, name, hash) VALUES
('%[1]s-0005-000000000001',
(SELECT id FROM seasons WHERE seasons.uuid = '%[1]s-0004-000000000001'),
(SELECT id FROM venues WHERE uuid = '%[1]s-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = '%[1]s-0002-000000000001'),
'event %[1]s', 'event-hash'),
('%[1]s-0005-000000000002',
(SELECT id FROM seasons WHERE seasons.uuid = '%[1]s-0004-000000000002'),
(SELECT id FROM venues where uuid = '%[1]s-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = '%[1]s-0002-000000000001'),
'wrong %[1]s', 'wrong-event-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO sessions (uuid, event, name, hash) VALUES 
('%[1]s-0006-000000000001',
(SELECT id FROM events WHERE events.uuid = '%[1]s-0005-000000000001'),
'session %[1]s', 'session-hash'),
('%[1]s-0006-000000000002',
(SELECT id FROM events WHERE events.uuid = '%[1]s-0005-000000000002'),
'wrong %[1]s', 'wrong-hash')
ON CONFLICT (uuid) DO NOTHING;
`, searchSessionIdentifierPrefix)
}
