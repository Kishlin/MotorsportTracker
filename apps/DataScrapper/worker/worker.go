package worker

import (
	"fmt"
	"log"
	"sync"
	"time"

	"github.com/kishlin/MotorsportTracker/apps/DataScrapper/intent"
	"github.com/kishlin/MotorsportTracker/apps/DataScrapper/queue"
)

// Worker processes messages from a queue
type Worker struct {
	queue        queue.Queue
	workerCount  int
	pollInterval time.Duration
	stopChan     chan struct{}
	wg           sync.WaitGroup
}

// NewWorker creates a new worker
func NewWorker(q queue.Queue, workerCount int, pollInterval time.Duration) *Worker {
	return &Worker{
		queue:        q,
		workerCount:  workerCount,
		pollInterval: pollInterval,
		stopChan:     make(chan struct{}),
	}
}

// Start begins processing messages
func (w *Worker) Start() {
	log.Printf("Starting %d worker(s) with poll interval of %s", w.workerCount, w.pollInterval)

	for i := 0; i < w.workerCount; i++ {
		w.wg.Add(1)
		go w.runWorker(i)
	}
}

// Stop signals the workers to stop
func (w *Worker) Stop() {
	log.Println("Stopping workers...")
	close(w.stopChan)
	w.wg.Wait()
	log.Println("All workers stopped")
}

// runWorker continuously polls for messages and processes them
func (w *Worker) runWorker(id int) {
	defer w.wg.Done()

	log.Printf("Worker %d started", id)

	for {
		select {
		case <-w.stopChan:
			log.Printf("Worker %d stopping", id)
			return
		default:
			// Poll for messages
			messages, err := w.queue.Receive(10) // Process up to 10 messages at a time
			if err != nil {
				log.Printf("Error receiving messages: %v", err)
				time.Sleep(w.pollInterval)
				continue
			}

			if len(messages) == 0 {
				// No messages, wait before polling again
				time.Sleep(w.pollInterval)
				continue
			}

			// Process each message
			for _, message := range messages {
				if err := w.processMessage(message); err != nil {
					log.Printf("Error processing message: %v", err)
					continue
				}

				// Delete message from queue after successful processing
				if err := w.queue.Delete(message); err != nil {
					log.Printf("Error deleting message: %v", err)
				}
			}
		}
	}
}

// processMessage handles a single message from the queue
func (w *Worker) processMessage(message queue.Message) error {
	log.Printf("Processing message of type: %s, target: %s", message.Type, message.Target)

	// Convert queue message to intent
	var i intent.Intent

	// Map message Type to intent.Type
	switch message.Type {
	case string(intent.ScrapAllSeries):
		i = intent.CreateAllSeriesIntent()
	case string(intent.ScrapAllSeasons):
		i = intent.CreateAllSeasonsIntent(message.Target)
	case string(intent.ScrapAllEvents):
		season, ok := message.Metadata["season"]
		if !ok {
			return fmt.Errorf("missing season metadata for ScrapAllEvents")
		}
		i = intent.CreateAllEventsIntent(message.Target, season)
	case string(intent.ScrapEventSession):
		season, ok := message.Metadata["season"]
		if !ok {
			return fmt.Errorf("missing season metadata for ScrapEventSession")
		}
		event, ok := message.Metadata["event"]
		if !ok {
			return fmt.Errorf("missing event metadata for ScrapEventSession")
		}
		session, ok := message.Metadata["session"]
		if !ok {
			return fmt.Errorf("missing session metadata for ScrapEventSession")
		}
		i = intent.CreateEventSessionIntent(message.Target, season, event, session)
	default:
		return fmt.Errorf("unknown intent type: %s", message.Type)
	}

	return w.processIntent(i)
}

// processIntent performs the actual processing for a specific intent
func (w *Worker) processIntent(i intent.Intent) error {
	switch i.Type {
	case intent.ScrapAllSeries:
		return w.scrapAllSeries()
	case intent.ScrapAllSeasons:
		return w.scrapAllSeasons(i.Series)
	case intent.ScrapAllEvents:
		return w.scrapAllEvents(i.Series, i.Season)
	case intent.ScrapEventSession:
		return w.scrapEventSession(i.Series, i.Season, i.Event, i.Session)
	default:
		return fmt.Errorf("unknown intent type: %s", i.Type)
	}
}

// Individual processing methods
func (w *Worker) scrapAllSeries() error {
	log.Println("Scraping all series...")
	// TODO: Implement actual scraping logic
	return nil
}

func (w *Worker) scrapAllSeasons(series string) error {
	log.Printf("Scraping all seasons for series: %s", series)
	// TODO: Implement actual scraping logic
	return nil
}

func (w *Worker) scrapAllEvents(series, season string) error {
	log.Printf("Scraping all events for series: %s, season: %s", series, season)
	// TODO: Implement actual scraping logic
	return nil
}

func (w *Worker) scrapEventSession(series, season, event, session string) error {
	log.Printf("Scraping session: %s for event: %s in series: %s, season: %s",
		session, event, series, season)
	// TODO: Implement actual scraping logic
	return nil
}
