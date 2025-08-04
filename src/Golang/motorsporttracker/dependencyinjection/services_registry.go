package dependencyinjection

import (
	"context"
	"os"
	"sync"

	"github.com/kishlin/MotorsportTracker/src/Golang/client"
	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

type ServicesRegistry struct {
	connectorFactory client.ConnectorFactory
	databaseFactory  database.Factory
	queueFactory     queue.Factory

	connectorOnce    sync.Once
	coreDBOnce       sync.Once
	intentsQueueOnce sync.Once

	connector    client.Connector
	coreDB       database.Database
	intentsQueue queue.Queue
}

func NewServicesRegistry(
	connectorFactory client.ConnectorFactory,
	databaseFactory database.Factory,
	queueFactory queue.Factory,
) *ServicesRegistry {
	return &ServicesRegistry{
		connectorFactory: connectorFactory,
		databaseFactory:  databaseFactory,
		queueFactory:     queueFactory,
	}
}

func (s *ServicesRegistry) Close() {
	if s.coreDB != nil {
		s.coreDB.Close()
	}

	if s.intentsQueue != nil {
		s.intentsQueue.Disconnect()
	}
}

func (s *ServicesRegistry) GetConnector() client.Connector {
	s.connectorOnce.Do(func() {
		s.connector = s.connectorFactory.NewConnector()
		if s.connector == nil {
			panic("unable to create connector")
		}
	})

	return s.connector
}

func (s *ServicesRegistry) GetCoreDatabase(ctx context.Context) database.Database {
	s.coreDBOnce.Do(func() {
		connStr := os.Getenv("POSTGRES_CORE_URL")
		if connStr == "" {
			panic("POSTGRES_CORE_URL environment variable is not set")
		}

		var err error
		s.coreDB, err = s.databaseFactory.NewDatabase(connStr)
		if err != nil {
			panic("unable to create core database: " + err.Error())
		} else if s.coreDB == nil {
			panic("core database is nil after creation")
		}

		err = s.coreDB.Connect(ctx)
		if err != nil {
			panic("unable to connect to core database: " + err.Error())
		}
	})

	return s.coreDB
}

func (s *ServicesRegistry) GetIntentsQueue() queue.Queue {
	s.intentsQueueOnce.Do(func() {
		config := queue.SQSConfig{
			QueueName:    os.Getenv("QUEUE_SCRAPPING_INTENTS"),
			Endpoint:     os.Getenv("SQS_ENDPOINT"),
			Region:       os.Getenv("SQS_REGION"),
			AccessKey:    os.Getenv("SQS_ACCESS_KEY"),
			SecretKey:    os.Getenv("SQS_SECRET_KEY"),
			SessionToken: os.Getenv("SQS_SESSION_TOKEN"),
		}

		var err error
		s.intentsQueue, err = s.queueFactory.NewSQSQueue(config)
		if err != nil {
			panic("unable to create intents queue: " + err.Error())
		} else if s.intentsQueue == nil {
			panic("intents queue is nil after creation")
		}

		err = s.intentsQueue.Connect()
		if err != nil {
			panic("unable to connect to intents queue: " + err.Error())
		}
	})

	return s.intentsQueue
}
