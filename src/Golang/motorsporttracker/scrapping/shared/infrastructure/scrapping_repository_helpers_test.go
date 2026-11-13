package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/suite"
)

type ScrappingRepositoryHelpersUnitTestSuite struct {
	suite.Suite
}

func (suite *ScrappingRepositoryHelpersUnitTestSuite) TestPrepareTimestamp() {
	suite.Run("returns nil on a nil pointer", func() {
		suite.Nil(PrepareTimestamp(nil))
	})

	suite.Run("parses a timestamp", func() {
		timestamp := int64(1741870800)
		dbVal := PrepareTimestamp(&timestamp)

		suite.NotNil(dbVal)
		suite.Equal(timestamp, dbVal.Unix())
	})
}

func TestUnit_ScrappingRepository_Helpers(t *testing.T) {
	suite.Run(t, new(ScrappingRepositoryHelpersUnitTestSuite))
}
