package messaging

import (
	"errors"
	"log/slog"
	"sync"

	"github.com/google/uuid"
)

// MemoryQueue implements Queue interface using in-memory maps
type MemoryQueue struct {
	hiddenMessages map[MessageHandle]Message
	messages       map[MessageHandle]Message
	isConnected    bool

	mutex sync.Mutex
}

// NewMemoryQueue creates a new in-memory queue
func NewMemoryQueue() *MemoryQueue {
	return &MemoryQueue{
		hiddenMessages: make(map[MessageHandle]Message),
		messages:       make(map[MessageHandle]Message),
		isConnected:    false,
	}
}

// Send adds a message to the in-memory queue
func (q *MemoryQueue) Send(message Message) error {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	if !q.isConnected {
		return errors.New("queue is not connected")
	}

	// Generate a unique handle for the message
	handle, err := uuid.NewUUID()
	if err != nil {
		return err
	}

	// Use the handle as the key in the map
	messageHandle := MessageHandle(handle.String())

	// Store the message in the map
	q.messages[messageHandle] = message

	slog.Debug("Message sent to memory queue", "handle", messageHandle, "type", message.Type)

	return nil
}

// Receive fetches messages from the in-memory queue
func (q *MemoryQueue) Receive(maxMessages int) (map[MessageHandle]Message, error) {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	messages := make(map[MessageHandle]Message)

	if !q.isConnected {
		return messages, errors.New("queue is not connected")
	}

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

			slog.Debug("Message moved to hidden queue", "handle", handle)
		}
	}

	return messages, nil
}

// Delete removes a message from the in-memory queue
func (q *MemoryQueue) Delete(handle MessageHandle) error {
	q.mutex.Lock()
	defer q.mutex.Unlock()

	if !q.isConnected {
		return errors.New("queue is not connected")
	}

	delete(q.hiddenMessages, handle)

	slog.Debug("Message deleted from hidden queue", "handle", handle)

	return nil
}

// Connect is a no-op for memory queue
func (q *MemoryQueue) Connect() error {
	slog.Info("Connecting to memory queue")

	q.isConnected = true

	return nil
}

// Disconnect is a no-op for memory queue
func (q *MemoryQueue) Disconnect() {
	slog.Info("Disconnecting from memory queue")

	q.isConnected = false
}
