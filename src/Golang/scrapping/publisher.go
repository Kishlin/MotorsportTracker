package scrapping

import (
	"fmt"
	"log"

	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

// IntentPublisher handles the core logic of publishing scrapping intents to the queue
type IntentPublisher struct {
	queue queue.Queue
}

// NewIntentPublisher creates a new IntentPublisher with a connected queue
func NewIntentPublisher() (*IntentPublisher, error) {
	q, err := queue.Factory(queue.ScrappingIntentsQueue)
	if err != nil {
		return nil, fmt.Errorf("error creating queue: %v", err)
	}

	if err := q.Connect(); err != nil {
		return nil, fmt.Errorf("error connecting to queue: %v", err)
	}

	return &IntentPublisher{queue: q}, nil
}

// Close disconnects from the queue
func (p *IntentPublisher) Close() {
	err := p.queue.Disconnect()
	if err != nil {
		log.Printf("Error disconnecting from queue: %v", err)
	}
}

// PublishIntent sends a scrapping intent message to the queue
func (p *IntentPublisher) PublishIntent(messageType string, metadata map[string]string) error {
	message := queue.Message{
		Type:     messageType,
		Metadata: metadata,
	}

	if err := p.queue.Send(message); err != nil {
		return fmt.Errorf("error publishing to queue: %v", err)
	}

	log.Printf("Successfully published %s scrapping intent", messageType)
	return nil
}

// IntentConfig represents a single scrapping intent configuration
type IntentConfig struct {
	MessageType string
	Metadata    map[string]string
}

const (
	ScrapeSeriesMessageType  = "scrape_series"
	ScrapeSeasonsMessageType = "scrape_seasons"
	ScrapeEventsMessageType  = "scrape_events"
)
