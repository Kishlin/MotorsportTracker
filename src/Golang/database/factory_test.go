package database

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type DatabaseFactoryUnitTestSuite struct {
	suite.Suite
}

func (suite *DatabaseFactoryUnitTestSuite) TestNewDatabase() {
	factory := NewDatabaseFactory()

	db, err := factory.NewDatabase("memory://test")
	require.NoError(suite.T(), err)

	memDb, ok := db.(*MemoryDatabase)
	require.True(suite.T(), ok)
	require.NotNil(suite.T(), memDb)

	db, err = factory.NewDatabase("unsupported://")
	require.Error(suite.T(), err)
	require.Nil(suite.T(), db)
}

func TestUnit_DatabaseFactory(t *testing.T) {
	suite.Run(t, new(DatabaseFactoryUnitTestSuite))
}
