package main

import (
	"flag"
	"fmt"
	"log"
	"os"
	"os/signal"
	"syscall"
	"time"

	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
	"github.com/kishlin/MotorsportTracker/src/Golang/worker"
)

func main() {
	// Define command-line flags specific to processing
	workerCount := flag.Int("workers", 3, "Number of concurrent workers for processing")
	pollInterval := flag.Duration("interval", 5*time.Second, "Queue polling interval")
	queueType := flag.String("queue", "memory", "Queue type: memory or sqs (default: memory)")

	// Parse the command-line flags
	flag.Parse()

	// Show help information if explicitly requested
	if len(os.Args) > 1 && (os.Args[1] == "-h" || os.Args[1] == "--help") {
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

	// Start the processor with the specified parameters
	fmt.Printf("Starting ScrappingProcessor with %d workers (poll interval: %s)\n",
		*workerCount, *pollInterval)
	fmt.Printf("Using queue type: %s\n", *queueType)

	// Create and start the worker
	w := worker.NewWorker(q, *workerCount, *pollInterval)
	w.Start()

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
	fmt.Println("  -queue=<type>     Queue type: memory or sqs (default: memory)")
	fmt.Println("\nExamples:")
	fmt.Println("  ./ScrappingProcessor")
	fmt.Println("  ./ScrappingProcessor -workers=5")
	fmt.Println("  ./ScrappingProcessor -interval=10s")
	fmt.Println("  ./ScrappingProcessor -queue=sqs")
}
