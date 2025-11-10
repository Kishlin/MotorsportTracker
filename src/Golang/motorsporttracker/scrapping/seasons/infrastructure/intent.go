package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeSeasonsIntentName = "scrape:seasons"

// ScrapSeasonsIntent is an Intent to scrape seasons.
type ScrapSeasonsIntent struct {
	application.BaseIntent
}

// NewScrapSeasonsIntent creates a new ScrapSeasonsIntent.
func NewScrapSeasonsIntent() *ScrapSeasonsIntent {
	return &ScrapSeasonsIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeSeasonsIntentName,
				Description: "Scrape all available seasons",
				Arguments: []application.Argument{
					{
						Name: "series",
					},
				},
				Options: []application.Option{},
			},
		},
	}
}
