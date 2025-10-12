package infrastructure

import (
	"encoding/json"
	"fmt"
	"log/slog"

	"github.com/aws/aws-sdk-go/aws"
	"github.com/aws/aws-sdk-go/aws/credentials"
	"github.com/aws/aws-sdk-go/aws/session"
	"github.com/aws/aws-sdk-go/service/sqs"

	"github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

// SQSQueue implements Queue interface using AWS SQS
type SQSQueue struct {
	client    *sqs.SQS
	queueURL  string
	queueName string
	config    QueueConfig
}

// NewSQSQueueWithConfig creates a new SQS queue client with the provided configuration
func NewSQSQueueWithConfig(config QueueConfig) *SQSQueue {
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
		return fmt.Errorf("creating AWS session: %v", err)
	}

	// Create SQS client
	q.client = sqs.New(sess)

	// Search queue URL if not already set
	if q.queueURL == "" {
		result, err := q.client.GetQueueUrl(&sqs.GetQueueUrlInput{
			QueueName: aws.String(q.queueName),
		})
		if err != nil {
			return fmt.Errorf("getting queue URL: %v", err)
		}
		q.queueURL = *result.QueueUrl
	}

	slog.Info("Connected to SQS queue", "queueName", q.queueName)

	return nil
}

// Send adds a message to the SQS queue
func (q *SQSQueue) Send(message domain.Message) error {
	// Convert message to JSON
	messageBody, err := json.Marshal(message)
	if err != nil {
		return fmt.Errorf("marshalling message: %v", err)
	}

	// Send message to SQS
	_, err = q.client.SendMessage(&sqs.SendMessageInput{
		MessageBody: aws.String(string(messageBody)),
		QueueUrl:    aws.String(q.queueURL),
	})
	if err != nil {
		return fmt.Errorf("sending message: %v", err)
	}

	return nil
}

// Receive fetches messages from the SQS queue
func (q *SQSQueue) Receive(maxMessages int) (map[domain.MessageHandle]domain.Message, error) {
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
		return nil, fmt.Errorf("receiving messages: %v", err)
	}

	// If no messages, return empty map
	if len(result.Messages) == 0 {
		return map[domain.MessageHandle]domain.Message{}, nil
	}

	// Parse messages and create the message-to-handle map
	messages := make(map[domain.MessageHandle]domain.Message)

	for _, sqsMsg := range result.Messages {
		if sqsMsg.Body == nil || sqsMsg.ReceiptHandle == nil {
			slog.Debug("Received message with nil body or receipt handle, skipping")
			continue
		}

		// Parse the message body which contains our application message
		var msg domain.Message
		err := json.Unmarshal([]byte(*sqsMsg.Body), &msg)
		if err != nil {
			slog.Error("Failed to unmarshal message", "err", err)
			continue
		}

		// Create a handle from the SQS receipt handle
		handle := domain.MessageHandle(*sqsMsg.ReceiptHandle)

		// Add message to map with its handle as key
		messages[handle] = msg

		slog.Debug("Received message", "handle", truncateString(*sqsMsg.ReceiptHandle, 20), "type", msg.Type)
	}

	return messages, nil
}

// Delete removes a message from the SQS queue using its handle
func (q *SQSQueue) Delete(handle domain.MessageHandle) error {
	// Convert the MessageHandle back to string for SQS
	receiptHandle := string(handle)

	// Delete the message
	_, err := q.client.DeleteMessage(&sqs.DeleteMessageInput{
		QueueUrl:      aws.String(q.queueURL),
		ReceiptHandle: aws.String(receiptHandle),
	})
	if err != nil {
		return fmt.Errorf("deleting message: %v", err)
	}

	slog.Debug("Deleted message", "handle", truncateString(receiptHandle, 20))

	return nil
}

// Disconnect closes the connection with AWS SQS
func (q *SQSQueue) Disconnect() {
	// Nothing specific to clean up for AWS SQS client
	q.client = nil

	slog.Info("Disconnected from SQS queue", "queueName", q.queueName)
}

// truncateString truncates a string to the specified length and adds an ellipsis
func truncateString(s string, length int) string {
	if len(s) <= length {
		return s
	}
	return s[:length] + "..."
}
