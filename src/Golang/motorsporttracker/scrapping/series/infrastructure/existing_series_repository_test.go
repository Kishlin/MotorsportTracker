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

func (suite *ExistingSeriesRepositoryFunctionalTestSuite) TestGetExistingSeries() {
	// Fixtures
	fn.Must(suite.repository.db.Exec(suite.T().Context(), seriesFixtures()))

	existingSeries, err := suite.repository.GetExistingSeries(suite.T().Context())
	require.NoError(suite.T(), err)

	count := 0
	for _, series := range existingSeries {
		if series.UUID[:24] == "91e8a1b1-898c-4ba3-9dd1-" {
			count++
		}
	}
	require.Equal(suite.T(), 3, count)

	// Clean up
	sql := "DELETE FROM series WHERE uuid::text LIKE '91e8a1b1-898c-4ba3-9dd1-%';"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
}

func TestFunctional_ExistingSeriesRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(ExistingSeriesRepositoryFunctionalTestSuite))
}

func seriesFixtures() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category) VALUES 
('91e8a1b1-898c-4ba3-9dd1-000000000001', 'Series 1', 'S1', 'S1', 'Category 1'),
('91e8a1b1-898c-4ba3-9dd1-000000000002', 'Series 2', 'S2', 'S2', 'Category 2'),
('91e8a1b1-898c-4ba3-9dd1-000000000003', 'Series 3', null, 'S3', 'Category 3');
`
}
