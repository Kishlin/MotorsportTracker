package connector

import (
	"fmt"
	"log"

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
	table, key := c.tableAndKeyFromUrl(url)

	data, hit, err := c.cache.Get(table, key)
	if err != nil {
		return nil, fmt.Errorf("failed to get data from cache: %w", err)
	}
	if !hit {
		log.Printf("Cache miss for table %s, key %s", table, key)

		data, err = c.inner.Get(url)
		if err != nil {
			return nil, fmt.Errorf("failed to get data from inner connector: %w", err)
		}

		if err := c.cache.Set(table, key, data); err != nil {
			return nil, fmt.Errorf("failed to set data in cache: %w", err)
		}
	}

	return data, nil
}

func (c *CachedConnector) tableAndKeyFromUrl(_ string) (table, key string) {
	return "series", "all"
}
