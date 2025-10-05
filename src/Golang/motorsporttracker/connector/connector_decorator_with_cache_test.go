package connector

import (
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/cache"
	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

const seriesUrl = "https://api.motorsportstats.com/widgets/1.0.0/series"

type ConnectorDecoratorWithCacheUnitTestSuite struct {
	suite.Suite

	connector *CachedConnector
}

func (suite *ConnectorDecoratorWithCacheUnitTestSuite) SetupSuite() {
	innerMemoryConnector := NewInMemoryConnector(make(map[string]MockResponse))
	inMemoryCache := cache.NewInMemoryCache()

	suite.connector = NewCachedConnector(innerMemoryConnector, inMemoryCache)
}

func (suite *ConnectorDecoratorWithCacheUnitTestSuite) TestNamespaceAndKeyFromUrl() {
	namespace, key, err := suite.connector.namespaceAndKeyFromUrl("https://api.motorsportstats.com/widgets/1.0.0/series")
	require.NoError(suite.T(), err)
	require.Equal(suite.T(), "series", namespace)
	require.Equal(suite.T(), "all", key)

	namespace, key, err = suite.connector.namespaceAndKeyFromUrl("https://api.motorsportstats.com/widgets/1.0.0/series/f1-uuid/seasons")
	require.NoError(suite.T(), err)
	require.Equal(suite.T(), "seasons", namespace)
	require.Equal(suite.T(), "f1-uuid", key)

	_, _, err = suite.connector.namespaceAndKeyFromUrl("https://api.motorsportstats.com/widgets/1.0.0/unknown")
	require.Error(suite.T(), err)
}

func (suite *ConnectorDecoratorWithCacheUnitTestSuite) TearDownTest() {
	suite.connector.inner.(*InMemoryConnector).ClearMockResponses()
}

func (suite *ConnectorDecoratorWithCacheUnitTestSuite) TestGet() {
	prepareSeriesClientResponse(suite.connector.inner)

	_, err := suite.connector.Get(seriesUrl)
	require.NoError(suite.T(), err)

	suite.connector.inner.(*InMemoryConnector).ClearMockResponses()

	// Now that the in-memory connector is cleared, it will panic if it is called again.
	// This ensures the cache is used to prevent another call.
	_, err = suite.connector.Get(seriesUrl)
	require.NoError(suite.T(), err)
}

func TestUnit_ConnectorDecoratorWithCache(t *testing.T) {
	suite.Run(t, new(ConnectorDecoratorWithCacheUnitTestSuite))
}

func prepareSeriesClientResponse(connector Connector) {
	connector.(*InMemoryConnector).SetMockResponse(
		seriesUrl,
		MockResponse{
			Err: nil,
			Data: []byte(`{
				"name": "Formula 1",
				"uuid": "f1-uuid",
				"shortName": "F1", 
				"shortCode": "F1",
				"category": "Open Wheel",
			}`),
		},
	)
}
