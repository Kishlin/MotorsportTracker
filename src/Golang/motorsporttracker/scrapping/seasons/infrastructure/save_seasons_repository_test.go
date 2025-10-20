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

const seriesRef = "e966c934-a354-43c0-80bf-000000000001"

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
	fn.Must(db.Exec(suite.T().Context(), seriesRefFixtures))

	suite.repository = NewSaveSeasonsRepository(db)
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TearDownSuite() {
	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TearDownTest() {
	sql := "DELETE FROM series WHERE uuid::text = $1;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql, seriesRef))
}

func (suite *SaveSeasonsRepositoryFunctionalTestSuite) TestSaveSeasons() {
	seasonsToSave := []*domain.Season{
		{UUID: "75f849b7-35be-44d8-b35f-000000000001", Name: "2023", Year: 2023, EndYear: 2024, Status: "in progress"},
		{UUID: "75f849b7-35be-44d8-b35f-000000000002", Name: "2022", Year: 2022, EndYear: 2023, Status: "completed"},
		{UUID: "75f849b7-35be-44d8-b35f-000000000003", Name: "2021", Year: 2021, EndYear: 2022, Status: "completed"},
	}

	err := suite.repository.SaveSeasons(suite.T().Context(), seriesRef, seasonsToSave)
	require.NoError(suite.T(), err)
	requireSavedSeasonsCount(suite.T(), suite.repository.db, 3)
}

func TestFunctional_SaveSeasonsRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveSeasonsRepositoryFunctionalTestSuite))
}

func requireSavedSeasonsCount(t *testing.T, db *database.PGXPoolAdapter, expectedCount int) {
	rows := fn.MustReturn(db.Query(t.Context(), countSeasonsQuery)).(pgx.Rows)
	defer rows.Close()

	require.True(t, rows.Next())

	var count int
	fn.Must(rows.Scan(&count))
	require.Equal(t, expectedCount, count)
}

const seriesRefFixtures = `
INSERT INTO series (uuid, name, short_name, short_code, category) VALUES 
('e966c934-a354-43c0-80bf-000000000001', 'Series 1', 'S1', 'Ser1', 'Category 1');
`

const countSeasonsQuery = `
SELECT COUNT(1) 
FROM seasons 
INNER JOIN series ON seasons.series = series.id
WHERE series.uuid::text LIKE 'e966c934-a354-43c0-80bf-%';
`
