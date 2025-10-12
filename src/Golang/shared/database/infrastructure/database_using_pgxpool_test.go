package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type DatabaseUsingPGXPoolIntegrationTestSuite struct {
	suite.Suite

	resetEnv func()
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")

	fn.Must(env.LoadEnv())
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) TearDownSuite() {
	suite.resetEnv()
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) TestCoreConnection() {
	db := NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	testConnection(suite, db)
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) TestClientCacheConnection() {
	db := NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CLIENT_CACHE_URL"))
	testConnection(suite, db)
}

func TestIntegration_DatabaseUsingPGXPool(t *testing.T) {
	suite.Run(t, new(DatabaseUsingPGXPoolIntegrationTestSuite))
}

func testConnection(suite *DatabaseUsingPGXPoolIntegrationTestSuite, db *PGXPoolAdapter) {
	ctx := suite.T().Context()

	require.NoError(suite.T(), db.Connect(ctx))
	require.NoError(suite.T(), db.Ping(ctx))
	db.Close()
}
