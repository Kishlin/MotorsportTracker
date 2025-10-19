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

type ExistingSeriesRepositoryFunctionalTestSuite struct {
	suite.Suite

	repository *ExistingSeriesRepository

	resetEnv func()
}

func (suite *ExistingSeriesRepositoryFunctionalTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))

	suite.repository = NewExistingSeriesRepository(db)
}

func (suite *ExistingSeriesRepositoryFunctionalTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *ExistingSeriesRepositoryFunctionalTestSuite) TearDownTest() {
	sql := "TRUNCATE TABLE series RESTART IDENTITY CASCADE;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
}

func (suite *ExistingSeriesRepositoryFunctionalTestSuite) TestGetExistingSeries() {
	suite.T().Run("returns empty map when there are no series in the database", func(t *testing.T) {
		existingSeries, err := suite.repository.GetExistingSeries(t.Context())
		require.NoError(t, err)
		require.Empty(t, existingSeries)
	})

	suite.T().Run("returns all existing series from the database", func(t *testing.T) {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), seriesFixtures()))

		existingSeries, err := suite.repository.GetExistingSeries(t.Context())
		require.NoError(t, err)
		require.Len(t, existingSeries, 3)
	})
}

func TestFunctional_ExistingSeriesRepository(t *testing.T) {
	suite.Run(t, new(ExistingSeriesRepositoryFunctionalTestSuite))
}

func seriesFixtures() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category) VALUES 
('00000000-0000-0000-0000-000000000001', 'Series 1', 'S1', 'S1', 'Category 1'),
('00000000-0000-0000-0000-000000000002', 'Series 2', 'S2', 'S2', 'Category 2'),
('00000000-0000-0000-0000-000000000003', 'Series 3', null, 'S3', 'Category 3');
`
}
