package messaging

type MessageHandle string

// Message that can be sent to a queue
type Message struct {
	Type     string            // Type of the message
	Metadata map[string]string // Additional data required for processing
}

// Queue defines the interface for queue operations
type Queue interface {
	// Send adds a message to the queue
	Send(message Message) error

	// Receive fetches messages from the queue
	Receive(maxMessages int) (map[MessageHandle]Message, error)

	// Delete removes a message from the queue after successful processing
	Delete(handle MessageHandle) error

	// Connect establishes the connection with the queue system
	Connect() error

	// Disconnect closes the connection with the queue system
	Disconnect()
}
