package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type QueueUsingSQSIntegrationTestSuite struct {
	suite.Suite

	resetEnv func()
}

func (suite *QueueUsingSQSIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")

	fn.Must(env.LoadEnv())
}

func (suite *QueueUsingSQSIntegrationTestSuite) TearDownSuite() {
	suite.resetEnv()
}

func (suite *QueueUsingSQSIntegrationTestSuite) TestQueueConnection() {
	config := QueueConfig{
		QueueName:    os.Getenv("QUEUE_SCRAPPING_INTENTS"),
		Endpoint:     os.Getenv("SQS_ENDPOINT"),
		Region:       os.Getenv("SQS_REGION"),
		AccessKey:    os.Getenv("SQS_ACCESS_KEY"),
		SecretKey:    os.Getenv("SQS_SECRET_KEY"),
		SessionToken: os.Getenv("SQS_SESSION_TOKEN"),
	}

	queue := NewSQSQueueWithConfig(config)
	defer queue.Disconnect()

	suite.NoError(queue.Connect())
}

func TestIntegration_QueueUsingSQS(t *testing.T) {
	suite.Run(t, new(QueueUsingSQSIntegrationTestSuite))
}
