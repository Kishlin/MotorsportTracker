package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeSeasonsForSeriesIDIntentName = "scrape:seasons-one"

// ScrapeSeasonsForSeriesIDIntent is an Intent to scrape a single season by series identifier.
type ScrapeSeasonsForSeriesIDIntent struct {
	application.BaseIntent
}

// NewScrapeSeasonsForSeriesIDIntent creates a new ScrapeSeasonsForSeriesIDIntent.
func NewScrapeSeasonsForSeriesIDIntent() *ScrapeSeasonsForSeriesIDIntent {
	return &ScrapeSeasonsForSeriesIDIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeSeasonsForSeriesIDIntentName,
				Description: "Scrape seasons for a series by identifier",
				Arguments: []application.Argument{
					{
						Name: "identifier",
					},
				},
				Options: []application.Option{},
			},
		},
	}
}
