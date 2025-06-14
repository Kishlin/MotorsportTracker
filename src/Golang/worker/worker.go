package worker

import (
	"log"
	"sync"
	"time"

	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

// Worker processes messages from a queue
type Worker struct {
	queue        queue.Queue
	handlersList *queue.HandlersList
	workerCount  int
	pollInterval time.Duration
	stopChan     chan struct{}
	wg           sync.WaitGroup
}

// NewWorker creates a new worker
func NewWorker(q queue.Queue, handlersList *queue.HandlersList, workerCount int, pollInterval time.Duration) *Worker {
	return &Worker{
		queue:        q,
		handlersList: handlersList,
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
			for handle, message := range messages {
				err := w.handlersList.HandleMessage(message)

				if err != nil {
					log.Printf("Error processing message: %v", err)
					continue
				}

				// Delete message from queue after successful processing
				if err := w.queue.Delete(handle); err != nil {
					log.Printf("Error deleting message: %v", err)
				}
			}
		}
	}
}
