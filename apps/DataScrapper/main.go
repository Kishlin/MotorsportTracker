package main

import (
	"flag"
	"fmt"
	"log"
	"os"
	"strings"
	"time"

	"github.com/kishlin/MotorsportTracker/apps/DataScrapper/intent"
	"github.com/kishlin/MotorsportTracker/apps/DataScrapper/queue"
	"github.com/kishlin/MotorsportTracker/apps/DataScrapper/worker"
)

func main() {
	// Define command-line flags for the two different usage modes
	scrapMode := flag.Bool("scrap", false, "Scrap new motorsport data from sources")
	processMode := flag.Bool("process", false, "Process previously scraped motorsport data")

	// Define additional parameters for each mode
	source := flag.String("source", "", "Data source (e.g., f1, f2, wec)")
	season := flag.String("season", "", "Season identifier (e.g., 2025)")
	event := flag.String("event", "", "Event identifier")
	session := flag.String("session", "", "Session identifier (e.g., qualifying, race)")
	all := flag.Bool("all", false, "Scrap all available data for the selected scope")

	// Process mode specific flags
	workerCount := flag.Int("workers", 3, "Number of concurrent workers for processing (process mode only)")
	pollInterval := flag.Duration("interval", 5*time.Second, "Queue polling interval (process mode only)")
	queueType := flag.String("queue", "memory", "Queue type: memory or sqs (default: memory)")

	// Parse the command-line flags
	flag.Parse()

	// If no mode is specified, show help information
	if !*scrapMode && !*processMode {
		printHelp()
		os.Exit(0)
	}

	// Create the queue based on configuration
	q, err := queue.QueueFactory(queue.QueueType(*queueType))
	if err != nil {
		log.Fatalf("Error creating queue: %v", err)
	}

	// Connect to the queue
	if err := q.Connect(); err != nil {
		log.Fatalf("Error connecting to queue: %v", err)
	}
	defer q.Disconnect()

	// Execute the appropriate mode
	if *scrapMode {
		executeScrapMode(q, *source, *season, *event, *session, *all)
	}

	if *processMode {
		executeProcessMode(q, *workerCount, *pollInterval)
	}
}

// printHelp shows the usage information for the application
func printHelp() {
	fmt.Println("DataScrapper: Motorsport data collection and processing tool")
	fmt.Println("\nUsage:")
	fmt.Println("  1. Scrap mode: Adds scraping requests to the queue")
	fmt.Println("     ./DataScrapper -scrap [options]")
	fmt.Println("\n  2. Process mode: Processes queue items by scraping data from sources")
	fmt.Println("     ./DataScrapper -process [options]")
	fmt.Println("\nScrap mode options:")
	fmt.Println("  -source=<source>  Data source (e.g., f1, f2, wec)")
	fmt.Println("  -season=<season>  Season identifier (e.g., 2025)")
	fmt.Println("  -event=<event>    Event identifier")
	fmt.Println("  -session=<session> Session identifier (e.g., qualifying, race)")
	fmt.Println("  -all              Scrap all available data for the selected scope")
	fmt.Println("\nProcess mode options:")
	fmt.Println("  -workers=<n>      Number of concurrent workers (default: 3)")
	fmt.Println("  -interval=<dur>   Queue polling interval (default: 5s)")
	fmt.Println("  -queue=<type>     Queue type: memory or sqs (default: memory)")
	fmt.Println("\nExamples:")
	fmt.Println("  ./DataScrapper -scrap -source=f1 -all")
	fmt.Println("  ./DataScrapper -scrap -source=f1 -season=2025")
	fmt.Println("  ./DataScrapper -scrap -source=f1 -season=2025 -event=monaco -session=race")
	fmt.Println("  ./DataScrapper -process -workers=5 -queue=sqs")
}

// executeScrapMode handles adding scraping requests to the queue
func executeScrapMode(q queue.Queue, source, season, event, session string, all bool) {
	if source == "" {
		if !all {
			fmt.Println("Error: source parameter is required for scrap mode unless -all is specified")
			os.Exit(1)
		}

		// User wants to scrap everything
		queueScrapeAllSeriesIntent(q)
		return
	}

	// Determine the intent type based on the parameters
	if season == "" {
		// No season specified, so we'll queue an intent to scrap all seasons for the series
		queueScrapeAllSeasonsIntent(q, source)
		return
	}

	if event == "" {
		// No event specified, so we'll queue an intent to scrap all events for the season
		queueScrapeAllEventsIntent(q, source, season)
		return
	}

	if session == "" {
		fmt.Println("Error: session parameter is required when event is specified")
		os.Exit(1)
	}

	// Queue an intent to scrap a specific session
	queueScrapeEventSessionIntent(q, source, season, event, session)
}

// executeProcessMode runs workers to process items from the queue
func executeProcessMode(q queue.Queue, workerCount int, pollInterval time.Duration) {
	fmt.Printf("Starting processor with %d workers (poll interval: %s)\n", workerCount, pollInterval)

	// Create and start the worker
	w := worker.NewWorker(q, workerCount, pollInterval)
	w.Start()

	// Wait for interrupt signal
	fmt.Println("Press Ctrl+C to stop...")
	waitForInterrupt()

	// Stop the worker gracefully
	w.Stop()
	fmt.Println("Processing stopped")
}

// Queue intent helper functions
func queueScrapeAllSeriesIntent(q queue.Queue) {
	i := intent.CreateAllSeriesIntent()
	message := queue.Message{
		Type:     string(i.Type),
		Target:   "",
		Metadata: i.Metadata,
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Println("Added intent to scrap all series to queue")
}

func queueScrapeAllSeasonsIntent(q queue.Queue, series string) {
	i := intent.CreateAllSeasonsIntent(series)
	message := queue.Message{
		Type:     string(i.Type),
		Target:   series,
		Metadata: i.Metadata,
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Printf("Added intent to scrap all seasons for series '%s' to queue\n", series)
}

func queueScrapeAllEventsIntent(q queue.Queue, series, season string) {
	i := intent.CreateAllEventsIntent(series, season)
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

func queueScrapeEventSessionIntent(q queue.Queue, series, season, event, session string) {
	i := intent.CreateEventSessionIntent(series, season, event, session)
	message := queue.Message{
		Type:   string(i.Type),
		Target: series,
		Metadata: map[string]string{
			"season":  season,
			"event":   event,
			"session": session,
		},
	}

	if err := q.Send(message); err != nil {
		log.Fatalf("Error sending message to queue: %v", err)
	}

	fmt.Printf("Added intent to scrap %s session for event '%s' in series '%s', season '%s' to queue\n",
		session, event, series, season)
}

// waitForInterrupt blocks until a SIGINT or SIGTERM is received
func waitForInterrupt() {
	// This is a simplified version - in a real application, you'd handle signals properly
	fmt.Scanln()
}
