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

	registry *ServicesRegistry
}

func (suite *ServiceRegistryIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	suite.registry = NewServicesRegistry()

	fn.Must(env.LoadEnv())
}

func (suite *ServiceRegistryIntegrationTestSuite) TearDownSuite() {
	suite.registry.Close()
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetBaseConnector() {
	connector := suite.registry.GetBaseConnector()
	require.NotNil(suite.T(), connector)

	// Ensure the same instance is returned on subsequent calls
	connector2 := suite.registry.GetBaseConnector()
	require.Same(suite.T(), connector, connector2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetConnector() {
	ctx := suite.T().Context()

	connector := suite.registry.GetConnector(ctx)
	require.NotNil(suite.T(), connector)

	// Ensure the same instance is returned on subsequent calls
	connector2 := suite.registry.GetConnector(ctx)
	require.Same(suite.T(), connector, connector2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetGateway() {
	ctx := suite.T().Context()

	gateway := suite.registry.GetGateway(ctx)
	require.NotNil(suite.T(), gateway)

	// Ensure the same instance is returned on subsequent calls
	gateway2 := suite.registry.GetGateway(ctx)
	require.Same(suite.T(), gateway, gateway2)
}

func (suite *ServiceRegistryIntegrationTestSuite) TestGetClientCacheDatabase() {
	ctx := suite.T().Context()

	db := suite.registry.GetClientCacheDatabase(ctx)
	require.NotNil(suite.T(), db)
	require.NoError(suite.T(), db.Ping(ctx))

	// Ensure the same instance is returned on subsequent calls
	db2 := suite.registry.GetClientCacheDatabase(ctx)
	require.Same(suite.T(), db, db2)
}

func TestIntegration_ServiceRegistry(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(ServiceRegistryIntegrationTestSuite))
}
