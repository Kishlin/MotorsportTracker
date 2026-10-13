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

// searchSeasonIdentifierPrefix namespaces this suite's UUIDs and searchable strings.
// Suites share core-test and run concurrently, so a keyword without it can match another suite's rows.
const searchSeasonIdentifierPrefix = "592e8e09-b250-446b"

type SearchSeasonIdentifierRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SearchSeasonIdentifierRepository
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seasonFixtures()))

	suite.repository = NewSearchSeasonIdentifierRepository(db)
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) TearDownSuite() {
	for _, table := range []string{"seasons", "seasons_history", "series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, searchSeasonIdentifierPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
}

func (suite *SearchSeasonIdentifierRepositoryIntegrationTestSuite) TestGetSeasonIdentifier() {
	for name, tc := range map[string]struct {
		keyword       string
		year          int
		expectedRef   string
		expectedFound bool
	}{
		"not found when there is no series match": {
			keyword:       "Non-existing series " + searchSeasonIdentifierPrefix,
			year:          2023,
			expectedRef:   "",
			expectedFound: false,
		},
		"not found when there is no season match": {
			keyword:       "Test Seasons Match 1 " + searchSeasonIdentifierPrefix,
			year:          2020,
			expectedRef:   "",
			expectedFound: false,
		},
		"found by exact name match": {
			keyword:       "Test Seasons Match 1 " + searchSeasonIdentifierPrefix,
			year:          2023,
			expectedRef:   searchSeasonIdentifierPrefix + "-0002-000000000001",
			expectedFound: true,
		},
		"still found season one year further back": {
			keyword:       "Test Seasons Match 1 " + searchSeasonIdentifierPrefix,
			year:          2022,
			expectedRef:   searchSeasonIdentifierPrefix + "-0002-000000000002",
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
	return fmt.Sprintf(`
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES
('%[1]s-0001-000000000001', 'Test Seasons Match 1 %[1]s', 'ShortSeasonsTest1 %[1]s', 'SerSeasonsTest1 %[1]s', 'Category 1', '%[1]s-0001-000000000001')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, name, year, end_year, hash) VALUES
('%[1]s-0002-000000000001',
 (SELECT id FROM series WHERE uuid::text = '%[1]s-0001-000000000001'),
 'Season 1', 2023, 2024, '%[1]s-0002-000000000001'),
('%[1]s-0002-000000000002',
 (SELECT id FROM series WHERE uuid::text = '%[1]s-0001-000000000001'),
 'Season 1', 2022, 2023, '%[1]s-0002-000000000002'),
('%[1]s-0002-000000000003',
 (SELECT id FROM series WHERE uuid::text = '%[1]s-0001-000000000001'),
 'Season 1', 2021, 2022, '%[1]s-0002-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`, searchSeasonIdentifierPrefix)
}
