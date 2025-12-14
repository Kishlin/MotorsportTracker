package infrastructure

import (
	"os"
	"strings"
	"testing"

	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SearchAllSeriesIdentifiersRepository

	resetEnv func()
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))

	suite.repository = NewSearchAllSeriesIdentifiersRepository(db)
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) TearDownSuite() {
	cleanUps := []string{
		"DELETE FROM series WHERE uuid::text LIKE '90772f9f-4ce9-4e9f-9581-%';",
		"DELETE FROM series_history WHERE uuid::text LIKE '90772f9f-4ce9-4e9f-9581-%';",
	}
	for _, query := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) TestGetAllSeriesIdentifiers() {
	suite.T().Run("it retrieves nothing when there are no series", func(t *testing.T) {
		suite.withNoSeriesInDB()

		identifiers, err := suite.repository.GetAllSeriesIdentifiers(t.Context())
		suite.NoError(err)

		suite.requireCountForTest(0, identifiers)
	})

	suite.T().Run("it retrieves all the available identifiers", func(t *testing.T) {
		suite.withSeriesInDB()

		identifiers, err := suite.repository.GetAllSeriesIdentifiers(t.Context())
		suite.NoError(err)

		suite.requireCountForTest(3, identifiers)
	})
}

func TestIntegration_SearchAllSeriesIdentifiersRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite))
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) requireCountForTest(expected int, identifers []string) {
	suite.T().Helper()

	count := 0
	for _, id := range identifers {
		if strings.HasPrefix(id, "90772f9f-4ce9-4e9f-9581-") {
			count++
		}
	}

	suite.Equal(expected, count)
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) withNoSeriesInDB() {}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) withSeriesInDB() {
	fn.Must(suite.repository.db.Exec(suite.T().Context(), suite.seriesFixtures()))
}

func (suite *SearchAllSeriesIdentifiersRepositoryIntegrationTestSuite) seriesFixtures() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('90772f9f-4ce9-4e9f-9581-000000000001', 'Test Series Match 1', 'ShortTest1', 'SerTest1', 'Category 1', '90772f9f-4ce9-4e9f-9581-000000000001'),
('90772f9f-4ce9-4e9f-9581-000000000002', 'Test Series Match 2', 'ShortTest2', 'SerTest2', 'Category 2', '90772f9f-4ce9-4e9f-9581-000000000002'),
('90772f9f-4ce9-4e9f-9581-000000000003', 'Test Series Match 3', null, 'SerTest3', 'Category 3', '90772f9f-4ce9-4e9f-9581-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`
}
