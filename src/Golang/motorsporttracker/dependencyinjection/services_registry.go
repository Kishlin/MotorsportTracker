package dependencyinjection

import (
	"context"
	"os"
	"sync"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
)

type ServicesRegistry struct {
	connectorFactory connector.Factory
	databaseFactory  database.Factory
	queueFactory     messaging.Factory

	connectorOnce     sync.Once
	coreDBOnce        sync.Once
	clientCacheDBonce sync.Once
	intentsQueueOnce  sync.Once

	connector connector.Connector

	coreDB        database.Database
	clientCacheDB database.Database

	intentsQueue messaging.Queue
}

func NewServicesRegistry(
	connectorFactory connector.Factory,
	databaseFactory database.Factory,
	queueFactory messaging.Factory,
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

func (s *ServicesRegistry) GetConnector() connector.Connector {
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

func (s *ServicesRegistry) GetClientCacheDatabase(ctx context.Context) database.Database {
	s.clientCacheDBonce.Do(func() {
		connStr := os.Getenv("POSTGRES_CLIENT_CACHE_URL")
		if connStr == "" {
			panic("POSTGRES_CLIENT_CACHE_URL environment variable is not set")
		}

		var err error
		s.clientCacheDB, err = s.databaseFactory.NewDatabase(connStr)
		if err != nil {
			panic("unable to create client cache database: " + err.Error())
		} else if s.clientCacheDB == nil {
			panic("client cache database is nil after creation")
		}

		err = s.clientCacheDB.Connect(ctx)
		if err != nil {
			panic("unable to connect to client cache database: " + err.Error())
		}
	})

	return s.clientCacheDB
}

func (s *ServicesRegistry) GetIntentsQueue() messaging.Queue {
	s.intentsQueueOnce.Do(func() {
		config := messaging.QueueConfig{
			QueueName:    os.Getenv("QUEUE_SCRAPPING_INTENTS"),
			Endpoint:     os.Getenv("SQS_ENDPOINT"),
			Region:       os.Getenv("SQS_REGION"),
			AccessKey:    os.Getenv("SQS_ACCESS_KEY"),
			SecretKey:    os.Getenv("SQS_SECRET_KEY"),
			SessionToken: os.Getenv("SQS_SESSION_TOKEN"),
		}

		var err error
		s.intentsQueue, err = s.queueFactory.NewQueue(config)
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
