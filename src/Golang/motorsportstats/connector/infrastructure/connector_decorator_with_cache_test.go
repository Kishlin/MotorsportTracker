package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	cache "github.com/kishlin/MotorsportTracker/src/Golang/shared/cache/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type CachedConnectorUnitTestSuite struct {
	suite.Suite
}

func (suite *CachedConnectorUnitTestSuite) TestGetFromCacheOrConnector() {
	cache := cache.NewInMemoryCache()
	fn.Must(cache.Set("namespace", "key", []byte("cached data")))

	decorator := NewCachedConnector(nil, cache)

	suite.T().Run("gets from cache when present", func(t *testing.T) {
		defer func() {
			if r := recover(); r != nil {
				t.Errorf("Unexpected panic: %v", r)
			}
		}()
		data, err := decorator.getFromCacheOrConnector("namespace", "key", func() ([]byte, error) {
			panic("should not be called")
		})
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []byte("cached data"), data)
	})

	suite.T().Run("calls connector when cache miss", func(t *testing.T) {
		data, err := decorator.getFromCacheOrConnector("namespace", "new-key", func() ([]byte, error) {
			return []byte("from connector"), nil
		})
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), []byte("from connector"), data)

		// Make sure the cache has been set too
		val, hit, err := cache.Get("namespace", "new-key")
		require.NoError(suite.T(), err)
		require.True(suite.T(), hit)
		require.Equal(suite.T(), []byte("from connector"), val)
	})
}

func TestUnit_CachedConnector(t *testing.T) {
	suite.Run(t, new(CachedConnectorUnitTestSuite))
}
