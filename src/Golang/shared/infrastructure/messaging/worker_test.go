package messaging

import (
	"context"
	"sync"
	"testing"
	"time"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/fn"
	domain "github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
)

type WorkerFunctionalTestSuite struct {
	suite.Suite

	q            *domain.MemoryQueue
	handlersList *domain.HandlersList
	handler      *spyHandler
}

func (suite *WorkerFunctionalTestSuite) SetupSuite() {
	suite.handler = &spyHandler{}
	suite.handlersList = domain.NewHandlersList()
	suite.handlersList.RegisterHandler("test", suite.handler)

	suite.q = domain.NewMemoryQueue()
	fn.Must(suite.q.Connect())
}

func (suite *WorkerFunctionalTestSuite) TearDownSuite() {
	suite.q.Disconnect()
	suite.q = nil
}

func (suite *WorkerFunctionalTestSuite) TearDownTest() {
	suite.handler.reset()
}

func (suite *WorkerFunctionalTestSuite) TestBasicProcessing() {
	msg := domain.Message{Type: "test", Metadata: map[string]string{"payload": "foo"}}
	_ = suite.q.Send(msg)

	worker := NewWorker(suite.q, suite.handlersList, 1, 10*time.Millisecond)
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

func (suite *WorkerFunctionalTestSuite) TestStopHaltsProcessing() {
	worker := NewWorker(suite.q, suite.handlersList, 1, 10*time.Millisecond)
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

	worker.Start(ctx)
	worker.Stop()

	// Send a message after stopping
	msg := domain.Message{Type: "test", Metadata: map[string]string{"payload": "should_not_process"}}
	_ = suite.q.Send(msg)
	time.Sleep(50 * time.Millisecond) // Give time for any potential processing

	suite.handler.callLock.Lock()
	defer suite.handler.callLock.Unlock()
	// Handler should not have been called at all
	require.False(suite.T(), suite.handler.called, "Handler should not process messages after stop")
}

func TestFunctional_Worker(t *testing.T) {
	suite.Run(t, new(WorkerFunctionalTestSuite))
}

// Spy handler for testing
type spyHandler struct {
	called   bool
	lastMsg  domain.Message
	callLock sync.Mutex
}

func (h *spyHandler) Handle(_ context.Context, message domain.Message) error {
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
	h.lastMsg = domain.Message{}
}
