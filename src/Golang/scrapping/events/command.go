package events

import (
	"fmt"
	"log"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

const ScrapEventsMessageType = "scrap_events"

// ScrapEventsCommand is a command that publishes a scrapping intent for all events.
type ScrapEventsCommand struct {
	cli.BaseCommand
}

// NewScrapEventsCommand creates a new ScrapEventsCommand.
func NewScrapEventsCommand() *ScrapEventsCommand {
	cmd := &ScrapEventsCommand{}

	cmd.SetConfig(cli.CommandConfig{
		Name:        "scrap:events",
		Description: "Scrap all available events",
		Parameters: []cli.Parameter{
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
	})

	return cmd
}

// Execute runs the scrapping intent for events.
func (c *ScrapEventsCommand) Execute(args map[string]string) error {
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
		Type: ScrapEventsMessageType,
		Metadata: map[string]string{
			"series": args["series"],
			"season": args["season"],
		},
	}

	err = q.Send(message)
	if err != nil {
		return fmt.Errorf("error publishing to queue: %v", err)
	}

	log.Println("Successfully published events scrapping intent")
	return nil
}
