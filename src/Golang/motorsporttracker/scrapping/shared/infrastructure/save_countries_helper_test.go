package infrastructure

import (
	"fmt"
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// saveCountryHelperPrefix namespaces every UUID this suite writes; see scripts/fixture-prefix-check.sh.
const saveCountryHelperPrefix = "22b1a818-97f2-43d0"

type SaveCountryHelperIntegrationTestSuite struct {
	suite.Suite

	db     *database.PGXPoolAdapter
	helper *SaveRepositoryHelper

	resetEnv func()
}

func (suite *SaveCountryHelperIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	suite.db = database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(suite.db.Connect(suite.T().Context()))

	suite.helper = NewSaveRepositoryHelper(suite.db)
}

func (suite *SaveCountryHelperIntegrationTestSuite) TearDownSuite() {
	for _, table := range []string{"countries", "countries_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, saveCountryHelperPrefix)
		fn.Must(suite.db.Exec(suite.T().Context(), query))
	}

	suite.db.Close()
	suite.resetEnv()
}

func (suite *SaveCountryHelperIntegrationTestSuite) TestSaveCountry() {
	suite.Run("no-op when then are no countries", func() {
		countries := suite.emptyCountriesList()
		err := SaveCountries(suite.T().Context(), suite.db, countries)
		suite.NoError(err)
	})

	suite.Run("saves one country", func() {
		countries := suite.singleCountryList()
		err := SaveCountries(suite.T().Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "countries", saveCountryHelperPrefix+"-0001-%"))
	})

	suite.Run("saves a country with nil values", func() {
		countries := suite.countryWithNilValues()
		err := SaveCountries(suite.T().Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "countries", saveCountryHelperPrefix+"-0002-%"))
	})

	suite.Run("saves multiple countries", func() {
		countries := suite.multipleCountriesList()
		err := SaveCountries(suite.T().Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(suite.T().Context(), "countries", saveCountryHelperPrefix+"-0003-%"))
	})
}

func TestIntegration_SaveCountryHelper(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveCountryHelperIntegrationTestSuite))
}

func (suite *SaveCountryHelperIntegrationTestSuite) emptyCountriesList() []*motorsportstats.Country {
	return []*motorsportstats.Country{}
}

func (suite *SaveCountryHelperIntegrationTestSuite) singleCountryList() []*motorsportstats.Country {
	return []*motorsportstats.Country{
		{
			UUID: saveCountryHelperPrefix + "-0001-000000000001",
			Name: fn.Ptr("Country"),
			Flag: fn.Ptr("fl.svg"),
		},
	}
}

func (suite *SaveCountryHelperIntegrationTestSuite) countryWithNilValues() []*motorsportstats.Country {
	return []*motorsportstats.Country{
		{
			UUID: saveCountryHelperPrefix + "-0002-000000000001",
		},
	}
}

func (suite *SaveCountryHelperIntegrationTestSuite) multipleCountriesList() []*motorsportstats.Country {
	return []*motorsportstats.Country{
		{
			UUID: saveCountryHelperPrefix + "-0003-000000000001",
		},
		{
			UUID: saveCountryHelperPrefix + "-0003-000000000002",
		},
		{
			UUID: saveCountryHelperPrefix + "-0003-000000000003",
		},
	}
}
