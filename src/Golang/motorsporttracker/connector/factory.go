package connector

import "github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/cache"

type Factory interface {
	NewConnector() Connector
}

type DefaultConnectorFactory struct{}

// NewConnector creates a new instance of MotorsportStatsConnector.
func (f *DefaultConnectorFactory) NewConnector() Connector {
	return NewConnector()
}

// NewDefaultConnectorFactory creates a new instance of DefaultConnectorFactory.
func NewDefaultConnectorFactory() Factory {
	return &DefaultConnectorFactory{}
}

type InMemoryConnectorFactory struct{}

// NewConnector creates a new instance of InMemoryConnector.
func (f *InMemoryConnectorFactory) NewConnector() Connector {
	return NewInMemoryConnector(make(map[string]MockResponse))
}

// NewInMemoryConnectorFactory creates a new instance of InMemoryConnectorFactory.
func NewInMemoryConnectorFactory() Factory {
	return &InMemoryConnectorFactory{}
}

type CachedConnectorFactory struct{}

// NewConnector creates a new instance of CachedConnector.
func (f *CachedConnectorFactory) NewConnector(inner Connector, cache cache.Cache) Connector {
	return NewCachedConnector(inner, cache)
}

// NewCachedConnectorFactory creates a new instance of CachedConnectorFactory.
func NewCachedConnectorFactory() *CachedConnectorFactory {
	return &CachedConnectorFactory{}
}
