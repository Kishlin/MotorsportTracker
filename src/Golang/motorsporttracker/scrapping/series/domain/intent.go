package domain

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/domain"
)

const ScrapeSeriesIntentName = "series"

// ScrapSeriesIntent is an Intent to scrape series.
type ScrapSeriesIntent struct {
	application.BaseIntent
}

// NewScrapSeriesIntent creates a new ScrapSeriesIntent.
func NewScrapSeriesIntent() *ScrapSeriesIntent {
	return &ScrapSeriesIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeSeriesIntentName,
				Description: "Scrape all available series",
				Arguments:   []application.Argument{},
				Options:     []application.Option{},
			},
		},
	}
}
