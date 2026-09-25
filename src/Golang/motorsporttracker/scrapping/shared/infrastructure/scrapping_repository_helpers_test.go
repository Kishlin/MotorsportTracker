package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/suite"
)

type ScrappingRepositoryHelpersUnitTestSuite struct {
	suite.Suite
}

func (suite *ScrappingRepositoryHelpersUnitTestSuite) TestPrepareTimestamp() {
	suite.Run("returns nil and zero on a nil pointer", func() {
		dbVal, hashVal := PrepareTimestamp(nil)
		suite.Nil(dbVal)
		suite.Equal(int64(0), hashVal)
	})

	suite.Run("parses and defers a timestamp", func() {
		timestamp := int64(1741870800)
		dbVal, hashVal := PrepareTimestamp(&timestamp)

		suite.NotNil(dbVal)
		suite.Equal(timestamp, dbVal.Unix())
		suite.Equal(timestamp, hashVal)
	})
}

func TestUnit_ScrappingRepository_Helpers(t *testing.T) {
	suite.Run(t, new(ScrappingRepositoryHelpersUnitTestSuite))
}
