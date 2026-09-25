package infrastructure

import (
	"context"
	"fmt"
	"os"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type CacheUsingDatabaseFunctionalTestSuite struct {
	suite.Suite

	cache *DatabaseCache
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) SetupSuite() {
	resetEnv := env.OverrideAppEnv("tests")
	defer resetEnv()

	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CLIENT_CACHE_URL"))
	err := db.Connect(context.Background())
	require.NoError(suite.T(), err)

	suite.cache = NewDatabaseCache(db)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TearDownSuite() {
	suite.cache.db.Close()
}

// cacheUsingDatabasePrefix namespaces this suite's cache keys: teardown deletes only its own rows.
const cacheUsingDatabasePrefix = "6e6e0ce4-a7a7-4047"

var setupQuery = fmt.Sprintf("INSERT INTO series (key, value) VALUES ('%s-existing_key', 'existing_value')", cacheUsingDatabasePrefix)

func (suite *CacheUsingDatabaseFunctionalTestSuite) SetupTest() {
	err := suite.cache.db.Exec(context.Background(), setupQuery)
	require.NoError(suite.T(), err)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TearDownTest() {
	err := suite.cache.db.Exec(context.Background(), fmt.Sprintf("DELETE FROM series WHERE key LIKE '%s-%%'", cacheUsingDatabasePrefix))
	require.NoError(suite.T(), err)
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TestGet() {
	actual, hit, err := suite.cache.Get("series", cacheUsingDatabasePrefix+"-missing_key")
	require.NoError(suite.T(), err)
	require.False(suite.T(), hit)
	require.Nil(suite.T(), actual)

	actual, hit, err = suite.cache.Get("series", cacheUsingDatabasePrefix+"-existing_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "existing_value", string(actual))
}

func (suite *CacheUsingDatabaseFunctionalTestSuite) TestSet() {
	// Test adding new key
	err := suite.cache.Set("series", cacheUsingDatabasePrefix+"-new_key", []byte("test_value"))
	require.NoError(suite.T(), err)

	actual, hit, err := suite.cache.Get("series", cacheUsingDatabasePrefix+"-new_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "test_value", string(actual))

	// Test updating existing key
	err = suite.cache.Set("series", cacheUsingDatabasePrefix+"-existing_key", []byte("updated_value"))
	require.NoError(suite.T(), err)

	actual, hit, err = suite.cache.Get("series", cacheUsingDatabasePrefix+"-existing_key")
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), "updated_value", string(actual))
}

func TestFunctional_CacheUsingDatabase(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(CacheUsingDatabaseFunctionalTestSuite))
}

type CacheUsingDatabaseNamespaceUnitTestSuite struct {
	suite.Suite
}

func (suite *CacheUsingDatabaseNamespaceUnitTestSuite) TestRejectsInvalidNamespace() {
	invalidNamespaces := map[string]string{
		"sql injection":    "series; DROP TABLE series",
		"hyphen":           "series-cache",
		"uppercase":        "Series",
		"digit":            "series1",
		"empty":            "",
		"schema qualified": "public.series",
		"trailing space":   "series ",
	}

	// A nil pool is deliberate: validation must reject the namespace before any database access.
	cache := NewDatabaseCache(nil)

	for name, namespace := range invalidNamespaces {
		suite.Run(name, func() {
			_, hit, err := cache.Get(namespace, "key")
			require.Error(suite.T(), err)
			require.False(suite.T(), hit)
			require.ErrorContains(suite.T(), err, "invalid cache namespace")

			err = cache.Set(namespace, "key", []byte("value"))
			require.Error(suite.T(), err)
			require.ErrorContains(suite.T(), err, "invalid cache namespace")
		})
	}
}

func (suite *CacheUsingDatabaseNamespaceUnitTestSuite) TestAcceptsValidNamespace() {
	for _, namespace := range []string{"series", "seasons", "calendar", "classification", "client_cache"} {
		require.NoError(suite.T(), assertValidNamespace(namespace), namespace)
	}
}

func TestUnit_CacheUsingDatabaseNamespace(t *testing.T) {
	suite.Run(t, new(CacheUsingDatabaseNamespaceUnitTestSuite))
}
