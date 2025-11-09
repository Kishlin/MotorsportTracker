package infrastructure

import (
	"context"
	"os"
	"sync"
	"testing"
	"time"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type WorkerIntegrationTestSuite struct {
	suite.Suite

	queue        *SQSQueue
	handlersList *HandlersList
	handler      *spyHandler
}

func (suite *WorkerIntegrationTestSuite) SetupSuite() {
	suite.handler = &spyHandler{}
	suite.handlersList = NewHandlersList()
	suite.handlersList.RegisterHandler("test", suite.handler)

	config := QueueConfig{
		QueueName:    os.Getenv("QUEUE_SCRAPPING_INTENTS"),
		Endpoint:     os.Getenv("SQS_ENDPOINT"),
		Region:       os.Getenv("SQS_REGION"),
		AccessKey:    os.Getenv("SQS_ACCESS_KEY"),
		SecretKey:    os.Getenv("SQS_SECRET_KEY"),
		SessionToken: os.Getenv("SQS_SESSION_TOKEN"),
	}

	suite.queue = NewSQSQueueWithConfig(config)
	suite.NoError(suite.queue.Connect())
}

func (suite *WorkerIntegrationTestSuite) TearDownSuite() {
	suite.queue.Disconnect()
	suite.queue = nil
}

func (suite *WorkerIntegrationTestSuite) TearDownTest() {
	suite.handler.reset()
}

func (suite *WorkerIntegrationTestSuite) TestBasicProcessing() {
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "foo"}}
	_ = suite.queue.Send(msg)

	worker := NewWorker(suite.queue, suite.handlersList, 1, 10*time.Millisecond)
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

	worker.Start(ctx)
	// Give worker time to process
	time.Sleep(50 * time.Millisecond)
	worker.Stop()

	suite.handler.callLock.Lock()
	defer suite.handler.callLock.Unlock()
	require.True(suite.T(), suite.handler.called, "Handler should have been called")
	require.Equal(suite.T(), msg, suite.handler.lastMsg)
}

func (suite *WorkerIntegrationTestSuite) TestStopHaltsProcessing() {
	worker := NewWorker(suite.queue, suite.handlersList, 1, 10*time.Millisecond)
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

	worker.Start(ctx)
	worker.Stop()

	// Send a message after stopping
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "should_not_process"}}
	_ = suite.queue.Send(msg)
	time.Sleep(50 * time.Millisecond) // Give time for any potential processing

	suite.handler.callLock.Lock()
	defer suite.handler.callLock.Unlock()
	// Handler should not have been called at all
	require.False(suite.T(), suite.handler.called, "Handler should not process messages after stop")
}

func TestIntegration_Worker(t *testing.T) {
	suite.Run(t, new(WorkerIntegrationTestSuite))
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

func (h *spyHandler) reset() {
	h.callLock.Lock()
	defer h.callLock.Unlock()

	h.called = false
	h.lastMsg = Message{}
}
