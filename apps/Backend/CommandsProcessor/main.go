package main

import (
	"context"
	"flag"
	"fmt"
	"os"
	"os/signal"
	"syscall"
	"time"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/events"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series"
)

func main() {
	// Create a context for the application
	ctx := context.WithoutCancel(context.Background())

	// Define command-line flags specific to processing
	workerCount := flag.Int("workers", 3, "Number of concurrent workers for processing")
	pollInterval := flag.Duration("interval", 5*time.Second, "Queue polling interval")

	// Parse the command-line flags
	flag.Parse()

	// Show help information if explicitly requested
	if len(os.Args) > 1 && (os.Args[1] == "-h" || os.Args[1] == "--help") {
		printHelp()
		os.Exit(0)
	}

	registry := dependencyinjection.NewServicesRegistry(
		connector.NewDefaultConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewSQSQueueFactory(),
	)
	defer registry.Close()

	// Start the processor with the specified parameters
	fmt.Printf("Starting ScrappingProcessor with %d workers (poll interval: %s)\n", *workerCount, *pollInterval)

	// Register handlers for scrapping intents
	handlersList := messaging.NewHandlersList()

	handlersList.RegisterHandler(
		series.ScrapeSeriesIntentName,
		series.NewScrapSeriesHandler(registry.GetCoreDatabase(ctx), registry.GetConnector()),
	)
	handlersList.RegisterHandler(seasons.ScrapeSeasonsIntentName, seasons.NewScrapSeasonsHandler())
	handlersList.RegisterHandler(events.ScrapeEventsIntentName, events.NewScrapEventsHandler())

	// Create and start the worker
	w := messaging.NewWorker(registry.GetIntentsQueue(), handlersList, *workerCount, *pollInterval)
	w.Start(ctx)

	// Set up graceful shutdown on interrupt/termination signals
	sigChan := make(chan os.Signal, 1)
	signal.Notify(sigChan, syscall.SIGINT, syscall.SIGTERM)

	fmt.Println("ScrappingProcessor is running. Press Ctrl+C to stop.")

	// Wait for termination signal
	<-sigChan

	// Stop the worker gracefully
	fmt.Println("\nShutting down gracefully...")
	w.Stop()
	fmt.Println("ScrappingProcessor stopped")
}

// printHelp shows the usage information for the application
func printHelp() {
	fmt.Println("ScrappingProcessor: Processes scrapping intents from queue")
	fmt.Println("\nUsage:")
	fmt.Println("  ./ScrappingProcessor [options]")
	fmt.Println("\nOptions:")
	fmt.Println("  -workers=<n>      Number of concurrent workers (default: 3)")
	fmt.Println("  -interval=<dur>   Queue polling interval (default: 5s)")
	fmt.Println("  -skip-migrations  Skip running database migrations")
	fmt.Println("\nEnvironment Variables:")
	fmt.Println("  QUEUE_TYPE          Type of queue to use: 'memory' or 'sqs' (default: memory)")
	fmt.Println("  SQS_ENDPOINT        SQS endpoint URL (default: http://localhost:9324)")
	fmt.Println("  SQS_REGION          AWS region (default: elasticmq)")
	fmt.Println("  SQS_QUEUE_NAME      SQS queue name (default: ScrappingIntents)")
	fmt.Println("  SQS_ACCESS_KEY      AWS access key for SQS")
	fmt.Println("  SQS_SECRET_KEY      AWS secret key for SQS")
	fmt.Println("  POSTGRES_CORE_URL   PostgreSQL connection string for the core database")
	fmt.Println("\nExamples:")
	fmt.Println("  ./ScrappingProcessor")
	fmt.Println("  ./ScrappingProcessor -workers=5")
	fmt.Println("  ./ScrappingProcessor -interval=10s")
	fmt.Println("  QUEUE_TYPE=sqs ./ScrappingProcessor -workers=5")
}
