package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type EnvUnitTestSuite struct {
	suite.Suite
}

func (suite *EnvUnitTestSuite) TestOverrideAppEnv() {
	initial := os.Getenv(appEnvKey)
	expected := "please work"

	resetFunc := OverrideAppEnv(expected)
	require.Equal(suite.T(), expected, os.Getenv(appEnvKey))

	resetFunc()
	require.Equal(suite.T(), initial, os.Getenv(appEnvKey))
}

func (suite *EnvUnitTestSuite) TestGetEnv() {
	suite.Run("it defaults to production", func() {
		resetFunc := OverrideAppEnv("")
		defer resetFunc()

		fn.Must(os.Unsetenv(appEnvKey))

		require.Equal(suite.T(), "production", getEnv())
	})

	suite.Run("it reads the value of APP_ENV", func() {
		expected := "please work"

		resetFunc := OverrideAppEnv(expected)
		defer resetFunc()

		actual := getEnv()

		require.Equal(suite.T(), expected, actual)
	})
}

func (suite *EnvUnitTestSuite) TestEnvIsValid() {
	validEnvs := []string{"dev", "tests", "production"}
	for _, env := range validEnvs {
		require.True(suite.T(), envIsValid(env))
	}

	invalidEnvs := []string{"staging", "local", "invalid", ""}
	for _, env := range invalidEnvs {
		require.False(suite.T(), envIsValid(env))
	}
}

func TestUnit_Env(t *testing.T) {
	suite.Run(t, new(EnvUnitTestSuite))
}
