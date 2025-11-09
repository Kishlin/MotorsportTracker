package infrastructure

type MessageHandle string

// Message that can be sent to a queue
type Message struct {
	Type     string            // Type of the message
	Metadata map[string]string // Additional data required for processing
}
