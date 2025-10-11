package messaging

import (
	"context"
	"log/slog"
	"sync"
	"time"

	domain "github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
)

// Worker processes messages from a queue
type Worker struct {
	queue        domain.Queue
	handlersList *domain.HandlersList
	workerCount  int
	pollInterval time.Duration
	stopChan     chan struct{}
	wg           sync.WaitGroup
}

// NewWorker creates a new worker
func NewWorker(q domain.Queue, handlersList *domain.HandlersList, workerCount int, pollInterval time.Duration) *Worker {
	return &Worker{
		queue:        q,
		handlersList: handlersList,
		workerCount:  workerCount,
		pollInterval: pollInterval,
		stopChan:     make(chan struct{}),
	}
}

// Start begins processing messages
func (w *Worker) Start(ctx context.Context) {
	slog.Info("Starting workers", "workerCount", w.workerCount, "pollInterval", w.pollInterval)

	for i := 0; i < w.workerCount; i++ {
		w.wg.Add(1)
		go w.runWorker(ctx, i)
	}
}

// Stop signals the workers to stop
func (w *Worker) Stop() {
	slog.Debug("Stopping workers")
	close(w.stopChan)
	w.wg.Wait()
	slog.Info("Worker stopped")
}

// runWorker continuously polls for messages and processes them
func (w *Worker) runWorker(ctx context.Context, id int) {
	defer w.wg.Done()

	slog.Info("Worker started", "id", id)

	for {
		select {
		case <-w.stopChan:
			slog.Info("Worker stopped", "id", id)
			return
		default:
			// Poll for messages
			messages, err := w.queue.Receive(10) // Process up to 10 messages at a time
			if err != nil {
				slog.Error("Error receiving messages", "err", err)
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
				err := w.handlersList.HandleMessage(ctx, message)

				if err != nil {
					slog.Error("Error handling message", "handle", handle, "err", err)
					continue
				}

				// Delete message from queue after successful processing
				if err := w.queue.Delete(handle); err != nil {
					slog.Error("Error deleting message", "handle", handle, "err", err)
				}
			}
		}
	}
}
