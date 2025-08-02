package events

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping"
)

// ScrapEventsCommand is a command that publishes a scrapping intent for all events.
type ScrapEventsCommand struct {
	cli.BaseCommand
}

// NewScrapEventsCommand creates a new ScrapEventsCommand.
func NewScrapEventsCommand() *ScrapEventsCommand {
	cmd := &ScrapEventsCommand{
		BaseCommand: cli.BaseCommand{
			Config: cli.CommandConfig{
				Name:        "scrap:events",
				Description: "Scrap all available events",
				Arguments: []cli.Argument{
					{
						Name:        "series",
						Description: "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						Required:    true,
					},
					{
						Name:        "season",
						Description: "Season identifier (e.g., 2025)",
						Required:    true,
					},
				},
				Options: []cli.Option{},
			},
		},
	}

	return cmd
}

// Execute runs the scrapping intent for events.
func (c *ScrapEventsCommand) Execute(arguments []string, _ map[string]string) error {
	publisher, err := scrapping.NewIntentPublisher()
	if err != nil {
		return err
	}
	defer publisher.Close()

	metadata := map[string]string{
		"series": arguments[0],
		"season": arguments[1],
	}

	return publisher.PublishIntent(scrapping.ScrapeEventsMessageType, metadata)
}
