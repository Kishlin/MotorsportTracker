package queue

import (
	"context"
	"errors"
)

type Handler interface {
	Handle(ctx context.Context, message Message) error
}

type HandlersList struct {
	handlers map[string]Handler
}

// NewHandlersList creates a new HandlersList.
func NewHandlersList() *HandlersList {
	return &HandlersList{
		handlers: make(map[string]Handler),
	}
}

// RegisterHandler registers a handler for a specific message type.
func (h *HandlersList) RegisterHandler(messageType string, handler Handler) {
	h.handlers[messageType] = handler
}

// HandleMessage processes a message using the appropriate handler.
func (h *HandlersList) HandleMessage(ctx context.Context, message Message) error {
	handler, exists := h.handlers[message.Type]
	if !exists {
		return errors.New("no handler registered for message type: " + message.Type)
	}

	return handler.Handle(ctx, message)
}
