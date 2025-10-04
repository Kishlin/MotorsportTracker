package env

import (
	"os"
	"testing"

	_func "github.com/kishlin/MotorsportTracker/src/Golang/func"
	"github.com/stretchr/testify/require"
)

func TestEnv_OverrideAppEnv(t *testing.T) {
	initial := os.Getenv(appEnvKey)
	expected := "please work"

	resetFunc := OverrideAppEnv(expected)
	require.Equal(t, expected, os.Getenv(appEnvKey))

	resetFunc()
	require.Equal(t, initial, os.Getenv(appEnvKey))
}

func TestEnv_GetEnv(t *testing.T) {
	t.Run("it defaults to production", func(t *testing.T) {
		resetFunc := OverrideAppEnv("")
		defer resetFunc()

		_func.Must(os.Unsetenv(appEnvKey))
	})

	t.Run("it reads the value of APP_ENV", func(t *testing.T) {
		expected := "please work"

		resetFunc := OverrideAppEnv(expected)
		defer resetFunc()

		actual := getEnv()

		require.Equal(t, expected, actual)
	})
}

func TestEnv_EnvIsValid(t *testing.T) {
	validEnvs := []string{"dev", "tests", "production"}
	for _, env := range validEnvs {
		if !envIsValid(env) {
			t.Errorf("Expected envIsValid to return true for valid env '%s', got false", env)
		}
	}

	invalidEnvs := []string{"staging", "local", "invalid", ""}
	for _, env := range invalidEnvs {
		if envIsValid(env) {
			t.Errorf("Expected envIsValid to return false for invalid env '%s', got true", env)
		}
	}
}
