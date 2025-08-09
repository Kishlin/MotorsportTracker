package messaging

import (
	"fmt"
	"strings"
)

// QueueConfig holds the configuration for SQS queue connection
type QueueConfig struct {
	Endpoint     string // SQS endpoint URL (e.g., memory:// for memory, https://sqs.region.amazonaws.com for prod)
	Region       string // AWS region (e.g., elasticmq for local, us-east-1 for prod)
	QueueName    string // Name of the SQS queue
	AccessKey    string // AWS access key
	SecretKey    string // AWS secret key
	SessionToken string // AWS session token (optional)
}

type Factory interface {
	NewQueue(config QueueConfig) (Queue, error)
}

type QueueFactory struct{}

func NewQueueFactory() *QueueFactory {
	return &QueueFactory{}
}

func (f *QueueFactory) NewQueue(config QueueConfig) (Queue, error) {
	if strings.HasPrefix(strings.ToLower(config.Endpoint), "memory://") {
		return NewMemoryQueue(), nil
	}

	if strings.HasPrefix(strings.ToLower(config.Endpoint), "https://") ||
		strings.HasPrefix(strings.ToLower(config.Endpoint), "http://") {
		return NewSQSQueueWithConfig(config), nil
	}

	return nil, fmt.Errorf("unsupported queue endpoint: %s", config.Endpoint)
}
