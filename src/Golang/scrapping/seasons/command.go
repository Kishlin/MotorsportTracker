package seasons

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping"
)

// ScrapSeasonsCommand is a command that publishes a scrapping intent for all seasons.
type ScrapSeasonsCommand struct {
	cli.BaseCommand
}

// NewScrapSeasonsCommand creates a new ScrapSeasonsCommand.
func NewScrapSeasonsCommand() *ScrapSeasonsCommand {
	cmd := &ScrapSeasonsCommand{
		BaseCommand: cli.BaseCommand{
			Config: cli.CommandConfig{
				Name:        "scrap:seasons",
				Description: "Scrap all available seasons",
				Arguments: []cli.Argument{
					{
						Name:        "series",
						Description: "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)",
						Required:    true,
					},
				},
				Options: []cli.Option{},
			},
		},
	}

	return cmd
}

// Execute runs the scrapping intent for seasons.
func (c *ScrapSeasonsCommand) Execute(arguments []string, _ map[string]string) error {
	publisher, err := scrapping.NewIntentPublisher()
	if err != nil {
		return err
	}
	defer publisher.Close()

	metadata := map[string]string{
		"series": arguments[0],
	}

	return publisher.PublishIntent(scrapping.ScrapeSeasonsMessageType, metadata)
}
