package queue

import (
	"fmt"
	"os"
)

// QueueType represents the type of queue to use
type QueueType string

const (
	// MemoryQueueType represents an in-memory queue implementation
	MemoryQueueType QueueType = "memory"

	// SQSQueueType represents an AWS SQS queue implementation
	SQSQueueType QueueType = "sqs"
)

// Default SQS queue URL for local development
const defaultLocalSQSQueueURL = "http://localhost:9324/queue/ScrappingIntents"

// QueueFactory creates queue instances based on configuration
func QueueFactory(queueType QueueType) (Queue, error) {
	switch queueType {
	case MemoryQueueType:
		return NewMemoryQueue(), nil
	case SQSQueueType:
		// Get queue URL from environment variable or use default local URL
		queueURL := os.Getenv("SQS_QUEUE_URL")
		if queueURL == "" {
			queueURL = defaultLocalSQSQueueURL
		}
		return NewSQSQueue(queueURL), nil
	default:
		return nil, fmt.Errorf("unknown queue type: %s", queueType)
	}
}
