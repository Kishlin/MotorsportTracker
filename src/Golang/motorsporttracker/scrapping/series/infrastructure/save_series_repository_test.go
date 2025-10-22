package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SaveSeriesRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveSeriesRepository
	helper     *shared.SaveRepositoryHelper

	resetEnv func()
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))

	suite.repository = NewSaveSeriesRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TearDownTest() {
	cleanUps := []string{
		"DELETE FROM series WHERE uuid::text LIKE '875c810d-a048-414e-%';",
		"DELETE FROM series_history WHERE uuid::text LIKE '875c810d-a048-414e-%';",
	}
	for _, sql := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
	}
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TestSaveSeries() {
	suite.T().Run("no-op when no series to save", func(t *testing.T) {
		err := suite.repository.SaveSeries(t.Context(), []*motorsportstats.Series{})
		suite.NoError(err)

		suite.Equal(0, suite.helper.Count(suite.T().Context(), "series", "875c810d-a048-414e-%"))
	})

	suite.T().Run("saves one series", func(t *testing.T) {
		seriesToSave := suite.oneSeries()

		err := suite.repository.SaveSeries(t.Context(), seriesToSave)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "series", "875c810d-a048-414e-0001-%"))
	})

	suite.T().Run("saves multiple series", func(t *testing.T) {
		seriesToSave := suite.multipleSeries()

		err := suite.repository.SaveSeries(t.Context(), seriesToSave)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(suite.T().Context(), "series", "875c810d-a048-414e-0002-%"))
	})
}

func TestIntegration_SaveSeriesRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeriesRepositoryIntegrationTestSuite))
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) oneSeries() []*motorsportstats.Series {
	return []*motorsportstats.Series{
		{
			UUID:      "875c810d-a048-414e-0001-000000000001",
			Name:      fn.Ptr("Some series"),
			ShortName: fn.Ptr("SS"),
			ShortCode: fn.Ptr("SS"),
			Category:  fn.Ptr("Some category"),
		},
	}
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) multipleSeries() []*motorsportstats.Series {
	return []*motorsportstats.Series{
		{
			UUID:      "875c810d-a048-414e-0002-000000000001",
			Name:      fn.Ptr("First series"),
			ShortName: fn.Ptr("FS"),
			ShortCode: fn.Ptr("FS"),
			Category:  fn.Ptr("Some category"),
		},
		{
			UUID:      "875c810d-a048-414e-0002-000000000002",
			Name:      nil,
			ShortName: nil,
			ShortCode: nil,
			Category:  nil,
		},
		{
			UUID:      "875c810d-a048-414e-0002-000000000003",
			Name:      fn.Ptr("Third series"),
			ShortName: fn.Ptr("TS"),
			ShortCode: fn.Ptr("TS"),
			Category:  fn.Ptr("Third category"),
		},
	}
}
