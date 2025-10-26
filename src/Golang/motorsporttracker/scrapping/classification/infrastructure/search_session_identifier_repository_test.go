package infrastructure

import (
	"os"
	"testing"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
	"github.com/stretchr/testify/suite"
)

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
	cleanUps := []string{
		"DELETE FROM sessions WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM sessions_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM events WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM events_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM seasons WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM seasons_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM series WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM series_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM venues WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM venues_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM countries WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
		"DELETE FROM countries_history WHERE uuid::text LIKE '27b3d55f-62be-4058-%';",
	}

	for _, query := range cleanUps {
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
			seriesKeyword:  "series",
			year:           2025,
			eventKeyword:   "event",
			sessionKeyword: "session",
			expectedHit:    true,
			expectedRef:    "27b3d55f-62be-4058-0006-000000000001",
		},
		"it fails if the series is wrong": {
			seriesKeyword:  "wrong",
			year:           2025,
			eventKeyword:   "event",
			sessionKeyword: "session",
			expectedHit:    false,
		},
		"it fails if the year is wrong": {
			seriesKeyword:  "series",
			year:           2024,
			eventKeyword:   "event",
			sessionKeyword: "session",
			expectedHit:    false,
		},
		"it fails if the event is wrong": {
			seriesKeyword:  "series",
			year:           2025,
			eventKeyword:   "wrong",
			sessionKeyword: "session",
			expectedHit:    false,
		},
		"it fails if the session is wrong": {
			seriesKeyword:  "series",
			year:           2025,
			eventKeyword:   "event",
			sessionKeyword: "wrong",
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
	return `
INSERT INTO venues (uuid, hash) VALUES 
('27b3d55f-62be-4058-0001-000000000001', 'venues-hash')
ON CONFLICT (uuid) DO NOTHING;
INSERT INTO countries (uuid, hash) VALUES 
('27b3d55f-62be-4058-0002-000000000001', 'countries-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO series(uuid, name, hash) VALUES 
('27b3d55f-62be-4058-0003-000000000001', 'series', 'series-hash'),
('27b3d55f-62be-4058-0003-000000000002', 'wrong', 'wrong-series-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, year, hash) VALUES
('27b3d55f-62be-4058-0004-000000000001',
(SELECT id FROM series WHERE series.uuid = '27b3d55f-62be-4058-0003-000000000001'),
2025, '2025-hash'),
('27b3d55f-62be-4058-0004-000000000002',
(SELECT id FROM series WHERE series.uuid = '27b3d55f-62be-4058-0003-000000000002'),
2024, 'wrong-year-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO events (uuid, season, venue, country, name, hash) VALUES
('27b3d55f-62be-4058-0005-000000000001',
(SELECT id FROM seasons WHERE seasons.uuid = '27b3d55f-62be-4058-0004-000000000001'),
(SELECT id FROM venues WHERE uuid = '27b3d55f-62be-4058-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = '27b3d55f-62be-4058-0002-000000000001'),
'event', 'event-hash'),
('27b3d55f-62be-4058-0005-000000000002',
(SELECT id FROM seasons WHERE seasons.uuid = '27b3d55f-62be-4058-0004-000000000002'),
(SELECT id FROM venues where uuid = '27b3d55f-62be-4058-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = '27b3d55f-62be-4058-0002-000000000001'),
'wrong', 'wrong-event-hash')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO sessions (uuid, event, name, hash) VALUES 
('27b3d55f-62be-4058-0006-000000000001',
(SELECT id FROM events WHERE events.uuid = '27b3d55f-62be-4058-0005-000000000001'),
'session', 'session-hash'),
('27b3d55f-62be-4058-0006-000000000002',
(SELECT id FROM events WHERE events.uuid = '27b3d55f-62be-4058-0005-000000000002'),
'wrong', 'wrong-hash')
ON CONFLICT (uuid) DO NOTHING;
`
}
