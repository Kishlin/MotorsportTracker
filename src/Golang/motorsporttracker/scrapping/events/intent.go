package events

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/domain"
)

const ScrapeEventsIntentName = "events"

// ScrapEventsIntent is an Intent to scrape events.
type ScrapEventsIntent struct {
	application.BaseIntent
}

// NewScrapEventsIntent creates a new ScrapEventsIntent.
func NewScrapEventsIntent() *ScrapEventsIntent {
	return &ScrapEventsIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeEventsIntentName,
				Description: "Scrape all available events",
				Arguments:   []application.Argument{},
				Options: []application.Option{
					{
						Name:          "series",
						Description:   "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						RequiresValue: true,
					},
					{
						Name:          "season",
						Description:   "Season identifier (e.g., 2025)",
						RequiresValue: true,
					},
				},
			},
		},
	}
}
