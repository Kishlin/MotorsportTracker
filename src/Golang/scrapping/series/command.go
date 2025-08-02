package series

import (
	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping"
)

// ScrapSeriesCommand is a command that publishes a scrapping intent for all series.
type ScrapSeriesCommand struct {
	cli.BaseCommand
}

// NewScrapSeriesCommand creates a new ScrapSeriesCommand.
func NewScrapSeriesCommand() *ScrapSeriesCommand {
	cmd := &ScrapSeriesCommand{
		BaseCommand: cli.BaseCommand{
			Config: cli.CommandConfig{
				Name:        "scrap:series",
				Description: "Scrap all available series",
				Arguments:   []cli.Argument{},
				Options:     []cli.Option{},
			},
		},
	}

	return cmd
}

// Execute runs the scrapping intent for series.
func (c *ScrapSeriesCommand) Execute(_ []string, _ map[string]string) error {
	publisher, err := scrapping.NewIntentPublisher()
	if err != nil {
		return err
	}
	defer publisher.Close()

	return publisher.PublishIntent(scrapping.ScrapeSeriesMessageType, nil)
}
