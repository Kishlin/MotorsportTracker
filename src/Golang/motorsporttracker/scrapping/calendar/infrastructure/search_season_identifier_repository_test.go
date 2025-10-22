package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SearchSeasonIdentifierRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SearchSeasonIdentifierRepository

	resetEnv func()
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seasonFixtures()))

	suite.repository = NewSearchSeasonIdentifierRepository(db)
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) TearDownSuite() {
	cleanUps := []string{
		"DELETE FROM seasons WHERE uuid::text LIKE 'a5940eb2-b69f-4946-9436-%';",
		"DELETE FROM seasons_history WHERE uuid::text LIKE 'a5940eb2-b69f-4946-9436-%';",
		"DELETE FROM series WHERE uuid::text = '592e8e09-b250-446b-b1cd-000000000001';",
		"DELETE FROM series_history WHERE uuid::text = '592e8e09-b250-446b-b1cd-000000000001';",
	}
	for _, query := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) TestGetSeasonIdentifier() {
	for name, tc := range map[string]struct {
		keyword       string
		year          int
		expectedRef   string
		expectedFound bool
	}{
		"not found when there is no series match": {
			keyword:       "Non-existing series",
			year:          2023,
			expectedRef:   "",
			expectedFound: false,
		},
		"not found when there is no season match": {
			keyword:       "Test Seasons Match 1",
			year:          2020,
			expectedRef:   "",
			expectedFound: false,
		},
		"found by exact name match": {
			keyword:       "Test Seasons Match 1",
			year:          2023,
			expectedRef:   "a5940eb2-b69f-4946-9436-000000000001",
			expectedFound: true,
		},
		"still found season one year further back": {
			keyword:       "Test Seasons Match 1",
			year:          2022,
			expectedRef:   "a5940eb2-b69f-4946-9436-000000000002",
			expectedFound: true,
		},
	} {
		suite.Run(name, func() {
			ref, found, err := suite.repository.GetSeasonIdentifier(suite.T().Context(), tc.keyword, tc.year)
			suite.Require().NoError(err)
			suite.Require().Equal(tc.expectedFound, found)
			suite.Require().Equal(tc.expectedRef, ref)
		})
	}
}

func TestSearchSeasonIdentifierRepositoryIntegrationTestSuite(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SearchSeasonIdentifierRepositoryIntegrationTestSuite))
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) seasonFixtures() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('592e8e09-b250-446b-b1cd-000000000001', 'Test Seasons Match 1', 'ShortSeasonsTest1', 'SerSeasonsTest1', 'Category 1', '592e8e09-b250-446b-b1cd')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, name, year, end_year, hash) VALUES 
('a5940eb2-b69f-4946-9436-000000000001', 
 (SELECT id FROM series WHERE uuid::text = '592e8e09-b250-446b-b1cd-000000000001'), 
 'Season 1', 2023, 2024, 'a5940eb2-b69f-4946-9436-000000000001'),
('a5940eb2-b69f-4946-9436-000000000002', 
 (SELECT id FROM series WHERE uuid::text = '592e8e09-b250-446b-b1cd-000000000001'), 
 'Season 1', 2022, 2023, 'a5940eb2-b69f-4946-9436-000000000002'),
('a5940eb2-b69f-4946-9436-000000000003', 
 (SELECT id FROM series WHERE uuid::text = '592e8e09-b250-446b-b1cd-000000000001'), 
 'Season 1', 2021, 2022, 'a5940eb2-b69f-4946-9436-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`
}
