package logger

import (
	"log/slog"
	"testing"
)

func TestLogger_ParseLogLevel(t *testing.T) {
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
		t.Run(tt.name, func(t *testing.T) {
			result := parseLogLevel(tt.logLevel)
			if result != tt.expected {
				t.Errorf("Expected %v, got %v", tt.expected, result)
			}
		})
	}
}
