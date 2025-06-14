package main

import (
	"flag"
	"fmt"
	"log"
	"os"

	"github.com/kishlin/MotorsportTracker/src/Golang/intent"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

func main() {
	// Set up the command structure
	if len(os.Args) < 2 {
		// Default to "series" command if no command is provided
		os.Args = append(os.Args, "series")
	}

	// Get the command (first argument)
	cmd := os.Args[1]

	// Strip the command from args so flag.Parse works on the rest of the arguments
	os.Args = append(os.Args[:1], os.Args[2:]...)

	// Define command-line flags (options)
	series := flag.String("series", "", "Motorsport series (e.g., Formula One, Formula 2, World Endurance Championship, ...)")
	season := flag.String("season", "", "Season identifier (e.g., 2025)")
	help := flag.Bool("help", false, "Show help information")

	// Parse the command-line flags (everything after the command)
	flag.Parse()

	// Show help if requested or command is "help"
	if *help || cmd == "help" {
		printHelp()
		os.Exit(0)
	}

	// Convert string command to intent.Command
	var command intent.Command
	switch cmd {
	case "series":
		command = intent.ScrapSeries
	case "seasons":
		if *series == "" {
			fmt.Println("Error: -series flag is required for the 'seasons' command")
			os.Exit(1)
		}
		command = intent.ScrapSeasons
	case "events":
		if *series == "" || *season == "" {
			fmt.Println("Error: both -series and -season flags are required for the 'events' command")
			os.Exit(1)
		}
		command = intent.ScrapEvents
	default:
		fmt.Printf("Unknown command: %s\n", cmd)
		printHelp()
		os.Exit(1)
	}

	q, err := queue.Factory(queue.ScrappingIntentsQueue)
	if err != nil {
		log.Fatalf("Error creating queue: %v", err)
	}

	// Connect to the queue
	if err := q.Connect(); err != nil {
		log.Fatalf("Error connecting to queue: %v", err)
	}
	defer q.Disconnect()

	// Handle queue publishing based on parameters
	executePublish(q, command, *series, *season)
}

// printHelp shows the usage information for the application
func printHelp() {
	fmt.Println("ScrappingIntentPublisher: Adds scrapping tasks to a queue")
	fmt.Println("\nUsage:")
	fmt.Println("  ./ScrappingIntentPublisher [command] [options]")
	fmt.Println("\nCommands:")
	fmt.Println("  series              Scrap all available series (default)")
	fmt.Println("  seasons             Scrap all seasons for a specific series")
	fmt.Println("  events              Scrap all events for a specific season")
	fmt.Println("  help                Show this help information")
	fmt.Println("\nOptions:")
	fmt.Println("  -series=<series>    Motorsport series (required for 'seasons' and 'events' commands)")
	fmt.Println("  -season=<season>    Season identifier (required for 'events' command)")
	fmt.Println("  -help               Show this help information")
	fmt.Println("\nEnvironment Variables:")
	fmt.Println("  QUEUE_TYPE          Type of queue to use: 'memory' or 'sqs' (default: memory)")
	fmt.Println("  SQS_ENDPOINT        SQS endpoint URL (default: http://localhost:9324)")
	fmt.Println("  SQS_REGION          AWS region (default: elasticmq)")
	fmt.Println("  SQS_QUEUE_NAME      SQS queue name (default: ScrappingIntents)")
	fmt.Println("  SQS_ACCESS_KEY      AWS access key for SQS")
	fmt.Println("  SQS_SECRET_KEY      AWS secret key for SQS")
	fmt.Println("\nExamples:")
	fmt.Println("  ./ScrappingIntentPublisher series")
	fmt.Println("  ./ScrappingIntentPublisher seasons -series=\"Formula One\"")
	fmt.Println("  ./ScrappingIntentPublisher events -series=\"Formula One\" -season=2025")
	fmt.Println("  QUEUE_TYPE=sqs ./ScrappingIntentPublisher series")
}

// executePublish handles adding scraping requests to the queue
func executePublish(q queue.Queue, command intent.Command, series, season string) {
	switch command {
	case intent.ScrapSeries:
		queueScrapeAllSeriesIntent(q)
	case intent.ScrapSeasons:
		queueScrapeAllSeasonsIntent(q, series)
	case intent.ScrapEvents:
		queueScrapeAllEventsIntent(q, series, season)
	}
}

// Queue intent helper functions
func queueScrapeAllSeriesIntent(q queue.Queue) {
	i := intent.CreateSeriesIntent()
	message := queue.Message{
		Type:   string(i.Type),
		Target: "",
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Println("Added intent to scrap all series to queue")
}

func queueScrapeAllSeasonsIntent(q queue.Queue, series string) {
	i := intent.CreateSeasonsIntent(series)
	message := queue.Message{
		Type:   string(i.Type),
		Target: series,
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Printf("Added intent to scrap all seasons for series '%s' to queue\n", series)
}

func queueScrapeAllEventsIntent(q queue.Queue, series, season string) {
	i := intent.CreateEventsIntent(series, season)
	message := queue.Message{
		Type:   string(i.Type),
		Target: series,
		Metadata: map[string]string{
			"season": season,
		},
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Printf("Added intent to scrap all events for series '%s', season '%s' to queue\n",
		series, season)
}
