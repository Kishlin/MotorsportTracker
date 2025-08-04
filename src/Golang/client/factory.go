package client

type ConnectorFactory interface {
	NewConnector() Connector
}

type DefaultConnectorFactory struct{}

// NewConnector creates a new instance of MotorsportConnector.
func (f *DefaultConnectorFactory) NewConnector() Connector {
	return NewConnector()
}

// NewDefaultConnectorFactory creates a new instance of DefaultConnectorFactory.
func NewDefaultConnectorFactory() ConnectorFactory {
	return &DefaultConnectorFactory{}
}
