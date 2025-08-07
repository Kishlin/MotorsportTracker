package seasons

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
)

const ScrapeSeasonsIntentName = "seasons"

// ScrapSeasonsIntent is an Intent to scrape seasons.
type ScrapSeasonsIntent struct {
	scrapping.BaseIntent
}

// NewScrapSeasonsIntent creates a new ScrapSeasonsIntent.
func NewScrapSeasonsIntent() *ScrapSeasonsIntent {
	return &ScrapSeasonsIntent{
		BaseIntent: scrapping.BaseIntent{
			Config: scrapping.IntentConfig{
				Name:        ScrapeSeasonsIntentName,
				Description: "Scrape all available seasons",
				Arguments:   []scrapping.Argument{},
				Options: []scrapping.Option{
					{
						Name:          "series",
						Description:   "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						RequiresValue: true,
					},
				},
			},
		},
	}
}
