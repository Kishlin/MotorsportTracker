package queue

import (
	"github.com/google/uuid"
	"sync"
)

// MemoryQueue implements Queue interface using in-memory maps
type MemoryQueue struct {
	hiddenMessages map[MessageHandle]Message
	messages       map[MessageHandle]Message
	mutex          sync.Mutex
}

// NewMemoryQueue creates a new in-memory queue
func NewMemoryQueue() *MemoryQueue {
	return &MemoryQueue{
		messages:       make(map[MessageHandle]Message),
		hiddenMessages: make(map[MessageHandle]Message),
	}
}

// Send adds a message to the in-memory queue
func (q *MemoryQueue) Send(message Message) error {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	// Generate a unique handle for the message
	handle, err := uuid.NewUUID()
	if err != nil {
		return err
	}

	// Use the handle as the key in the map
	messageHandle := MessageHandle(handle.String())

	// Store the message in the map
	q.messages[messageHandle] = message

	return nil
}

// Receive fetches messages from the in-memory queue
func (q *MemoryQueue) Receive(maxMessages int) (map[MessageHandle]Message, error) {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	messages := make(map[MessageHandle]Message)

	if len(q.messages) == 0 {
		return messages, nil
	}

	limit := min(maxMessages, len(q.messages))

	for handle, msg := range q.messages {
		if len(messages) >= limit {
			break
		}

		if _, exists := q.hiddenMessages[handle]; !exists {
			q.hiddenMessages[handle] = msg
			messages[handle] = msg

			delete(q.messages, handle)
		}
	}

	return messages, nil
}

// Delete removes a message from the in-memory queue
func (q *MemoryQueue) Delete(handle MessageHandle) error {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	if _, exists := q.hiddenMessages[handle]; exists {
		delete(q.hiddenMessages, handle)
	}

	return nil
}

// Connect is a no-op for memory queue
func (q *MemoryQueue) Connect() error {
	return nil
}

// Disconnect is a no-op for memory queue
func (q *MemoryQueue) Disconnect() {
	return
}
