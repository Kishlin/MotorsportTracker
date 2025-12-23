package infrastructure

import (
	"context"
	"fmt"

	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

// SeasonsScrapper orchestrates bulk scraping operations by publishing asynchronous intents.
type SeasonsScrapper struct {
	queue *messaging.SQSQueue
}

func NewSeasonsScrapper(queue *messaging.SQSQueue) *SeasonsScrapper {
	return &SeasonsScrapper{
		queue: queue,
	}
}

func (p *SeasonsScrapper) ScrapeSeasonsForSeries(_ context.Context, seriesID string) error {
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
