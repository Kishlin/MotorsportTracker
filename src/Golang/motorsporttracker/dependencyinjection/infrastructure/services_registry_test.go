package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type ServiceRegistryUnitTestSuite struct {
	suite.Suite
}

// Resource Cleanup - Close Method Safety
func (suite *ServiceRegistryUnitTestSuite) TestClose() {
	registry := NewServicesRegistry()

	defer func() {
		require.Nil(suite.T(), recover())
	}()

	registry.Close()
}

func TestUnit_ServiceRegistry(t *testing.T) {
	suite.Run(t, new(ServiceRegistryUnitTestSuite))
}

type ServiceRegistryIntegrationTestSuite struct {
	suite.Suite

	resetEnv func()

	registry *ServicesRegistry
}

func (suite *ServiceRegistryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	suite.registry = NewServicesRegistry()

	fn.Must(env.LoadEnv())
}

func (suite *ServiceRegistryIntegrationTestSuite) TeardownSuite() {
	suite.registry.Close()
	suite.resetEnv()
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetMotorsportStatsGateway() {
	ctx := suite.T().Context()

	gateway := suite.registry.GetMotorsportStatsGateway(ctx)
	require.NotNil(suite.T(), gateway)

	// Ensure the same instance is returned on subsequent calls
	gateway2 := suite.registry.GetMotorsportStatsGateway(ctx)
	require.Equal(suite.T(), gateway, gateway2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetCoreDatabase() {
	ctx := suite.T().Context()

	db := suite.registry.GetCoreDatabase(ctx)
	require.NotNil(suite.T(), db)
	require.NoError(suite.T(), db.Ping(ctx))

	// Ensure the same instance is returned on subsequent calls
	db2 := suite.registry.GetCoreDatabase(ctx)
	require.Equal(suite.T(), db, db2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetClientCacheDatabase() {
	ctx := suite.T().Context()

	db := suite.registry.GetClientCacheDatabase(ctx)
	require.NotNil(suite.T(), db)
	require.NoError(suite.T(), db.Ping(ctx))

	// Ensure the same instance is returned on subsequent calls
	db2 := suite.registry.GetClientCacheDatabase(ctx)
	require.Equal(suite.T(), db, db2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetIntentsQueue() {
	queue := suite.registry.GetIntentsQueue()
	require.NotNil(suite.T(), queue)

	// Ensure the same instance is returned on subsequent calls
	queue2 := suite.registry.GetIntentsQueue()
	require.Equal(suite.T(), queue, queue2)
}

func TestIntegration_ServiceRegistry(t *testing.T) {
	suite.Run(t, new(ServiceRegistryIntegrationTestSuite))
}
