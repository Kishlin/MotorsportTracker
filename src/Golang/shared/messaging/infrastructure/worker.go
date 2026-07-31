package infrastructure

import (
	"context"
	"log/slog"
	"sync"
	"time"
)

// Worker processes messages from a queue
type Worker struct {
	queue        *SQSQueue
	handlersList *HandlersList
	workerCount  int
	pollInterval time.Duration
	stopChan     chan struct{}
	wg           sync.WaitGroup
}

// NewWorker creates a new worker
func NewWorker(q *SQSQueue, handlersList *HandlersList, workerCount int, pollInterval time.Duration) *Worker {
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

// maxBackoff caps the wait between polls after consecutive receive failures.
const maxBackoff = 60 * time.Second

// backoffFor returns how long to wait after consecutiveErrors failed receives:
// the poll interval doubled once per additional failure, capped at maxBackoff.
func (w *Worker) backoffFor(consecutiveErrors int) time.Duration {
	if w.pollInterval <= 0 {
		return maxBackoff
	}

	backoff := w.pollInterval
	for i := 1; i < consecutiveErrors; i++ {
		if backoff >= maxBackoff {
			return maxBackoff
		}

		backoff *= 2
	}

	if backoff > maxBackoff {
		return maxBackoff
	}

	return backoff
}

// wait sleeps for the given duration, returning false if the worker was asked
// to stop first. Backoff can reach a minute, so the wait must stay interruptible
// or Stop() would block for that long.
func (w *Worker) wait(ctx context.Context, duration time.Duration) bool {
	timer := time.NewTimer(duration)
	defer timer.Stop()

	select {
	case <-w.stopChan:
		return false
	case <-ctx.Done():
		return false
	case <-timer.C:
		return true
	}
}

// runWorker continuously polls for messages and processes them
func (w *Worker) runWorker(ctx context.Context, id int) {
	defer w.wg.Done()

	slog.Info("Worker started", "id", id)

	consecutiveErrors := 0

	for {
		select {
		case <-w.stopChan:
			slog.Info("Worker stopped", "id", id)
			return
		default:
			// Poll for messages
			messages, err := w.queue.Receive(10) // Process up to 10 messages at a time
			if err != nil {
				consecutiveErrors++
				backoff := w.backoffFor(consecutiveErrors)

				slog.Error("Error receiving messages",
					"err", err, "id", id,
					"consecutiveErrors", consecutiveErrors, "retryIn", backoff)

				if w.wait(ctx, backoff) == false {
					slog.Info("Worker stopped", "id", id)
					return
				}

				continue
			}

			consecutiveErrors = 0

			if len(messages) == 0 {
				// No messages, wait before polling again
				if w.wait(ctx, w.pollInterval) == false {
					slog.Info("Worker stopped", "id", id)
					return
				}

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
