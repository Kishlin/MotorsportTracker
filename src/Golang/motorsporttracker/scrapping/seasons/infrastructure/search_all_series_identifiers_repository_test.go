package infrastructure

import (
	"fmt"
	"os"
	"strings"
	"testing"

	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// searchAllSeriesIdentifiersPrefix namespaces this suite's UUIDs and searchable strings.
// Suites share core-test and run concurrently, so a keyword without it can match another suite's rows.
const searchAllSeriesIdentifiersPrefix = "90772f9f-4ce9-4e9f-9581"

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
	for _, table := range []string{"series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, searchAllSeriesIdentifiersPrefix)
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
		if strings.HasPrefix(id, searchAllSeriesIdentifiersPrefix+"-") {
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
	return fmt.Sprintf(`
INSERT INTO series (uuid, name, short_name, short_code, category, hash) VALUES 
('%[1]s-000000000001', 'Test Series Match 1 %[1]s', 'ShortTest1 %[1]s', 'SerTest1 %[1]s', 'Category 1', '%[1]s-000000000001'),
('%[1]s-000000000002', 'Test Series Match 2 %[1]s', 'ShortTest2 %[1]s', 'SerTest2 %[1]s', 'Category 2', '%[1]s-000000000002'),
('%[1]s-000000000003', 'Test Series Match 3 %[1]s', null, 'SerTest3 %[1]s', 'Category 3', '%[1]s-000000000003')
ON CONFLICT (uuid) DO NOTHING;
`, searchAllSeriesIdentifiersPrefix)
}
