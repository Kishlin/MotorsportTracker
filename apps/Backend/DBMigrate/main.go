package main

import (
	"errors"
	"fmt"
	"os"

	"github.com/golang-migrate/migrate/v4"
	_ "github.com/golang-migrate/migrate/v4/database/postgres"
	_ "github.com/golang-migrate/migrate/v4/source/file"
)

func main() {
	connStr, err := connectionStringFromEnv()
	if err != nil {
		fmt.Fprintf(os.Stderr, "Failed to construct connection string: %v\n", err)
		return
	}

	sourceStr := os.Getenv("DB_MIGRATE_SOURCE")
	if sourceStr == "" {
		fmt.Fprintf(os.Stderr, "DB_MIGRATE_SOURCE environment variable not set")
		return
	}

	m, err := migrate.New(sourceStr, connStr)
	if err != nil {
		fmt.Fprintf(os.Stderr, "Failed to create migration: %v\n", err)
		return
	}
	defer func(m *migrate.Migrate) {
		err, _ := m.Close()
		if err != nil {
			fmt.Fprintf(os.Stderr, "Failed to close migration: %v\n", err)
		}
	}(m)

	// Run the migrations
	err = m.Up()
	if errors.Is(err, migrate.ErrNoChange) {
		fmt.Println("No new migrations to apply")
		return
	} else if err != nil {
		fmt.Printf("Failed to create migration driver: %v\n", err)
		return
	}

	fmt.Println("Migrations applied successfully")
}

func connectionStringFromEnv() (string, error) {
	db := os.Getenv("DB_MIGRATE_DATABASE")
	if db == "" {
		return "", fmt.Errorf("DB_MIGRATE_DATABASE environment variable not set")
	}

	user := os.Getenv("DB_MIGRATE_USER")
	if user == "" {
		return "", fmt.Errorf("DB_MIGRATE_USER environment variable not set")
	}

	password := os.Getenv("DB_MIGRATE_PASSWORD")
	if password == "" {
		return "", fmt.Errorf("DB_MIGRATE_PASSWORD environment variable not set")
	}

	host := os.Getenv("DB_MIGRATE_HOST")
	if host == "" {
		return "", fmt.Errorf("DB_MIGRATE_HOST environment variable not set")
	}

	port := os.Getenv("DB_MIGRATE_PORT")
	if port == "" {
		return "", fmt.Errorf("DB_MIGRATE_PORT environment variable not set")
	}

	noSSL := os.Getenv("DB_MIGRATE_NO_SSL") != ""

	sslMode := "require"
	if noSSL {
		sslMode = "disable"
	}

	connStr := fmt.Sprintf("postgres://%s:%s@%s:%s/%s?sslmode=%s", user, password, host, port, db, sslMode)
	return connStr, nil
}
