package connector

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
