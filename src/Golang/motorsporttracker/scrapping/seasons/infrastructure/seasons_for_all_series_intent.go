package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeSeasonsForAllSeriesIntentName = "scrape:seasons-all"

// ScrapeSeasonsForAllSeriesIntent is an Intent to scrape seasons for all available series.
type ScrapeSeasonsForAllSeriesIntent struct {
	application.BaseIntent
}

// NewScrapeSeasonsForAllSeriesIntent creates a new ScrapeSeasonsForAllSeriesIntent.
func NewScrapeSeasonsForAllSeriesIntent() *ScrapeSeasonsForAllSeriesIntent {
	return &ScrapeSeasonsForAllSeriesIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeSeasonsForAllSeriesIntentName,
				Description: "Scrape seasons for all available series",
				Arguments:   []application.Argument{},
				Options:     []application.Option{},
			},
		},
	}
}
