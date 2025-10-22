package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

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
	fn.Must(db.Exec(suite.T().Context(), seriesFixtures()))

	suite.repository = NewSearchSeriesIdentifierRepository(db)
}

func (suite *SearchSeriesIdentifierRepositoryIntegrationTestSuite) TearDownSuite() {
	sql := "DELETE FROM series WHERE uuid::text LIKE '82b7cd85-ee6f-4c2c-a289-%';"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))

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
			keyword:       "Non-existing series",
			expectedRef:   "",
			expectedFound: false,
		},
		"found by exact name match": {
			keyword:       "Test Series Match 1",
			expectedRef:   "82b7cd85-ee6f-4c2c-a289-000000000001",
			expectedFound: true,
		},
		"found by exact short name match": {
			keyword:       "ShortTest2",
			expectedRef:   "82b7cd85-ee6f-4c2c-a289-000000000002",
			expectedFound: true,
		},
		"found by exact short code match": {
			keyword:       "SerTest3",
			expectedRef:   "82b7cd85-ee6f-4c2c-a289-000000000003",
			expectedFound: true,
		},
		"found by partial name match": {
			keyword:       "Series Match 1",
			expectedRef:   "82b7cd85-ee6f-4c2c-a289-000000000001",
			expectedFound: true,
		},
	} {
		suite.T().Run(name, func(t *testing.T) {
			ref, found, err := suite.repository.GetSeriesIdentifier(t.Context(), tc.keyword)
			suite.NoError(err)

			if tc.expectedFound == false {
				require.False(t, found)
				return
			}

			require.True(t, found)
			require.Equal(t, tc.expectedRef, ref)
		})
	}
}

func TestIntegration_SearchSeriesIdentifierRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SearchSeriesIdentifierRepositoryIntegrationTestSuite))
}

func seriesFixtures() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('82b7cd85-ee6f-4c2c-a289-000000000001', 'Test Series Match 1', 'ShortTest1', 'SerTest1', 'Category 1', '82b7cd85-ee6f-4c2c-a289-000000000001'),
('82b7cd85-ee6f-4c2c-a289-000000000002', 'Test Series Match 2', 'ShortTest2', 'SerTest2', 'Category 2', '82b7cd85-ee6f-4c2c-a289-000000000002'),
('82b7cd85-ee6f-4c2c-a289-000000000003', 'Test Series Match 3', null, 'SerTest3', 'Category 3', '82b7cd85-ee6f-4c2c-a289-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`
}
