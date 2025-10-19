package infrastructure

import (
	"os"
	"testing"

	"github.com/jackc/pgx/v5"
	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

const seriesRef = "00000000-0000-0000-0000-000000000001"

type SaveSeasonsRepositoryFunctionalTestSuite struct {
	suite.Suite

	repository *SaveSeasonsRepository

	resetEnv func()
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), seriesRefFixtures()))

	suite.repository = NewSaveSeasonsRepository(db)
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TearDownTest() {
	sql1 := "TRUNCATE TABLE series RESTART IDENTITY CASCADE;"
	sql2 := "TRUNCATE TABLE seasons RESTART IDENTITY CASCADE;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql2))
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql1))
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TestSaveSeasons() {
	suite.T().Run("no-op when given an empty list", func(t *testing.T) {
		err := suite.repository.SaveSeasons(t.Context(), seriesRef, []*domain.Season{})
		require.NoError(t, err)
		requireSavedSeasonsCount(t, suite.repository.db, 0)
	})

	suite.T().Run("saves seasons into the database", func(t *testing.T) {
		seasonsToSave := []*domain.Season{
			{UUID: "00000000-0000-0000-0000-000000000001", Name: "2023", Year: 2023, EndYear: 2024, Status: "in progress"},
			{UUID: "00000000-0000-0000-0000-000000000002", Name: "2022", Year: 2022, EndYear: 2023, Status: "completed"},
			{UUID: "00000000-0000-0000-0000-000000000003", Name: "2021", Year: 2021, EndYear: 2022, Status: "completed"},
		}

		err := suite.repository.SaveSeasons(t.Context(), seriesRef, seasonsToSave)
		require.NoError(t, err)
		requireSavedSeasonsCount(t, suite.repository.db, 3)
	})
}

func TestFunctional_SaveSeasonsRepository(t *testing.T) {
	suite.Run(t, new(SaveSeasonsRepositoryFunctionalTestSuite))
}

func requireSavedSeasonsCount(t *testing.T, db *database.PGXPoolAdapter, expectedCount int) {
	rows := fn.MustReturn(
		db.Query(t.Context(), "SELECT COUNT(1) FROM seasons;"),
	).(pgx.Rows)
	defer rows.Close()

	require.True(t, rows.Next())

	var count int
	fn.Must(rows.Scan(&count))
	require.Equal(t, expectedCount, count)
}

func seriesRefFixtures() string {
	return `
INSERT INTO series (id, uuid, name, short_name, short_code, category) VALUES 
(1, '00000000-0000-0000-0000-000000000001', 'Series 1', 'S1', 'Ser1', 'Category 1');
`
}
