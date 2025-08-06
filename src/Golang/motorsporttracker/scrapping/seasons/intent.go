package seasons

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
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
				Arguments: []scrapping.Argument{
					{
						Name:        "series",
						Description: "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						Required:    true,
					},
				},
				Options: []scrapping.Option{},
			},
		},
	}
}

// ToMessage converts the ScrapSeasonsIntent to a messaging.Message.
func (c *ScrapSeasonsIntent) ToMessage() messaging.Message {
	return messaging.Message{
		Type:     ScrapeSeasonsIntentName,
		Metadata: nil,
	}
}
