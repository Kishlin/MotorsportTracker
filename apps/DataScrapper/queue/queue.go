package queue

// Message represents a scraping or processing intent that can be sent to a queue
type Message struct {
	Type     string            // Type of the intent (e.g., "scrap_series", "scrap_season", etc.)
	Target   string            // Target of the scraping (e.g., series name, season identifier)
	Metadata map[string]string // Additional data required for processing
}

// Queue defines the interface for queue operations
type Queue interface {
	// Send adds a message to the queue
	Send(message Message) error

	// Receive fetches messages from the queue
	Receive(maxMessages int) ([]Message, error)

	// Delete removes a message from the queue after successful processing
	Delete(message Message) error

	// Connect establishes the connection with the queue system
	Connect() error

	// Disconnect closes the connection with the queue system
	Disconnect() error
}
