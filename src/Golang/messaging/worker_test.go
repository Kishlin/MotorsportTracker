package messaging

import (
	"context"
	"sync"
	"testing"
	"time"
)

func TestWorker_BasicProcessing(t *testing.T) {
	q := NewMemoryQueue()
	handler := &spyHandler{}
	handlersList := NewHandlersList()
	handlersList.RegisterHandler("test", handler)

	msg := Message{Type: "test", Metadata: map[string]string{"payload": "foo"}}
	_ = q.Send(msg)

	worker := NewWorker(q, handlersList, 1, 10*time.Millisecond)
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

	worker.Start(ctx)
	// Give worker time to process
	time.Sleep(50 * time.Millisecond)
	worker.Stop()

	handler.callLock.Lock()
	defer handler.callLock.Unlock()
	if !handler.called {
		t.Error("Handler should have been called for the message")
	}
	if handler.lastMsg.Type != msg.Type || handler.lastMsg.Metadata["payload"] != msg.Metadata["payload"] {
		t.Errorf("Handler received wrong message: got %v, want %v", handler.lastMsg, msg)
	}
}

func TestWorker_StopHaltsProcessing(t *testing.T) {
	q := NewMemoryQueue()
	handler := &spyHandler{}
	handlersList := NewHandlersList()
	handlersList.RegisterHandler("test", handler)

	worker := NewWorker(q, handlersList, 1, 10*time.Millisecond)
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

	worker.Start(ctx)
	worker.Stop()

	// Send a message after stopping
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "should_not_process"}}
	_ = q.Send(msg)
	time.Sleep(50 * time.Millisecond) // Give time for any potential processing

	handler.callLock.Lock()
	defer handler.callLock.Unlock()
	// Handler should not have been called at all
	if handler.called {
		t.Error("Handler should not process messages after stop")
	}
}

// Spy handler for testing
type spyHandler struct {
	called   bool
	lastMsg  Message
	callLock sync.Mutex
}

func (h *spyHandler) Handle(_ context.Context, message Message) error {
	h.callLock.Lock()
	defer h.callLock.Unlock()

	h.called = true
	h.lastMsg = message

	return nil
}
