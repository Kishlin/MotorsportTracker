package events

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

const ScrapeEventsMessageType = "scrap:events"

// ScrapEventsIntent is an Intent to scrape events.
type ScrapEventsIntent struct {
	scrapping.BaseIntent
}

// NewScrapEventsIntent creates a new ScrapEventsIntent.
func NewScrapEventsIntent() *ScrapEventsIntent {
	return &ScrapEventsIntent{
		BaseIntent: scrapping.BaseIntent{
			Config: scrapping.IntentConfig{
				Name:        "scrap:events",
				Description: "Scrap all available events",
				Arguments: []scrapping.Argument{
					{
						Name:        "series",
						Description: "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						Required:    true,
					},
					{
						Name:        "season",
						Description: "Season identifier (e.g., 2025)",
						Required:    true,
					},
				},
				Options: []scrapping.Option{},
			},
		},
	}
}

// ToMessage converts the ScrapEventsIntent to a queue.Message.
func (c *ScrapEventsIntent) ToMessage() queue.Message {
	return queue.Message{
		Type:     ScrapeEventsMessageType,
		Metadata: nil,
	}
}
