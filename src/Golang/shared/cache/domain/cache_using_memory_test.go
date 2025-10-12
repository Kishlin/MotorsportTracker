package domain

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type CacheUsingMemoryUnitTestSuite struct {
	suite.Suite
}

func (suite *CacheUsingMemoryUnitTestSuite) TestNominalUse() {
	cache := NewInMemoryCache()
	require.NotNil(suite.T(), cache)

	namespace := "testNamespace"
	key := "testKey"
	value := []byte("testValue")

	// First get should return nil
	retrievedValue, hit, err := cache.Get(namespace, key)
	require.Nil(suite.T(), err)
	require.False(suite.T(), hit)
	require.Nil(suite.T(), retrievedValue)

	// Count should be 0 when it's empty
	require.Zero(suite.T(), cache.ItemsCount())

	// Set a value in the cache
	err = cache.Set(namespace, key, value)
	require.NoError(suite.T(), err)

	// Search the value from the cache
	retrievedValue, hit, err = cache.Get(namespace, key)
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), value, retrievedValue)

	// Count should be 1 after setting a value
	require.Equal(suite.T(), 1, cache.ItemsCount())

	// Set another value in the same namespace
	anotherKey := "anotherKey"
	anotherValue := []byte("anotherValue")
	err = cache.Set(namespace, anotherKey, anotherValue)
	require.NoError(suite.T(), err)

	// Count should be 2 after setting another value
	require.Equal(suite.T(), 2, cache.ItemsCount())

	// Set the same key again with a different value
	newValue := []byte("newValue")
	err = cache.Set(namespace, key, newValue)
	require.NoError(suite.T(), err)

	// Count should still be 2
	require.Equal(suite.T(), 2, cache.ItemsCount())

	// Get should return the updated value
	retrievedValue, hit, err = cache.Get(namespace, key)
	require.NoError(suite.T(), err)
	require.True(suite.T(), hit)
	require.Equal(suite.T(), newValue, retrievedValue)
}

func TestUnit_CacheUsingMemory(t *testing.T) {
	suite.Run(t, new(CacheUsingMemoryUnitTestSuite))
}
