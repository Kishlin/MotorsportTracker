package infrastructure

import (
	"os"
	"testing"

	"github.com/jackc/pgx/v5"
	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SaveSeriesRepositoryFunctionalTestSuite struct {
	suite.Suite

	repository *SaveSeriesRepository

	resetEnv func()
}

func (suite *SaveSeriesRepositoryFunctionalTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))

	suite.repository = NewSaveSeriesRepository(db)
}

func (suite *SaveSeriesRepositoryFunctionalTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveSeriesRepositoryFunctionalTestSuite) TearDownTest() {
	sql := "DELETE FROM series WHERE uuid::text LIKE '875c810d-a048-414e-a048-%';"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
}

func (suite *SaveSeriesRepositoryFunctionalTestSuite) TestSaveSeries() {
	seriesToSave := []*domain.Series{
		{Name: "World Endurance Championship", ShortName: fn.Ptr("WEC"), ShortCode: "WEC", Category: "Sports Car", UUID: "875c810d-a048-414e-a048-000000000001"},
		{Name: "Formula 1", ShortName: fn.Ptr("F1"), ShortCode: "F1", Category: "Open Wheel", UUID: "875c810d-a048-414e-a048-000000000002"},
		{Name: "MotoGP", ShortName: nil, ShortCode: "MG", Category: "Motorcycle", UUID: "875c810d-a048-414e-a048-000000000003"},
	}

	err := suite.repository.SaveSeries(suite.T().Context(), seriesToSave)
	require.NoError(suite.T(), err)

	requireSavedSeriesCount(suite.T(), suite.repository.db, len(seriesToSave))
}

func TestFunctional_SaveSeriesRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeriesRepositoryFunctionalTestSuite))
}

func requireSavedSeriesCount(t *testing.T, db *database.PGXPoolAdapter, expectedCount int) {
	rows := fn.MustReturn(
		db.Query(t.Context(), "SELECT COUNT(1) FROM series WHERE uuid::text LIKE '875c810d-a048-414e-a048-%';"),
	).(pgx.Rows)
	defer rows.Close()

	require.True(t, rows.Next())

	var count int
	fn.Must(rows.Scan(&count))
	require.Equal(t, expectedCount, count)
}
