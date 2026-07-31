package infrastructure

import (
	"errors"
	"fmt"
	"io/fs"
	"os"
	"path/filepath"
	"strings"

	"github.com/joho/godotenv"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

var allowedEnvs = []string{"dev", "tests", "production"}

const appEnvKey = "APP_ENV"

// bootstrapDebug reports how the environment was resolved, before slog exists.
//
// LoadEnv runs ahead of logger.SetupSlog in every app, so slog.Debug here would
// be swallowed by the default Info-level handler and never reach the output.
// LOG_LEVEL is therefore read straight from the process environment — not from
// the .env files, which have not been loaded yet at this point — and the message
// goes to stderr so it never mixes into a command's real stdout.
func bootstrapDebug(format string, args ...any) {
	if strings.ToLower(os.Getenv("LOG_LEVEL")) != "debug" {
		return
	}

	_, _ = fmt.Fprintf(os.Stderr, format+"\n", args...)
}

func LoadEnv() (err error) {
	env := getEnv()
	if envIsValid(env) == false {
		return fmt.Errorf("invalid APP_ENV value: %s", env)
	}

	projectDir := os.Getenv("PROJECT_DIR")
	if projectDir == "" {
		projectDir, err = findProjectDir()
		if err != nil {
			return fmt.Errorf("looking for project dir: %w", err)
		}
	}

	bootstrapDebug("Resolved project dir: %s", projectDir)

	// Env vars are not overridden, so we need to load prioritized files first
	_ = godotenv.Load(projectDir + "/.env." + env + ".local")
	_ = godotenv.Load(projectDir + "/.env." + env)
	_ = godotenv.Load(projectDir + "/.env.local")
	_ = godotenv.Load(projectDir + "/.env")

	bootstrapDebug("Loaded environment: %s", env)

	return nil
}

func OverrideAppEnv(env string) func() {
	initialValue := os.Getenv(appEnvKey)
	fn.Must(os.Setenv(appEnvKey, env))

	return func() {
		fn.Must(os.Setenv(appEnvKey, initialValue))
	}
}

func findProjectDir() (string, error) {
	candidate, err := os.Getwd()
	if err != nil {
		return "", fmt.Errorf("finding executable directory: %w", err)
	}

	maxDepth := 10

	for i := 0; i < maxDepth; i++ {
		hasEnvFile, err := exists(filepath.Join(candidate, ".env"))
		if err != nil {
			return "", fmt.Errorf("checking whether dir %s has an .env file: %w", candidate, err)
		}
		if hasEnvFile {
			return candidate, nil
		}

		parent := filepath.Dir(candidate)
		if parent == candidate {
			break
		}
		candidate = parent
	}

	return "", fmt.Errorf(".env file not found (searched up to %d levels to %s)", maxDepth, candidate)
}

func exists(path string) (bool, error) {
	bootstrapDebug("Checking existence of .env file: %s", path)
	_, err := os.Stat(path)
	if err == nil {
		return true, nil
	}
	if errors.Is(err, fs.ErrNotExist) {
		return false, nil
	}
	return false, err
}

func getEnv() string {
	env := os.Getenv(appEnvKey)
	if env == "" {
		env = "production"
	}

	return env
}

func envIsValid(env string) bool {
	for _, allowedEnv := range allowedEnvs {
		if env == allowedEnv {
			return true
		}
	}

	return false
}
