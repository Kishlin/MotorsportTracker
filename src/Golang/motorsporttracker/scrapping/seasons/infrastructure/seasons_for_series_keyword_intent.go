package infrastructure

import (
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
)

const ScrapeSeasonsForSeriesKeywordIntentName = "scrape:seasons"

// ScrapeSeasonsForSeriesKeywordIntent is an Intent to scrape seasons by series keyword.
type ScrapeSeasonsForSeriesKeywordIntent struct {
	application.BaseIntent
}

// NewScrapeSeasonsForSeriesKeywordIntent creates a new ScrapeSeasonsForSeriesKeywordIntent.
func NewScrapeSeasonsForSeriesKeywordIntent() *ScrapeSeasonsForSeriesKeywordIntent {
	return &ScrapeSeasonsForSeriesKeywordIntent{
		BaseIntent: application.BaseIntent{
			Config: application.IntentConfig{
				Name:        ScrapeSeasonsForSeriesKeywordIntentName,
				Description: "Scrape seasons for a series by keyword",
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
