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

type SearchSeriesIdentifierRepositoryFunctionalTestSuite struct {
	suite.Suite

	repository *SearchSeriesIdentifierRepository

	resetEnv func()
}

func (suite *SearchSeriesIdentifierRepositoryFunctionalTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), seriesFixtures()))

	suite.repository = NewSearchSeriesIdentifierRepository(db)
}

func (suite *SearchSeriesIdentifierRepositoryFunctionalTestSuite) TearDownSuite() {
	sql := "TRUNCATE TABLE series RESTART IDENTITY CASCADE;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SearchSeriesIdentifierRepositoryFunctionalTestSuite) TestGetSeriesIdentifier() {
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
			keyword:       "Series 1",
			expectedRef:   "00000000-0000-0000-0000-000000000001",
			expectedFound: true,
		},
		"found by exact short name match": {
			keyword:       "S2",
			expectedRef:   "00000000-0000-0000-0000-000000000002",
			expectedFound: true,
		},
		"found by exact short code match": {
			keyword:       "Ser3",
			expectedRef:   "00000000-0000-0000-0000-000000000003",
			expectedFound: true,
		},
		"found by partial name match": {
			keyword:       "ies 1",
			expectedRef:   "00000000-0000-0000-0000-000000000001",
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

func TestFunctional_SearchSeriesIdentifierRepository(t *testing.T) {
	suite.Run(t, new(SearchSeriesIdentifierRepositoryFunctionalTestSuite))
}

func seriesFixtures() string {
	return `
INSERT INTO series (id, uuid, name, short_name, short_code, category) VALUES 
(1, '00000000-0000-0000-0000-000000000001', 'Series 1', 'S1', 'Ser1', 'Category 1'),
(2, '00000000-0000-0000-0000-000000000002', 'Series 2', 'S2', 'Ser2', 'Category 2'),
(3, '00000000-0000-0000-0000-000000000003', 'Series 3', null, 'Ser3', 'Category 3');
`
}
