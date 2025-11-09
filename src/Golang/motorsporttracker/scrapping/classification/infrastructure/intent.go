package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeClassificationIntentName = "classification"

// ScrapeClassificationIntent is an Intent to scrape Classification.
type ScrapeClassificationIntent struct {
	application.BaseIntent
}

// NewScrapClassificationIntent creates a new ScrapeClassificationIntent.
func NewScrapClassificationIntent() *ScrapeClassificationIntent {
	return &ScrapeClassificationIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeClassificationIntentName,
				Description: "Scrape a Classification",
				Arguments: []application.Argument{
					{
						Name:        "series",
						Description: "The series to scrape the Classification for (e.g., F1, WEC, etc.)",
					},
					{
						Name:        "year",
						Description: "The year to scrape the Classification for (e.g., 2025, etc.)",
					},
					{
						Name:        "event",
						Description: "The event to scrape the Classification for (e.g., British GP, etc.)",
					},
					{
						Name:        "session",
						Description: "The session to scrape the Classification for (e.g. Race, etc.)",
					},
				},
				Options: []application.Option{},
			},
		},
	}
}
