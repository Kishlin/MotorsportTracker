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
	sql := "TRUNCATE TABLE series RESTART IDENTITY CASCADE;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
}

func (suite *SaveSeriesRepositoryFunctionalTestSuite) TestSaveSeries() {
	suite.T().Run("no-op when given an empty list", func(t *testing.T) {
		err := suite.repository.SaveSeries(t.Context(), []*domain.Series{})
		require.NoError(t, err)

		requireSavedSeriesCount(t, suite.repository.db, 0)
	})

	suite.T().Run("saves series into the database", func(t *testing.T) {
		seriesToSave := []*domain.Series{
			{Name: "World Endurance Championship", ShortName: fn.Ptr("WEC"), ShortCode: "WEC", Category: "Sports Car", UUID: "967cd5ab-5562-40dc-a0b0-109738adcd01"},
			{Name: "Formula 1", ShortName: fn.Ptr("F1"), ShortCode: "F1", Category: "Open Wheel", UUID: "a33f8b4a-2b22-41ce-8e7d-0aea08f0e176"},
			{Name: "MotoGP", ShortName: nil, ShortCode: "MG", Category: "Motorcycle", UUID: "a485d01c-f907-4ff7-83db-ca1c90cc28a1"},
		}

		err := suite.repository.SaveSeries(t.Context(), seriesToSave)
		require.NoError(t, err)

		requireSavedSeriesCount(t, suite.repository.db, len(seriesToSave))
	})
}

func TestFunctional_SaveSeriesRepository(t *testing.T) {
	suite.Run(t, new(SaveSeriesRepositoryFunctionalTestSuite))
}

func requireSavedSeriesCount(t *testing.T, db *database.PGXPoolAdapter, expectedCount int) {
	rows := fn.MustReturn(
		db.Query(t.Context(), "SELECT COUNT(1) FROM series;"),
	).(pgx.Rows)
	defer rows.Close()

	require.True(t, rows.Next())

	var count int
	fn.Must(rows.Scan(&count))
	require.Equal(t, expectedCount, count)
}
