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

// QueueFactory creates queue instances based on configuration
func QueueFactory(queueType QueueType) (Queue, error) {
	switch queueType {
	case MemoryQueueType:
		return NewMemoryQueue(), nil
	case SQSQueueType:
		// In a real implementation, you'd get these from environment variables or config
		queueURL := os.Getenv("SQS_QUEUE_URL")
		if queueURL == "" {
			return nil, fmt.Errorf("SQS_QUEUE_URL environment variable is not set")
		}
		return NewSQSQueue(queueURL), nil
	default:
		return nil, fmt.Errorf("unknown queue type: %s", queueType)
	}
}
