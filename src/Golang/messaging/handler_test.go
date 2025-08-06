package messaging

import (
	"context"
	"errors"
	"sync"
	"testing"
)

func TestHandler_RegisterHandler(t *testing.T) {
	handlersList := NewHandlersList()
	handler1 := &mockHandler{id: "handler1"}
	handler2 := &mockHandler{id: "handler2"}

	handlersList.RegisterHandler("type1", handler1)
	handlersList.RegisterHandler("type2", handler2)

	if len(handlersList.handlers) != 2 {
		t.Errorf("Expected 2 handlers, got %d", len(handlersList.handlers))
	}

	if handlersList.handlers["type1"] != handler1 {
		t.Error("Handler1 should be registered for type1")
	}
	if handlersList.handlers["type2"] != handler2 {
		t.Error("Handler2 should be registered for type2")
	}
}

func TestHandler_RegisterHandlerOverwrite(t *testing.T) {
	handlersList := NewHandlersList()
	handler1 := &mockHandler{id: "handler1"}
	handler2 := &mockHandler{id: "handler2"}
	messageType := "test-message"

	handlersList.RegisterHandler(messageType, handler1)
	handlersList.RegisterHandler(messageType, handler2) // Overwrite

	registeredHandler := handlersList.handlers[messageType]
	if registeredHandler != handler2 {
		t.Error("Second handler should overwrite the first one")
	}
	if len(handlersList.handlers) != 1 {
		t.Errorf("Expected 1 handler after overwrite, got %d", len(handlersList.handlers))
	}
}

func TestHandler_HandleMessage_Success(t *testing.T) {
	handlersList := NewHandlersList()
	handler := &mockHandler{}
	messageType := "test-message"
	message := Message{
		Type:     messageType,
		Metadata: map[string]string{"key": "value"},
	}

	handlersList.RegisterHandler(messageType, handler)

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)

	if err != nil {
		t.Errorf("HandleMessage should not return error: %v", err)
	}

	handler.lock.Lock()
	defer handler.lock.Unlock()
	if !handler.called {
		t.Error("Handler should have been called")
	}
	if handler.lastContext != ctx {
		t.Error("Handler should receive the correct context")
	}
	if handler.lastMessage.Type != message.Type {
		t.Errorf("Handler should receive correct message type: got %s, want %s", handler.lastMessage.Type, message.Type)
	}
	if handler.lastMessage.Metadata["key"] != message.Metadata["key"] {
		t.Error("Handler should receive correct message metadata")
	}
}

func TestHandler_HandleMessage_HandlerNotFound(t *testing.T) {
	handlersList := NewHandlersList()
	message := Message{
		Type:     "unknown-message-type",
		Metadata: map[string]string{"key": "value"},
	}

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)

	if err == nil {
		t.Error("HandleMessage should return error for unknown message type")
	}

	expectedError := "no handler registered for message type: unknown-message-type"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error message '%s', got '%s'", expectedError, err.Error())
	}
}

func TestHandler_HandleMessage_HandlerReturnsError(t *testing.T) {
	handlersList := NewHandlersList()
	expectedError := errors.New("handler processing error")
	handler := &mockHandler{returnError: expectedError}
	messageType := "test-message"
	message := Message{
		Type:     messageType,
		Metadata: map[string]string{"key": "value"},
	}

	handlersList.RegisterHandler(messageType, handler)

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)

	if err != expectedError {
		t.Errorf("HandleMessage should return handler's error: got %v, want %v", err, expectedError)
	}

	handler.lock.Lock()
	defer handler.lock.Unlock()
	if !handler.called {
		t.Error("Handler should have been called even if it returns error")
	}
}

func TestHandler_HandleMessage_MultipleHandlersIndependent(t *testing.T) {
	handlersList := NewHandlersList()
	handler1 := &mockHandler{id: "handler1"}
	handler2 := &mockHandler{id: "handler2"}

	handlersList.RegisterHandler("type1", handler1)
	handlersList.RegisterHandler("type2", handler2)

	message1 := Message{Type: "type1", Metadata: map[string]string{"data": "message1"}}
	message2 := Message{Type: "type2", Metadata: map[string]string{"data": "message2"}}

	ctx := context.Background()

	err1 := handlersList.HandleMessage(ctx, message1)
	err2 := handlersList.HandleMessage(ctx, message2)

	if err1 != nil {
		t.Errorf("First message handling should not error: %v", err1)
	}
	if err2 != nil {
		t.Errorf("Second message handling should not error: %v", err2)
	}

	handler1.lock.Lock()
	if !handler1.called || handler1.lastMessage.Type != "type1" {
		t.Error("Handler1 should handle type1 messages")
	}
	handler1.lock.Unlock()

	handler2.lock.Lock()
	if !handler2.called || handler2.lastMessage.Type != "type2" {
		t.Error("Handler2 should handle type2 messages")
	}
	handler2.lock.Unlock()
}

func TestHandler_HandleMessage_EmptyMessageType(t *testing.T) {
	handlersList := NewHandlersList()
	message := Message{
		Type:     "",
		Metadata: map[string]string{"key": "value"},
	}

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)

	if err == nil {
		t.Error("HandleMessage should return error for empty message type")
	}

	expectedError := "no handler registered for message type: "
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error message '%s', got '%s'", expectedError, err.Error())
	}
}

// Mock handler for testing
type mockHandler struct {
	id          string
	called      bool
	lastContext context.Context
	lastMessage Message
	returnError error
	lock        sync.Mutex
}

func (h *mockHandler) Handle(ctx context.Context, message Message) error {
	h.lock.Lock()
	defer h.lock.Unlock()

	h.called = true
	h.lastContext = ctx
	h.lastMessage = message

	return h.returnError
}
