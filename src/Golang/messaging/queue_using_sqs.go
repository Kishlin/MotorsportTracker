package messaging

import (
	"encoding/json"
	"fmt"
	"log"

	"github.com/aws/aws-sdk-go/aws"
	"github.com/aws/aws-sdk-go/aws/credentials"
	"github.com/aws/aws-sdk-go/aws/session"
	"github.com/aws/aws-sdk-go/service/sqs"
)

// SQSConfig holds the configuration for SQS queue connection
type SQSConfig struct {
	Endpoint     string // SQS endpoint URL (e.g., http://localhost:9324 for local, https://sqs.region.amazonaws.com for prod)
	Region       string // AWS region (e.g., elasticmq for local, us-east-1 for prod)
	QueueName    string // Name of the SQS queue
	AccessKey    string // AWS access key
	SecretKey    string // AWS secret key
	SessionToken string // AWS session token (optional)
}

// SQSQueue implements Queue interface using AWS SQS
type SQSQueue struct {
	client    *sqs.SQS
	queueURL  string
	queueName string
	config    SQSConfig
}

// NewSQSQueueWithConfig creates a new SQS queue client with the provided configuration
func NewSQSQueueWithConfig(config SQSConfig) *SQSQueue {
	return &SQSQueue{
		queueName: config.QueueName,
		config:    config,
	}
}

// Connect establishes the connection with AWS SQS
func (q *SQSQueue) Connect() error {
	// Create AWS credentials from config
	creds := credentials.NewStaticCredentials(
		q.config.AccessKey,
		q.config.SecretKey,
		q.config.SessionToken,
	)

	// Create AWS session with config
	sess, err := session.NewSession(&aws.Config{
		Region:      aws.String(q.config.Region),
		Endpoint:    aws.String(q.config.Endpoint),
		Credentials: creds,
	})
	if err != nil {
		return fmt.Errorf("failed to create AWS session: %v", err)
	}

	// Create SQS client
	q.client = sqs.New(sess)

	// Get queue URL if not already set
	if q.queueURL == "" {
		result, err := q.client.GetQueueUrl(&sqs.GetQueueUrlInput{
			QueueName: aws.String(q.queueName),
		})
		if err != nil {
			return fmt.Errorf("failed to get queue URL: %v", err)
		}
		q.queueURL = *result.QueueUrl
	}

	log.Printf("Successfully connected to SQS queue: %s", q.queueName)
	return nil
}

// Send adds a message to the SQS queue
func (q *SQSQueue) Send(message Message) error {
	// Convert message to JSON
	messageBody, err := json.Marshal(message)
	if err != nil {
		return fmt.Errorf("failed to marshal message: %v", err)
	}

	// Send message to SQS
	_, err = q.client.SendMessage(&sqs.SendMessageInput{
		MessageBody: aws.String(string(messageBody)),
		QueueUrl:    aws.String(q.queueURL),
	})
	if err != nil {
		return fmt.Errorf("failed to send message: %v", err)
	}

	return nil
}

// Receive fetches messages from the SQS queue
func (q *SQSQueue) Receive(maxMessages int) (map[MessageHandle]Message, error) {
	// Set the maximum number of messages to retrieve
	maxMsg := int64(maxMessages)
	if maxMsg > 10 {
		maxMsg = 10 // SQS allows max 10 messages per request
	}

	// Receive messages from SQS with all attributes
	result, err := q.client.ReceiveMessage(&sqs.ReceiveMessageInput{
		QueueUrl:                    aws.String(q.queueURL),
		MaxNumberOfMessages:         aws.Int64(maxMsg),
		WaitTimeSeconds:             aws.Int64(1),  // Short polling to avoid blocking too long when queue is empty
		VisibilityTimeout:           aws.Int64(30), // 30 seconds visibility timeout
		MessageSystemAttributeNames: []*string{aws.String("All")},
		MessageAttributeNames:       []*string{aws.String("All")},
	})
	if err != nil {
		return nil, fmt.Errorf("failed to receive messages: %v", err)
	}

	// If no messages, return empty map
	if len(result.Messages) == 0 {
		return map[MessageHandle]Message{}, nil
	}

	// Parse messages and create the message-to-handle map
	messages := make(map[MessageHandle]Message)

	for _, sqsMsg := range result.Messages {
		if sqsMsg.Body == nil || sqsMsg.ReceiptHandle == nil {
			log.Println("Received message with nil body or receipt handle, skipping")
			continue
		}

		// Parse the message body which contains our application message
		var msg Message
		err := json.Unmarshal([]byte(*sqsMsg.Body), &msg)
		if err != nil {
			log.Printf("Error unmarshaling message: %v", err)
			continue
		}

		// Create a handle from the SQS receipt handle
		handle := MessageHandle(*sqsMsg.ReceiptHandle)

		// Add message to map with its handle as key
		messages[handle] = msg

		if sqsMsg.MessageId != nil {
			log.Printf("Received message with ID: %s", *sqsMsg.MessageId)
		}
	}

	return messages, nil
}

// Delete removes a message from the SQS queue using its handle
func (q *SQSQueue) Delete(handle MessageHandle) error {
	// Convert the MessageHandle back to string for SQS
	receiptHandle := string(handle)

	// Delete the message
	_, err := q.client.DeleteMessage(&sqs.DeleteMessageInput{
		QueueUrl:      aws.String(q.queueURL),
		ReceiptHandle: aws.String(receiptHandle),
	})
	if err != nil {
		return fmt.Errorf("failed to delete message: %v", err)
	}

	log.Printf("Successfully deleted message with receipt handle: %s...", truncateString(receiptHandle, 20))
	return nil
}

// Disconnect closes the connection with AWS SQS
func (q *SQSQueue) Disconnect() {
	// Nothing specific to clean up for AWS SQS client
	q.client = nil
}

// truncateString truncates a string to the specified length and adds an ellipsis
func truncateString(s string, length int) string {
	if len(s) <= length {
		return s
	}
	return s[:length] + "..."
}
