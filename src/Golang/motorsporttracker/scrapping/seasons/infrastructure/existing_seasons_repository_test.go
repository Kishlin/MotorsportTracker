package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type ExistingSeasonsRepositoryFunctionalTestSuite struct {
	suite.Suite

	repository *ExistingSeasonsRepository

	resetEnv func()
}

func (suite *ExistingSeasonsRepositoryFunctionalTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), seasonsFixture()))

	suite.repository = NewExistingSeasonsRepository(db)
}

func (suite *ExistingSeasonsRepositoryFunctionalTestSuite) TearDownSuite() {
	sql1 := "TRUNCATE TABLE seasons RESTART IDENTITY CASCADE;"
	sql2 := "TRUNCATE TABLE series RESTART IDENTITY CASCADE;"
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql1))
	fn.Must(suite.repository.db.Exec(suite.T().Context(), sql2))

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *ExistingSeasonsRepositoryFunctionalTestSuite) TestGetExistingSeasons() {
	suite.T().Run("returns empty map when no seasons exist for the series", func(t *testing.T) {
		existingSeasons, err := suite.repository.GetExistingSeasons(t.Context(), "00000000-0000-0000-0000-000000000999")
		suite.NoError(err)
		suite.Empty(existingSeasons)
	})

	suite.T().Run("returns map of existing seasons for the series", func(t *testing.T) {
		existingSeasons, err := suite.repository.GetExistingSeasons(t.Context(), "00000000-0000-0000-0000-000000000001")
		suite.NoError(err)
		suite.Len(existingSeasons, 2)
		suite.Contains(existingSeasons, "00000000-0000-0000-0000-000000000001")
		suite.Contains(existingSeasons, "00000000-0000-0000-0000-000000000002")
	})
}

func TestFunctional_ExistingSeasonsRepository(t *testing.T) {
	suite.Run(t, new(ExistingSeasonsRepositoryFunctionalTestSuite))
}

func seasonsFixture() string {
	return `
INSERT INTO series (id, uuid, name, short_code, category) VALUES
(1, '00000000-0000-0000-0000-000000000001', 'Super Racing Series', 'SRS', 'Racing'),
(2, '00000000-0000-0000-0000-000000000002', 'Extreme Racing Series', 'ERS', 'Racing');

INSERT INTO seasons (id, uuid, series, name, year, end_year) VALUES 
(1, '00000000-0000-0000-0000-000000000001', 1, 'Season 1', 2023, 2024),
(2, '00000000-0000-0000-0000-000000000002', 1, 'Season 2', 2022, 2023),
(3, '00000000-0000-0000-0000-000000000003', 2, 'Season 3', 2022, 2023);
`
}
