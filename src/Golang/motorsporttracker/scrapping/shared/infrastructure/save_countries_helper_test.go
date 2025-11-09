package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

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
	cleanUps := []string{
		`DELETE FROM countries WHERE uuid::text LIKE '22b1a818-97f2-43d0-%';`,
		`DELETE FROM countries_history WHERE uuid::text LIKE '22b1a818-97f2-43d0-%';`,
	}
	for _, sql := range cleanUps {
		fn.Must(suite.db.Exec(suite.T().Context(), sql))
	}

	suite.db.Close()
	suite.resetEnv()
}

func (suite *SaveCountryHelperIntegrationTestSuite) TestSaveCountry() {
	suite.T().Run("no-op when then are no countries", func(t *testing.T) {
		countries := suite.emptyCountriesList()
		err := SaveCountries(t.Context(), suite.db, countries)
		suite.NoError(err)
	})

	suite.T().Run("saves one country", func(t *testing.T) {
		countries := suite.singleCountryList()
		err := SaveCountries(t.Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "countries", "22b1a818-97f2-43d0-0001-%"))
	})

	suite.T().Run("saves a country with nil values", func(t *testing.T) {
		countries := suite.countryWithNilValues()
		err := SaveCountries(t.Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "countries", "22b1a818-97f2-43d0-0002-%"))
	})

	suite.T().Run("saves multiple countries", func(t *testing.T) {
		countries := suite.multipleCountriesList()
		err := SaveCountries(t.Context(), suite.db, countries)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(t.Context(), "countries", "22b1a818-97f2-43d0-0003-%"))
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
			UUID: "22b1a818-97f2-43d0-0001-000000000001",
			Name: fn.Ptr("Country"),
			Flag: fn.Ptr("fl.svg"),
		},
	}
}

func (suite *SaveCountryHelperIntegrationTestSuite) countryWithNilValues() []*motorsportstats.Country {
	return []*motorsportstats.Country{
		{
			UUID: "22b1a818-97f2-43d0-0002-000000000001",
		},
	}
}

func (suite *SaveCountryHelperIntegrationTestSuite) multipleCountriesList() []*motorsportstats.Country {
	return []*motorsportstats.Country{
		{
			UUID: "22b1a818-97f2-43d0-0003-000000000001",
		},
		{
			UUID: "22b1a818-97f2-43d0-0003-000000000002",
		},
		{
			UUID: "22b1a818-97f2-43d0-0003-000000000003",
		},
	}
}
