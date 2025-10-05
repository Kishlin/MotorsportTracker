package cache

import (
	"context"
	"os"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/env"
	_func "github.com/kishlin/MotorsportTracker/src/Golang/func"
	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type CacheUsingDatabaseFunctionalTestSuite struct {
	suite.Suite

	cache *DatabaseCache
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	_func.Must(env.LoadEnv())

	db := database.NewPGXPoolAdapter(os.Getenv("POSTGRES_CLIENT_CACHE_URL"))
	err := db.Connect(context.Background())
	require.NoError(suite.T(), err)

	suite.cache = NewDatabaseCache(db)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TearDownSuite() {
	suite.cache.db.Close()
}

var setupQuery = "INSERT INTO series (key, value) VALUES ('existing_key', 'existing_value')"

func (suite *CacheUsingDatabaseFunctionalTestSuite) SetupTest() {
	err := suite.cache.db.Exec(context.Background(), setupQuery)
	require.NoError(suite.T(), err)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TearDownTest() {
	//goland:noinspection SqlWithoutWhere
	err := suite.cache.db.Exec(context.Background(), "DELETE FROM series")
	require.NoError(suite.T(), err)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TestGet() {
	actual, hit, err := suite.cache.Get("series", "missing_key")
	require.NoError(suite.T(), err)
	require.False(suite.T(), hit)
	require.Nil(suite.T(), actual)

	actual, hit, err = suite.cache.Get("series", "existing_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "existing_value", string(actual))
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TestSet() {
	// Test adding new key
	err := suite.cache.Set("series", "new_key", []byte("test_value"))
	require.NoError(suite.T(), err)

	actual, hit, err := suite.cache.Get("series", "new_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "test_value", string(actual))

	// Test updating existing key
	err = suite.cache.Set("series", "existing_key", []byte("updated_value"))
	require.NoError(suite.T(), err)

	actual, hit, err = suite.cache.Get("series", "existing_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "updated_value", string(actual))
}

func TestFunctional_CacheUsingDatabase(t *testing.T) {
	suite.Run(t, new(CacheUsingDatabaseFunctionalTestSuite))
}
