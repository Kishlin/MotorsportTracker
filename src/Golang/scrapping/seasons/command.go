package seasons

import (
	"fmt"
	"log"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

const ScrapSeasonsMessageType = "scrap_seasons"

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
				Parameters: []cli.Parameter{
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
func (c *ScrapSeasonsCommand) Execute(args map[string]string) error {
	q, err := queue.Factory(queue.ScrappingIntentsQueue)
	if err != nil {
		return fmt.Errorf("error creating queue: %v", err)
	}

	// Connect to the queue
	if err := q.Connect(); err != nil {
		return fmt.Errorf("error connecting to queue: %v", err)
	}
	defer q.Disconnect()

	// Create and publish the message
	message := queue.Message{
		Type: ScrapSeasonsMessageType,
		Metadata: map[string]string{
			"series": args["series"],
		},
	}

	err = q.Send(message)
	if err != nil {
		return fmt.Errorf("error publishing to queue: %v", err)
	}

	log.Println("Successfully published seasons scrapping intent")
	return nil
}
