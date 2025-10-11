package messaging

import (
	"context"
	"errors"
	"sync"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type HandlersLstUnitTestSuite struct {
	suite.Suite
}

func (suite *HandlersLstUnitTestSuite) TestRegisterHandler() {
	handlersList := NewHandlersList()
	handler1 := &mockHandler{id: "handler1"}
	handler2 := &mockHandler{id: "handler2"}

	handlersList.RegisterHandler("type1", handler1)
	handlersList.RegisterHandler("type2", handler2)

	require.Len(suite.T(), handlersList.handlers, 2)
	require.Contains(suite.T(), handlersList.handlers, "type1")
	require.Contains(suite.T(), handlersList.handlers, "type2")
}

func (suite *HandlersLstUnitTestSuite) TestRegisterHandlerOverwrite() {
	handlersList := NewHandlersList()
	handler1 := &mockHandler{id: "handler1"}
	handler2 := &mockHandler{id: "handler2"}

	handlersList.RegisterHandler("same-type", handler1)
	handlersList.RegisterHandler("same-type", handler2) // Overwrite

	require.Len(suite.T(), handlersList.handlers, 1)
	require.Equal(suite.T(), handlersList.handlers["same-type"], handler2)
}

func (suite *HandlersLstUnitTestSuite) TestHandleMessage_Success() {
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
	require.NoError(suite.T(), err)

	handler.lock.Lock()
	defer handler.lock.Unlock()
	require.True(suite.T(), handler.called)
	require.Equal(suite.T(), handler.lastMessage, message)
}

func (suite *HandlersLstUnitTestSuite) TestHandleMessage_HandlerNotFound() {
	handlersList := NewHandlersList()
	message := Message{
		Type:     "unknown-message-type",
		Metadata: map[string]string{"key": "value"},
	}

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)
	require.Error(suite.T(), err)
}

func (suite *HandlersLstUnitTestSuite) TestHandleMessage_HandlerReturnsError() {
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

	require.Equal(suite.T(), expectedError, err)

	handler.lock.Lock()
	defer handler.lock.Unlock()
	require.True(suite.T(), handler.called)
}

func (suite *HandlersLstUnitTestSuite) TestHandleMessage_MultipleHandlersIndependent() {
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
	require.NoError(suite.T(), err1)
	require.NoError(suite.T(), err2)

	handler1.lock.Lock()
	require.True(suite.T(), handler1.called)
	require.Equal(suite.T(), handler1.lastMessage, message1)
	handler1.lock.Unlock()

	handler2.lock.Lock()
	require.True(suite.T(), handler2.called)
	require.Equal(suite.T(), handler2.lastMessage, message2)
	handler2.lock.Unlock()
}

func (suite *HandlersLstUnitTestSuite) TestHandleMessage_EmptyMessageType() {
	handlersList := NewHandlersList()
	message := Message{
		Type:     "",
		Metadata: map[string]string{"key": "value"},
	}

	ctx := context.Background()
	err := handlersList.HandleMessage(ctx, message)
	require.Error(suite.T(), err)
}

func TestUnit_HandlersList(t *testing.T) {
	suite.Run(t, new(HandlersLstUnitTestSuite))
}

// Mock handler for testing
type mockHandler struct {
	id          string
	called      bool
	lastMessage Message
	returnError error
	lock        sync.Mutex
}

func (h *mockHandler) Handle(_ context.Context, message Message) error {
	h.lock.Lock()
	defer h.lock.Unlock()

	h.called = true
	h.lastMessage = message

	return h.returnError
}
