package infrastructure

import (
	"context"
	"fmt"

	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

// SeasonsScrapperUsingIntents orchestrates bulk scraping operations by publishing asynchronous intents.
type SeasonsScrapperUsingIntents struct {
	queue *messaging.SQSQueue
}

func NewSeasonsScrapperUsingIntents(queue *messaging.SQSQueue) *SeasonsScrapperUsingIntents {
	return &SeasonsScrapperUsingIntents{
		queue: queue,
	}
}

func (p *SeasonsScrapperUsingIntents) ScrapeSeasonsForSeries(_ context.Context, seriesID string) error {
	intent := NewScrapeSeasonsForSeriesIDIntent()
	message, err := intent.ToMessage([]string{seriesID}, map[string]string{})
	if err != nil {
		return fmt.Errorf("creating message for series %s: %w", seriesID, err)
	}

	if err := p.queue.Send(message); err != nil {
		return fmt.Errorf("sending message for series %s: %w", seriesID, err)
	}

	return nil
}
