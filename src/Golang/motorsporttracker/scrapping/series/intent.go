package series

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
)

const ScrapeSeriesIntentName = "series"

// ScrapSeriesIntent is an Intent to scrape series.
type ScrapSeriesIntent struct {
	scrapping.BaseIntent
}

// NewScrapSeriesIntent creates a new ScrapSeriesIntent.
func NewScrapSeriesIntent() *ScrapSeriesIntent {
	return &ScrapSeriesIntent{
		BaseIntent: scrapping.BaseIntent{
			Config: scrapping.IntentConfig{
				Name:        ScrapeSeriesIntentName,
				Description: "Scrape all available series",
				Arguments:   []scrapping.Argument{},
				Options:     []scrapping.Option{},
			},
		},
	}
}

// ToMessage converts the ScrapSeriesIntent to a messaging.Message.
func (c *ScrapSeriesIntent) ToMessage() messaging.Message {
	return messaging.Message{
		Type:     ScrapeSeriesIntentName,
		Metadata: nil,
	}
}
