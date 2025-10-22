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

const seriesRef = "75f849b7-35be-44d8-0001-000000000001"

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
	cleanUps := []string{
		"DELETE FROM seasons WHERE uuid::text LIKE '75f849b7-35be-44d8-%';",
		"DELETE FROM seasons_history WHERE uuid::text LIKE '75f849b7-35be-44d8-%';",
		"DELETE FROM series WHERE uuid::text = '75f849b7-35be-44d8-%';",
		"DELETE FROM series_history WHERE uuid::text = '75f849b7-35be-44d8-%';",
	}
	for _, sql := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
	}
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) TestSaveSeasons() {
	suite.T().Run("no-op when no seasons to save", func(t *testing.T) {
		err := suite.repository.SaveSeasons(t.Context(), seriesRef, []*motorsportstats.Season{})
		suite.NoError(err)

		suite.Equal(0, suite.helper.Count(t.Context(), "seasons", "75f849b7-35be-44d8-%"))
	})

	suite.T().Run("saves one season", func(t *testing.T) {
		seasonsToSave := suite.oneSeason()

		err := suite.repository.SaveSeasons(t.Context(), seriesRef, seasonsToSave)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "seasons", "75f849b7-35be-44d8-0002-%"))
	})

	suite.T().Run("saves multiple seasons", func(t *testing.T) {
		seasonsToSave := suite.multipleSeasons()

		err := suite.repository.SaveSeasons(t.Context(), seriesRef, seasonsToSave)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(t.Context(), "seasons", "75f849b7-35be-44d8-0003-%"))
	})
}

func TestIntegration_SaveSeasonsRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeasonsRepositoryIntegrationTestSuite))
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) seriesFixture() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('75f849b7-35be-44d8-0001-000000000001', 'Series 1', 'S1', 'Ser1', 'Category 1', '75f849b7-35be-44d8-0001')
ON CONFLICT (uuid) DO NOTHING;
`
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) oneSeason() []*motorsportstats.Season {
	return []*motorsportstats.Season{
		{
			UUID:    "75f849b7-35be-44d8-0002-000000000001",
			Name:    "2023 Championship",
			Year:    2023,
			EndYear: 2024,
			Status:  "in progress",
		},
	}
}

func (suite *SaveSeasonsRepositoryIntegrationTestSuite) multipleSeasons() []*motorsportstats.Season {
	return []*motorsportstats.Season{
		{
			UUID:    "75f849b7-35be-44d8-0003-000000000001",
			Name:    "2023 Championship",
			Year:    2023,
			EndYear: 2024,
			Status:  "in progress",
		},
		{
			UUID:    "75f849b7-35be-44d8-0003-000000000002",
			Name:    "2022 Championship",
			Year:    2022,
			EndYear: 2023,
			Status:  "historic",
		},
		{
			UUID:    "75f849b7-35be-44d8-0003-000000000003",
			Name:    "2021 Championship",
			Year:    2021,
			EndYear: 2022,
			Status:  "historic",
		},
	}
}
