package infrastructure

import (
	"log/slog"
	"os"
	"strings"
)

func SetupSlog() {
	opts := &slog.HandlerOptions{
		Level:     parseLogLevel(os.Getenv("LOG_LEVEL")),
		AddSource: true,
	}

	slog.SetDefault(slog.New(slog.NewTextHandler(os.Stdout, opts)))

	slog.Info("Log handler set up", "level", opts.Level.Level().String())
}

func parseLogLevel(logLevel string) slog.Level {
	switch strings.ToLower(logLevel) {
	case "debug":
		return slog.LevelDebug
	case "info":
		return slog.LevelInfo
	case "warn":
		return slog.LevelWarn
	case "error":
		return slog.LevelError
	default:
		return slog.LevelInfo // Default to Info level if not set
	}
}
