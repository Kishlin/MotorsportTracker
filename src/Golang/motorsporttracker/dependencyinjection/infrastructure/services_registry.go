package infrastructure

import (
	"context"
	"os"
	"path/filepath"
	"strings"
	"sync"

	connectorImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	gatewayImpls "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/infrastructure"
	cacheImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/cache/infrastructure"
	clientImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/client/infrastructure"
	databaseImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	messagingImpls "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ServicesRegistry struct {
	motorsportStatsGateway *gatewayImpls.GatewayUsingConnector

	coreDB        *databaseImpls.PGXPoolAdapter
	clientCacheDB *databaseImpls.PGXPoolAdapter

	intentsQueue *messagingImpls.SQSQueue

	motorsportStatsGatewayOnce sync.Once
	coreDBOnce                 sync.Once
	clientCacheDBonce          sync.Once
	intentsQueueOnce           sync.Once
}

func NewServicesRegistry() *ServicesRegistry {
	return &ServicesRegistry{}
}

func (s *ServicesRegistry) Close() {
	if s.coreDB != nil {
		s.coreDB.Close()
	}

	if s.clientCacheDB != nil {
		s.clientCacheDB.Close()
	}

	if s.intentsQueue != nil {
		s.intentsQueue.Disconnect()
	}
}

func (s *ServicesRegistry) GetMotorsportStatsGateway(ctx context.Context) *gatewayImpls.GatewayUsingConnector {
	s.motorsportStatsGatewayOnce.Do(func() {
		host := os.Getenv("REMOTE_API_HOST")
		if host == "" {
			panic("REMOTE_API_HOST environment variable is not set")
		}

		connector := connectorImpls.NewCachedConnector(
			connectorImpls.NewConnectorUsingClient(
				clientImpls.NewClient(host),
			),
			cacheImpls.NewDatabaseCache(s.GetClientCacheDatabase(ctx)),
		)

		useFSCache := strings.ToLower(os.Getenv("USE_FS_CACHE")) == "true"
		if useFSCache {
			connector = connectorImpls.NewCachedConnector(
				connector,
				cacheImpls.NewFileSystemCache(
					filepath.Join(os.Getenv("PROJECT_DIR"), "etc", "ConnectorCache"),
					".json",
				),
			)
		}

		s.motorsportStatsGateway = gatewayImpls.NewGatewayUsingConnector(connector)

		if s.motorsportStatsGateway == nil {
			panic("unable to create motorsport stats gateway")
		}
	})

	return s.motorsportStatsGateway
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

func (s *ServicesRegistry) GetClientCacheDatabase(ctx context.Context) *databaseImpls.PGXPoolAdapter {
	s.clientCacheDBonce.Do(func() {
		connStr := os.Getenv("POSTGRES_CLIENT_CACHE_URL")
		if connStr == "" {
			panic("POSTGRES_CLIENT_CACHE_URL environment variable is not set")
		}

		s.clientCacheDB = databaseImpls.NewDatabaseUsingPGXPool(connStr)

		err := s.clientCacheDB.Connect(ctx)
		if err != nil {
			panic("unable to connect to core databaseImpls: " + err.Error())
		}
	})

	return s.clientCacheDB
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
