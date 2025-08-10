package main

import (
	"errors"
	"fmt"
	"os"

	"github.com/golang-migrate/migrate/v4"
	_ "github.com/golang-migrate/migrate/v4/database/postgres"
	_ "github.com/golang-migrate/migrate/v4/source/file"

	"github.com/kishlin/MotorsportTracker/src/Golang/logger"
)

func main() {
	logger.SetupSlog()

	sourceStr := os.Getenv("DB_MIGRATE_SOURCE")
	if sourceStr == "" {
		fmt.Fprintf(os.Stderr, "DB_MIGRATE_SOURCE environment variable not set")
		return
	}

	connStr := os.Getenv("DB_MIGRATE_DATABASE_URL")
	if connStr == "" {
		fmt.Fprintf(os.Stderr, "DB_MIGRATE_DATABASE_URL environment variable not set")
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
