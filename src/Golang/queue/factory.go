package queue

import (
	"fmt"
	"os"
	"strings"
)

type Name string

const (
	ScrappingIntentsQueue Name = "QUEUE_SCRAPPING_INTENTS"
)

// Type represents the type of queue to use
type Type string

const (
	// MemoryQueueType represents an in-memory queue implementation
	MemoryQueueType Type = "memory"

	// SQSQueueType represents an AWS SQS queue implementation
	SQSQueueType Type = "sqs"
)

// GetQueueTypeFromEnv determines which queue type to use based on environment variables
func GetQueueTypeFromEnv() Type {
	queueType := os.Getenv("QUEUE_TYPE")
	switch strings.ToLower(queueType) {
	case "sqs":
		return SQSQueueType
	case "memory":
		return MemoryQueueType
	default:
		// Default to memory queue if not specified
		return MemoryQueueType
	}
}

// Factory creates queue instances based on configuration
func Factory(name Name) (Queue, error) {
	queueType := GetQueueTypeFromEnv()

	switch queueType {
	case MemoryQueueType:
		return NewMemoryQueue(), nil
	case SQSQueueType:
		config := SQSConfig{
			QueueName:    getEnv(string(name)),
			Endpoint:     getEnv("SQS_ENDPOINT"),
			Region:       getEnv("SQS_REGION"),
			AccessKey:    getEnv("SQS_ACCESS_KEY"),
			SecretKey:    getEnv("SQS_SECRET_KEY"),
			SessionToken: getEnv("SQS_SESSION_TOKEN"),
		}

		return NewSQSQueueWithConfig(config), nil
	default:
		return nil, fmt.Errorf("unknown queue type: %s", queueType)
	}
}

// getEnv gets an environment variable value
func getEnv(key string) string {
	return os.Getenv(key)
}
