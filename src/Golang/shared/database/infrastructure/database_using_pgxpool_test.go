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
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")

	fn.Must(env.LoadEnv())
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) TestCoreConnection() {
	db := NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	doTestConnection(suite, db)
}

func (suite *DatabaseUsingPGXPoolIntegrationTestSuite) TestClientCacheConnection() {
	db := NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CLIENT_CACHE_URL"))
	doTestConnection(suite, db)
}

func TestIntegration_DatabaseUsingPGXPool(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(DatabaseUsingPGXPoolIntegrationTestSuite))
}

func doTestConnection(suite *DatabaseUsingPGXPoolIntegrationTestSuite, db *PGXPoolAdapter) {
	ctx := suite.T().Context()

	require.NoError(suite.T(), db.Connect(ctx))
	require.NoError(suite.T(), db.Ping(ctx))
	db.Close()
}
