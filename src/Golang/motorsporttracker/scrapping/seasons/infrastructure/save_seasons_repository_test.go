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

// saveSeasonsPrefix namespaces every UUID this suite writes; see scripts/fixture-prefix-check.sh.
const saveSeasonsPrefix = "75f849b7-35be-44d8"

const seriesRef = saveSeasonsPrefix + "-0001-000000000001"

type SaveSeasonsRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveSeasonsRepository
	helper     *shared.SaveRepositoryHelper

	resetEnv func()
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seriesFixture()))

	suite.repository = NewSaveSeasonsRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) TearDownTest() {
	for _, table := range []string{"seasons", "seasons_history", "series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, saveSeasonsPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) TestSaveSeasons() {
	suite.Run("no-op when no seasons to save", func() {
		err := suite.repository.SaveSeasons(suite.T().Context(), seriesRef, []*motorsportstats.Season{})
		suite.NoError(err)

		suite.Equal(0, suite.helper.Count(suite.T().Context(), "seasons", saveSeasonsPrefix+"-%"))
	})

	suite.Run("saves one season", func() {
		seasonsToSave := suite.oneSeason()

		err := suite.repository.SaveSeasons(suite.T().Context(), seriesRef, seasonsToSave)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "seasons", saveSeasonsPrefix+"-0002-%"))
	})

	suite.Run("saves multiple seasons", func() {
		seasonsToSave := suite.multipleSeasons()

		err := suite.repository.SaveSeasons(suite.T().Context(), seriesRef, seasonsToSave)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(suite.T().Context(), "seasons", saveSeasonsPrefix+"-0003-%"))
	})
}

func TestIntegration_SaveSeasonsRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeasonsRepositoryIntegrationTestSuite))
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) seriesFixture() string {
	return fmt.Sprintf(`
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('%[1]s-0001-000000000001', 'Series 1', 'S1', 'Ser1', 'Category 1', '%[1]s-0001')
ON CONFLICT (uuid) DO NOTHING;
`, saveSeasonsPrefix)
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) oneSeason() []*motorsportstats.Season {
	return []*motorsportstats.Season{
		{
			UUID:    saveSeasonsPrefix + "-0002-000000000001",
			Name:    fn.Ptr("2023 Championship"),
			Year:    fn.Ptr(2023),
			EndYear: fn.Ptr(2024),
			Status:  fn.Ptr("in progress"),
		},
	}
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) multipleSeasons() []*motorsportstats.Season {
	return []*motorsportstats.Season{
		{
			UUID:    saveSeasonsPrefix + "-0003-000000000001",
			Name:    fn.Ptr("2023 Championship"),
			Year:    fn.Ptr(2023),
			EndYear: fn.Ptr(2024),
			Status:  fn.Ptr("in progress"),
		},
		{
			UUID:    saveSeasonsPrefix + "-0003-000000000002",
			Name:    fn.Ptr("2022 Championship"),
			Year:    fn.Ptr(2022),
			EndYear: fn.Ptr(2023),
			Status:  fn.Ptr("historic"),
		},
		{
			UUID:    saveSeasonsPrefix + "-0003-000000000003",
			Name:    nil,
			Year:    nil,
			EndYear: nil,
			Status:  nil,
		},
	}
}
