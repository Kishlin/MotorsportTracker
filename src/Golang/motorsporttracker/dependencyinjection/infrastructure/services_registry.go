package infrastructure

import (
	"context"
	"os"
	"sync"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/dependencyinjection/infrastructure"
	databaseImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	messagingImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

// ServicesRegistry holds the motorsportstats registry it scrapes through, so an app builds this one alone
// and closes everything with a single Close.
type ServicesRegistry struct {
	motorsportStats *motorsportstats.ServicesRegistry

	coreDB *databaseImpls.PGXPoolAdapter

	intentsQueue *messagingImpls.SQSQueue

	coreDBOnce       sync.Once
	intentsQueueOnce sync.Once
}

func NewServicesRegistry() *ServicesRegistry {
	return &ServicesRegistry{
		motorsportStats: motorsportstats.NewServicesRegistry(),
	}
}

func (s *ServicesRegistry) Close() {
	s.motorsportStats.Close()

	if s.coreDB != nil {
		s.coreDB.Close()
	}

	if s.intentsQueue != nil {
		s.intentsQueue.Disconnect()
	}
}

func (s *ServicesRegistry) MotorsportStats() *motorsportstats.ServicesRegistry {
	return s.motorsportStats
}

func (s *ServicesRegistry) GetCoreDatabase(ctx context.Context) *databaseImpls.PGXPoolAdapter {
	s.coreDBOnce.Do(func() {
		connStr := os.Getenv("POSTGRES_CORE_URL")
		if connStr == "" {
			panic("POSTGRES_CORE_URL environment variable is not set")
		}

		s.coreDB = databaseImpls.NewDatabaseUsingPGXPool(connStr)

		err := s.coreDB.Connect(ctx)
		if err != nil {
			panic("unable to connect to core databaseImpls: " + err.Error())
		}
	})

	return s.coreDB
}

func (s *ServicesRegistry) GetIntentsQueue() *messagingImpls.SQSQueue {
	s.intentsQueueOnce.Do(func() {
		config := messagingImpls.QueueConfig{
			QueueName:    os.Getenv("QUEUE_SCRAPPING_INTENTS"),
			Endpoint:     os.Getenv("SQS_ENDPOINT"),
			Region:       os.Getenv("SQS_REGION"),
			AccessKey:    os.Getenv("SQS_ACCESS_KEY"),
			SecretKey:    os.Getenv("SQS_SECRET_KEY"),
			SessionToken: os.Getenv("SQS_SESSION_TOKEN"),
		}

		s.intentsQueue = messagingImpls.NewSQSQueueWithConfig(config)

		err := s.intentsQueue.Connect()
		if err != nil {
			panic("unable to connect to intents queue: " + err.Error())
		}
	})

	return s.intentsQueue
}
