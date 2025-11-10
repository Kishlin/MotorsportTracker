package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeCalendarIntentName = "scrape:calendar"

// ScrapCalendarIntent is an Intent to scrape Calendar.
type ScrapCalendarIntent struct {
	application.BaseIntent
}

// NewScrapCalendarIntent creates a new ScrapCalendarIntent.
func NewScrapCalendarIntent() *ScrapCalendarIntent {
	return &ScrapCalendarIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeCalendarIntentName,
				Description: "Scrape all available Calendar",
				Arguments: []application.Argument{
					{
						Name:        "series",
						Description: "The series to scrape the calendar for (e.g., F1, WEC, etc.)",
					},
					{
						Name:        "year",
						Description: "The year to scrape the calendar for (e.g., 2023)",
					},
				},
				Options: []application.Option{},
			},
		},
	}
}
