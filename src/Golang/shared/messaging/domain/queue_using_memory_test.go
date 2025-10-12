package domain

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type QueueUsingMemoryUnitTestSuite struct {
	suite.Suite

	q *MemoryQueue
}

func (suite *QueueUsingMemoryUnitTestSuite) SetupTest() {
	suite.q = NewMemoryQueue()
	fn.Must(suite.q.Connect())
}

func (suite *QueueUsingMemoryUnitTestSuite) TearDownTest() {
	suite.q.Disconnect()
	suite.q = nil
}

func (suite *QueueUsingMemoryUnitTestSuite) TestSendAndReceive() {
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "foo"}}

	err := suite.q.Send(msg)
	require.NoError(suite.T(), err)

	received, err := suite.q.Receive(1)
	require.NoError(suite.T(), err)
	require.Len(suite.T(), received, 1)
	for _, m := range received {
		require.Equal(suite.T(), msg, m)
	}
}

func (suite *QueueUsingMemoryUnitTestSuite) TestReceiveLimit() {
	for i := 0; i < 5; i++ {
		msg := Message{Type: "test", Metadata: map[string]string{"payload": string(rune('a' + i))}}
		_ = suite.q.Send(msg)
	}

	received, err := suite.q.Receive(3)
	require.NoError(suite.T(), err)
	require.Len(suite.T(), received, 3)
}

func (suite *QueueUsingMemoryUnitTestSuite) TestReceiveEmpty() {
	received, err := suite.q.Receive(1)
	require.NoError(suite.T(), err)
	require.Empty(suite.T(), received)
}

func (suite *QueueUsingMemoryUnitTestSuite) TestHiddenMessages() {
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "bar"}}
	_ = suite.q.Send(msg)

	received, _ := suite.q.Receive(1)
	require.Len(suite.T(), received, 1)

	// Try to receive again, should get 0 since message is hidden
	received2, _ := suite.q.Receive(1)
	require.Empty(suite.T(), received2)
}

func TestUnit_QueueUsingMemory(t *testing.T) {
	suite.Run(t, new(QueueUsingMemoryUnitTestSuite))
}
