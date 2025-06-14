package series

import (
	"fmt"
	"log"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

const ScrapSeriesMessageType = "scrap_series"

// ScrapSeriesCommand is a command that publishes a scrapping intent for all series.
type ScrapSeriesCommand struct {
	cli.BaseCommand
}

// NewScrapSeriesCommand creates a new ScrapSeriesCommand.
func NewScrapSeriesCommand() *ScrapSeriesCommand {
	cmd := &ScrapSeriesCommand{}

	cmd.SetConfig(cli.CommandConfig{
		Name:        "scrap:series",
		Description: "Scrap all available series",
		Parameters:  []cli.Parameter{},
		Options:     []cli.Option{},
	})

	return cmd
}

// Execute runs the scrapping intent for series.
func (c *ScrapSeriesCommand) Execute(args map[string]string) error {
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
		Type: ScrapSeriesMessageType,
	}

	err = q.Send(message)
	if err != nil {
		return fmt.Errorf("error publishing to queue: %v", err)
	}

	log.Println("Successfully published series scrapping intent")
	return nil
}
