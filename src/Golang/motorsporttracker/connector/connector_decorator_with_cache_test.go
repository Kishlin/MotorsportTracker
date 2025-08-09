package connector

import (
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/cache"
)

const seriesUrl = "https://api.motorsportstats.com/widgets/1.0.0/series"

func TestCachedConnector_Get(t *testing.T) {
	t.Run("Sets the cache if empty", func(t *testing.T) {
		connector := setupCachedConnector(
			withSeriesClientResponse(),
			withEmptyClientCache(),
		)

		data, err := connector.Get(seriesUrl)
		if err != nil {
			t.Fatalf("Expected no error, got %v", err)
		}
		if len(data) == 0 {
			t.Fatal("Expected to get data, got empty response")
		}

		// Assert cache state after the test
		assertCacheItemCount(t, connector, 1)
	})
}

func TestCachedConnector_TableFromUrl(t *testing.T) {
	connector := setupCachedConnector()

	for name, testCase := range map[string]struct {
		url               string
		expectedTableName string
		expectedKey       string
	}{
		"Widget Url": {
			url:               seriesUrl,
			expectedTableName: "series",
			expectedKey:       "all",
		},
	} {
		t.Run(name, func(t *testing.T) {
			tableName, key := connector.tableAndKeyFromUrl(testCase.url)
			if tableName != testCase.expectedTableName {
				t.Errorf("Expected table name '%s', got '%s'", testCase.expectedTableName, tableName)
			}
			if key != testCase.expectedKey {
				t.Errorf("Expected key '%s', got '%s'", testCase.expectedKey, key)
			}
		})
	}
}

type cachedConnectorOpt func(connector *InMemoryConnector, cache *cache.InMemoryCache)

func setupCachedConnector(opts ...cachedConnectorOpt) *CachedConnector {
	connector := NewInMemoryConnector(make(map[string]MockResponse))
	inMemoryCache := cache.NewInMemoryCache()

	for _, opt := range opts {
		opt(connector, inMemoryCache)
	}

	return NewCachedConnector(connector, inMemoryCache)
}

func withEmptyClientCache() cachedConnectorOpt {
	return func(_ *InMemoryConnector, _ *cache.InMemoryCache) {}
}

func withSeriesClientResponse() cachedConnectorOpt {
	return func(connector *InMemoryConnector, _ *cache.InMemoryCache) {
		connector.SetMockResponse(
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
}

func assertCacheItemCount(t *testing.T, c *CachedConnector, expected int) {
	if inMemCache, ok := c.cache.(*cache.InMemoryCache); ok {
		if inMemCache.ItemsCount() != expected {
			t.Errorf("Expected %d items in cache, got %d", expected, inMemCache.ItemsCount())
		}
		return
	}

	panic("Cache is not of type InMemoryCache")
}
