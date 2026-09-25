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

// searchSeriesIdentifierPrefix namespaces this suite's UUIDs and searchable strings.
// Suites share core-test and run concurrently, so a keyword without it can match another suite's rows.
const searchSeriesIdentifierPrefix = "82b7cd85-ee6f-4c2c-a289"

type SearchSeriesIdentifierRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SearchSeriesIdentifierRepository

	resetEnv func()
}

func (suite *SearchSeriesIdentifierRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seriesFixtures()))

	suite.repository = NewSearchSeriesIdentifierRepository(db)
}

func (suite *SearchSeriesIdentifierRepositoryIntegrationTestSuite) TearDownSuite() {
	for _, table := range []string{"series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, searchSeriesIdentifierPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SearchSeriesIdentifierRepositoryIntegrationTestSuite) TestGetSeriesIdentifier() {
	for name, tc := range map[string]struct {
		keyword       string
		expectedRef   string
		expectedFound bool
	}{
		"not found when there is no match": {
			keyword:       "Non-existing series " + searchSeriesIdentifierPrefix,
			expectedRef:   "",
			expectedFound: false,
		},
		"found by exact name match": {
			keyword:       "Test Series Match 1 " + searchSeriesIdentifierPrefix,
			expectedRef:   searchSeriesIdentifierPrefix + "-000000000001",
			expectedFound: true,
		},
		"found by exact short name match": {
			keyword:       "ShortTest2 " + searchSeriesIdentifierPrefix,
			expectedRef:   searchSeriesIdentifierPrefix + "-000000000002",
			expectedFound: true,
		},
		"found by exact short code match": {
			keyword:       "SerTest3 " + searchSeriesIdentifierPrefix,
			expectedRef:   searchSeriesIdentifierPrefix + "-000000000003",
			expectedFound: true,
		},
		"found by partial name match": {
			keyword:       "Series Match 1 " + searchSeriesIdentifierPrefix,
			expectedRef:   searchSeriesIdentifierPrefix + "-000000000001",
			expectedFound: true,
		},
	} {
		suite.Run(name, func() {
			ref, found, err := suite.repository.GetSeriesIdentifier(suite.T().Context(), tc.keyword)
			suite.NoError(err)

			if tc.expectedFound == false {
				suite.False(found)
				return
			}

			suite.True(found)
			suite.Equal(tc.expectedRef, ref)
		})
	}
}

func TestIntegration_SearchSeriesIdentifierRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SearchSeriesIdentifierRepositoryIntegrationTestSuite))
}

func (suite *SearchSeriesIdentifierRepositoryIntegrationTestSuite) seriesFixtures() string {
	return fmt.Sprintf(`
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES
('%[1]s-000000000001', 'Test Series Match 1 %[1]s', 'ShortTest1 %[1]s', 'SerTest1 %[1]s', 'Category 1', '%[1]s-000000000001'),
('%[1]s-000000000002', 'Test Series Match 2 %[1]s', 'ShortTest2 %[1]s', 'SerTest2 %[1]s', 'Category 2', '%[1]s-000000000002'),
('%[1]s-000000000003', 'Test Series Match 3 %[1]s', null, 'SerTest3 %[1]s', 'Category 3', '%[1]s-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`, searchSeriesIdentifierPrefix)
}
