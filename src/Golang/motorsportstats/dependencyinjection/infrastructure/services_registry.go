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
)

type ServicesRegistry struct {
	baseConnector *connectorImpls.ConnectorUsingClient
	connector     connectorImpls.Connector
	gateway       *gatewayImpls.GatewayUsingConnector

	clientCacheDB *databaseImpls.PGXPoolAdapter

	baseConnectorOnce sync.Once
	connectorOnce     sync.Once
	gatewayOnce       sync.Once
	clientCacheDBOnce sync.Once
}

func NewServicesRegistry() *ServicesRegistry {
	return &ServicesRegistry{}
}

func (s *ServicesRegistry) Close() {
	if s.clientCacheDB != nil {
		s.clientCacheDB.Close()
	}
}

// GetBaseConnector returns the connector without any cache: every call reaches motorsportstats, and every
// payload is validated against the schemas.
func (s *ServicesRegistry) GetBaseConnector() *connectorImpls.ConnectorUsingClient {
	s.baseConnectorOnce.Do(func() {
		host := os.Getenv("REMOTE_API_HOST")
		if host == "" {
			panic("REMOTE_API_HOST environment variable is not set")
		}

		s.baseConnector = connectorImpls.NewConnectorUsingClient(clientImpls.NewClient(host))
	})

	return s.baseConnector
}

// GetConnector returns the base connector behind the DatabaseCache, and the FileSystemCache on top when
// USE_FS_CACHE=true.
func (s *ServicesRegistry) GetConnector(ctx context.Context) connectorImpls.Connector {
	s.connectorOnce.Do(func() {
		s.connector = connectorImpls.NewCachedConnector(
			s.GetBaseConnector(),
			cacheImpls.NewDatabaseCache(s.GetClientCacheDatabase(ctx)),
		)

		useFSCache := strings.ToLower(os.Getenv("USE_FS_CACHE")) == "true"
		if useFSCache {
			projectDir := os.Getenv("PROJECT_DIR")
			if projectDir == "" {
				panic("PROJECT_DIR environment variable is not set, the filesystem cache cannot be located")
			}

			s.connector = connectorImpls.NewCachedConnector(
				s.connector,
				cacheImpls.NewFileSystemCache(filepath.Join(projectDir, "etc", "ConnectorCache"), ".json"),
			)
		}
	})

	return s.connector
}

func (s *ServicesRegistry) GetGateway(ctx context.Context) *gatewayImpls.GatewayUsingConnector {
	s.gatewayOnce.Do(func() {
		s.gateway = gatewayImpls.NewGatewayUsingConnector(s.GetConnector(ctx))
	})

	return s.gateway
}

func (s *ServicesRegistry) GetClientCacheDatabase(ctx context.Context) *databaseImpls.PGXPoolAdapter {
	s.clientCacheDBOnce.Do(func() {
		connStr := os.Getenv("POSTGRES_CLIENT_CACHE_URL")
		if connStr == "" {
			panic("POSTGRES_CLIENT_CACHE_URL environment variable is not set")
		}

		s.clientCacheDB = databaseImpls.NewDatabaseUsingPGXPool(connStr)

		err := s.clientCacheDB.Connect(ctx)
		if err != nil {
			panic("unable to connect to client cache database: " + err.Error())
		}
	})

	return s.clientCacheDB
}
