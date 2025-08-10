package connector

import (
	"fmt"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/cache"
)

type CachedConnector struct {
	inner Connector
	cache cache.Cache
}

// NewCachedConnector creates a new CachedConnector with the provided inner connector and cache.
func NewCachedConnector(inner Connector, cache cache.Cache) *CachedConnector {
	return &CachedConnector{
		inner: inner,
		cache: cache,
	}
}

// Get retrieves data from the cache or the inner connector if not found in the cache.
func (c *CachedConnector) Get(url string) ([]byte, error) {
	namespace, key := c.namespaceAndKeyFromUrl(url)

	data, hit, err := c.cache.Get(namespace, key)
	if err != nil {
		return nil, fmt.Errorf("getting data from cache: %w", err)
	}
	if !hit {
		slog.Debug("Cache miss for %s/%s, fetching from inner connector", namespace, key)

		data, err = c.inner.Get(url)
		if err != nil {
			return nil, fmt.Errorf("getting data from inner connector: %w", err)
		}

		if err := c.cache.Set(namespace, key, data); err != nil {
			return nil, fmt.Errorf("setting data in cache: %w", err)
		}
	}

	return data, nil
}

func (c *CachedConnector) namespaceAndKeyFromUrl(_ string) (namespace, key string) {
	return "series", "all"
}
