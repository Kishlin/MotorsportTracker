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

func TestCachedConnector_NamespaceAndKeyFromUrl(t *testing.T) {
	connector := setupCachedConnector()

	for name, testCase := range map[string]struct {
		url               string
		expectedNamespace string
		expectedKey       string
		shouldSucceed     bool
	}{
		"Series Url": {
			url:               seriesUrl,
			expectedNamespace: "series",
			expectedKey:       "all",
			shouldSucceed:     true,
		},
		"Seasons Url": {
			url:               "https://api.motorsportstats.com/widgets/1.0.0/series/f1-uuid/seasons",
			expectedNamespace: "seasons",
			expectedKey:       "f1-uuid",
			shouldSucceed:     true,
		},
		"Unexpected Url": {
			url:           "https://api.motorsportstats.com/widgets/1.0.0/unknown/endpoint",
			shouldSucceed: false,
		},
	} {
		t.Run(name, func(t *testing.T) {
			namespace, key, err := connector.namespaceAndKeyFromUrl(testCase.url)
			if (err == nil) != testCase.shouldSucceed {
				t.Errorf("Expected success: %v, got error: %v", testCase.shouldSucceed, err)
				return
			}

			if namespace != testCase.expectedNamespace {
				t.Errorf("Expected namepsace '%s', got '%s'", testCase.expectedNamespace, namespace)
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
