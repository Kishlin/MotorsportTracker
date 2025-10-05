package _func

import (
	"errors"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type FuncUnitTestSuite struct {
	suite.Suite
}

func (suite *FuncUnitTestSuite) TestMust() {
	suite.T().Run("it does not panic if no error", func(t *testing.T) {
		defer func() {
			require.Nil(suite.T(), recover())
		}()
		Must(nil)
	})

	suite.T().Run("it panics on error", func(t *testing.T) {
		defer func() {
			require.NotNil(suite.T(), recover())
		}()
		Must(errors.New("this should cause a panic"))
	})
}

func TestUnit_Func(t *testing.T) {
	suite.Run(t, new(FuncUnitTestSuite))
}
