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
	suite.Run("it does not panic if no error", func() {
		defer func() {
			require.Nil(suite.T(), recover())
		}()
		Must(nil)
	})

	suite.Run("it panics on error", func() {
		defer func() {
			require.NotNil(suite.T(), recover())
		}()
		Must(errors.New("this should cause a panic"))
	})
}

func (suite *FnUnitTestSuite) TestMustReturn() {
	suite.Run("it returns value if no error", func() {
		result := MustReturn("expected value", nil)
		require.Equal(suite.T(), "expected value", result)
	})

	suite.Run("it panics on error", func() {
		defer func() {
			require.NotNil(suite.T(), recover())
		}()
		MustReturn("hi", errors.New("this should cause a panic"))
	})
}

func (suite *FnUnitTestSuite) TestPtr() {
	suite.Run("it returns a pointer to the value", func() {
		value := 42
		ptr := Ptr(value)
		require.NotNil(suite.T(), ptr)
		require.Equal(suite.T(), value, *ptr)
	})
}

func (suite *FnUnitTestSuite) TestDeref() {
	suite.Run("it returns the value if there is one", func() {
		intVal := 42
		strVal := "test"
		boolVal := true

		require.Equal(suite.T(), 42, Deref(&intVal, 0))
		require.Equal(suite.T(), true, Deref(&boolVal, false))
		require.Equal(suite.T(), "test", Deref(&strVal, "wrong"))
	})

	suite.Run("it returns the default value if pointer is empty", func() {
		var intVal *int = nil
		var strVal *string = nil
		var boolVal *bool = nil

		require.Equal(suite.T(), 42, Deref(intVal, 42))
		require.Equal(suite.T(), true, Deref(boolVal, true))
		require.Equal(suite.T(), "test", Deref(strVal, "test"))
	})
}

func TestUnit_Fn(t *testing.T) {
	suite.Run(t, new(FnUnitTestSuite))
}
