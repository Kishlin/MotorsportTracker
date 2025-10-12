package domain

import (
	"context"
	"fmt"
	"log/slog"

	cache "github.com/kishlin/MotorsportTracker/src/Golang/shared/cache/domain"
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

func (c *CachedConnector) GetSeries(ctx context.Context) ([]byte, error) {
	return c.getFromCacheOrConnector("series", "all", func() ([]byte, error) {
		return c.inner.GetSeries(ctx)
	})
}

func (c *CachedConnector) GetSeasons(ctx context.Context, seriesUuid string) ([]byte, error) {
	return c.getFromCacheOrConnector("seasons", seriesUuid, func() ([]byte, error) {
		return c.inner.GetSeasons(ctx, seriesUuid)
	})
}

func (c *CachedConnector) getFromCacheOrConnector(namespace string, key string, getFromConnector func() ([]byte, error)) ([]byte, error) {
	data, hit, err := c.cache.Get(namespace, key)
	if err != nil {
		return nil, fmt.Errorf("getting data from cache: %w", err)
	}
	if !hit {
		slog.Debug("Cache miss for %s/%s, fetching from inner connector", namespace, key)

		data, err = getFromConnector()
		if err != nil {
			return nil, fmt.Errorf("getting data from inner connector: %w", err)
		}

		if err := c.cache.Set(namespace, key, data); err != nil {
			return nil, fmt.Errorf("setting data in cache: %w", err)
		}
	}

	return data, nil
}
