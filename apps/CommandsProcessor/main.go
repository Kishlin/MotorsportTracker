package main

import (
	"context"
	"flag"
	"fmt"
	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/events"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/seasons"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/series"
	"log"
	"os"
	"os/signal"
	"syscall"
	"time"

	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
	"github.com/kishlin/MotorsportTracker/src/Golang/worker"
)

func main() {
	// Create a context for the application
	ctx := context.WithoutCancel(context.Background())

	// Define command-line flags specific to processing
	workerCount := flag.Int("workers", 3, "Number of concurrent workers for processing")
	pollInterval := flag.Duration("interval", 5*time.Second, "Queue polling interval")
	skipMigrations := flag.Bool("skip-migrations", false, "Skip running database migrations")

	// Parse the command-line flags
	flag.Parse()

	// Show help information if explicitly requested
	if len(os.Args) > 1 && (os.Args[1] == "-h" || os.Args[1] == "--help") {
		printHelp()
		os.Exit(0)
	}

	// Get the database connection string from environment variable
	connStr := os.Getenv("POSTGRES_CORE_URL")
	if connStr == "" {
		log.Fatalf("POSTGRES_CORE_URL environment variable not set")
		return
	}

	// Initialize database connection
	db := database.GetInstance()
	if err := db.ConnectCore(ctx, connStr); err != nil {
		log.Fatalf("Failed to connect to database: %v", err)
	}
	defer db.Close()

	// Run database migrations if not skipped
	if !*skipMigrations {
		fmt.Println("Running database migrations...")
		migrationRunner := database.NewMigrationRunner(db)
		if err := migrationRunner.RunMigrations(ctx, database.GetCoreMigrations()); err != nil {
			log.Fatalf("Failed to run migrations: %v", err)
		}
		fmt.Println("Migrations completed successfully")
	}

	// Create the queue using factory (with environment variables)
	q, err := queue.Factory(queue.ScrappingIntentsQueue)
	if err != nil {
		log.Fatalf("Error creating queue: %v", err)
	}

	// Connect to the queue
	if err := q.Connect(); err != nil {
		log.Fatalf("Error connecting to queue: %v", err)
	}
	defer q.Disconnect()

	// Start the processor with the specified parameters
	fmt.Printf("Starting ScrappingProcessor with %d workers (poll interval: %s)\n",
		*workerCount, *pollInterval)
	fmt.Printf("Using queue type: %s\n", queue.GetQueueTypeFromEnv())

	// Register handlers for scrapping intents
	handlersList := queue.NewHandlersList()

	handlersList.RegisterHandler(series.ScrapSeriesMessageType, series.NewScrapSeriesHandler(db))
	handlersList.RegisterHandler(seasons.ScrapSeasonsMessageType, seasons.NewScrapSeasonsHandler())
	handlersList.RegisterHandler(events.ScrapEventsMessageType, events.NewScrapEventsHandler())

	// Create and start the worker
	w := worker.NewWorker(q, handlersList, *workerCount, *pollInterval)
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
