package queue

import (
	"sync"
)

// MemoryQueue implements Queue interface using an in-memory slice
// Useful for testing and local development
type MemoryQueue struct {
	messages []Message
	mutex    sync.Mutex
}

// NewMemoryQueue creates a new in-memory queue
func NewMemoryQueue() *MemoryQueue {
	return &MemoryQueue{
		messages: []Message{},
	}
}

// Send adds a message to the in-memory queue
func (q *MemoryQueue) Send(message Message) error {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	q.messages = append(q.messages, message)
	return nil
}

// Receive fetches messages from the in-memory queue
func (q *MemoryQueue) Receive(maxMessages int) ([]Message, error) {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	if len(q.messages) == 0 {
		return []Message{}, nil
	}

	count := min(maxMessages, len(q.messages))
	result := make([]Message, count)
	copy(result, q.messages[:count])

	return result, nil
}

// Delete removes a message from the in-memory queue
func (q *MemoryQueue) Delete(message Message) error {
	// For simplicity in this demo implementation, we'll just remove the first matching message
	// In a real implementation, you might want to use message IDs
	q.mutex.Lock()
	defer q.mutex.Unlock()

	for i, msg := range q.messages {
		if msg.Type == message.Type && msg.Target == message.Target {
			// Remove the message by slicing
			q.messages = append(q.messages[:i], q.messages[i+1:]...)
			break
		}
	}

	return nil
}

// Connect is a no-op for memory queue
func (q *MemoryQueue) Connect() error {
	return nil
}

// Disconnect is a no-op for memory queue
func (q *MemoryQueue) Disconnect() error {
	return nil
}

// min returns the smaller of x or y
func min(x, y int) int {
	if x < y {
		return x
	}
	return y
}
