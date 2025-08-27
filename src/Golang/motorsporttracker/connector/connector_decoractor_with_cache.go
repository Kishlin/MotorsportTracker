package connector

import (
	"fmt"
	"log/slog"
	"strings"

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
	namespace, key, err := c.namespaceAndKeyFromUrl(url)
	if err != nil {
		return nil, fmt.Errorf("determining namespace and key from url: %w", err)
	}

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

func (c *CachedConnector) namespaceAndKeyFromUrl(url string) (namespace, key string, err error) {
	if strings.HasSuffix(url, "/series") {
		return "series", "all", nil
	}

	parts := strings.Split(url, "/")
	if len(parts) >= 2 && parts[len(parts)-1] == "seasons" {
		// Url is like  "https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons" and we want to extract the %s part
		return "seasons", parts[len(parts)-2], nil
	}

	return "", "", fmt.Errorf("unable to determine namespace and key from url: %s", url)
}
