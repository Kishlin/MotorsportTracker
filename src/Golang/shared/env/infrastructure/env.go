package infrastructure

import (
	"fmt"
	"os"

	"github.com/joho/godotenv"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

var allowedEnvs = []string{"dev", "tests", "production"}

const appEnvKey = "APP_ENV"

func LoadEnv() error {
	env := getEnv()
	if envIsValid(env) == false {
		return fmt.Errorf("invalid APP_ENV value: %s", env)
	}

	projectDir := os.Getenv("PROJECT_DIR")
	if projectDir == "" {
		projectDir = "."
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
