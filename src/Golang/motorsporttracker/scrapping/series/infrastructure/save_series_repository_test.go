package infrastructure

import (
	"fmt"
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// saveSeriesPrefix namespaces every UUID this suite writes; see scripts/fixture-prefix-check.sh.
const saveSeriesPrefix = "875c810d-a048-414e"

type SaveSeriesRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveSeriesRepository
	helper     *shared.SaveRepositoryHelper
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))

	suite.repository = NewSaveSeriesRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TearDownSuite() {
	suite.repository.db.Close()
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TearDownTest() {
	for _, table := range []string{"series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, saveSeriesPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) TestSaveSeries() {
	suite.Run("no-op when no series to save", func() {
		err := suite.repository.SaveSeries(suite.T().Context(), []*motorsportstats.Series{})
		suite.NoError(err)

		suite.Equal(0, suite.helper.Count(suite.T().Context(), "series", saveSeriesPrefix+"-%"))
	})

	suite.Run("saves one series", func() {
		seriesToSave := suite.oneSeries()

		err := suite.repository.SaveSeries(suite.T().Context(), seriesToSave)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "series", saveSeriesPrefix+"-0001-%"))
	})

	suite.Run("saves multiple series", func() {
		seriesToSave := suite.multipleSeries()

		err := suite.repository.SaveSeries(suite.T().Context(), seriesToSave)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(suite.T().Context(), "series", saveSeriesPrefix+"-0002-%"))
	})
}

func TestIntegration_SaveSeriesRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeriesRepositoryIntegrationTestSuite))
}

func (suite *SaveSeriesRepositoryIntegrationTestSuite) oneSeries() []*motorsportstats.Series {
	return []*motorsportstats.Series{
		{
			UUID:      saveSeriesPrefix + "-0001-000000000001",
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
			UUID:      saveSeriesPrefix + "-0002-000000000001",
			Name:      fn.Ptr("First series"),
			ShortName: fn.Ptr("FS"),
			ShortCode: fn.Ptr("FS"),
			Category:  fn.Ptr("Some category"),
		},
		{
			UUID:      saveSeriesPrefix + "-0002-000000000002",
			Name:      nil,
			ShortName: nil,
			ShortCode: nil,
			Category:  nil,
		},
		{
			UUID:      saveSeriesPrefix + "-0002-000000000003",
			Name:      fn.Ptr("Third series"),
			ShortName: fn.Ptr("TS"),
			ShortCode: fn.Ptr("TS"),
			Category:  fn.Ptr("Third category"),
		},
	}
}
