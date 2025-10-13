package domain

import (
	"errors"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type FnUnitTestSuite struct {
	suite.Suite
}

func (suite *FnUnitTestSuite) TestMust() {
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

func (suite *FnUnitTestSuite) TestMustReturn() {
	suite.T().Run("it returns value if no error", func(t *testing.T) {
		result := MustReturn("expected value", nil)
		require.Equal(t, "expected value", result)
	})

	suite.T().Run("it panics on error", func(t *testing.T) {
		defer func() {
			require.NotNil(suite.T(), recover())
		}()
		MustReturn("hi", errors.New("this should cause a panic"))
	})
}

func (suite *FnUnitTestSuite) TestPtr() {
	suite.T().Run("it returns a pointer to the value", func(t *testing.T) {
		value := 42
		ptr := Ptr(value)
		require.NotNil(t, ptr)
		require.Equal(t, value, *ptr)
	})
}

func TestUnit_Fn(t *testing.T) {
	suite.Run(t, new(FnUnitTestSuite))
}
