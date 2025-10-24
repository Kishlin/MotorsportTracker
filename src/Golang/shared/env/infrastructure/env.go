package infrastructure

import (
	"errors"
	"fmt"
	"io/fs"
	"os"
	"path/filepath"

	"github.com/joho/godotenv"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

var allowedEnvs = []string{"dev", "tests", "production"}

const appEnvKey = "APP_ENV"

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

	fmt.Println("Project Dir: " + projectDir)

	// Env vars are not overridden, so we need to load prioritized files first
	_ = godotenv.Load(projectDir + "/.env." + env + ".local")
	_ = godotenv.Load(projectDir + "/.env." + env)
	_ = godotenv.Load(projectDir + "/.env.local")
	_ = godotenv.Load(projectDir + "/.env")

	fmt.Println("Loaded environment:", env)

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
	fmt.Println("Checking existence of .env file:", path)
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
