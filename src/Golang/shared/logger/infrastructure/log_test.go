package infrastructure

import (
	"log/slog"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type LoggerUnitTestSuite struct {
	suite.Suite
}

func (suite *LoggerUnitTestSuite) TestParseLogLevel() {
	tests := []struct {
		name     string
		logLevel string
		expected slog.Level
	}{
		{"Debug Level", "debug", slog.LevelDebug},
		{"Info Level", "info", slog.LevelInfo},
		{"Warn Level", "warn", slog.LevelWarn},
		{"Error Level", "error", slog.LevelError},
		{"Default Level", "unknown", slog.LevelInfo},
		{"Case Insensitive", "ErRoR", slog.LevelError},
	}

	for _, tt := range tests {
		suite.T().Run(tt.name, func(t *testing.T) {
			result := parseLogLevel(tt.logLevel)
			require.Equal(t, tt.expected, result)
		})
	}
}

func TestUnit_Logger(t *testing.T) {
	suite.Run(t, new(LoggerUnitTestSuite))
}
