package main

import (
	"errors"
	"log"
	"os"

	"github.com/golang-migrate/migrate/v4"
	_ "github.com/golang-migrate/migrate/v4/database/postgres"
	_ "github.com/golang-migrate/migrate/v4/source/file"
)

func main() {
	sourceStr := os.Getenv("DB_MIGRATE_SOURCE")
	if sourceStr == "" {
		log.Fatalf("DB_MIGRATE_SOURCE environment variable not set")
		return
	}

	connStr := os.Getenv("DB_MIGRATE_DATABASE_URL")
	if connStr == "" {
		log.Fatalf("DB_MIGRATE_DATABASE_URL environment variable not set")
		return
	}

	m, err := migrate.New(sourceStr, connStr)
	if err != nil {
		log.Fatalf("Failed to create migration: %v", err)
		return
	}
	defer func(m *migrate.Migrate) {
		err, _ := m.Close()
		if err != nil {
			log.Fatalf("Failed to close migration: %v", err)
		}
	}(m)

	// Run the migrations
	err = m.Up()
	if errors.Is(err, migrate.ErrNoChange) {
		log.Println("No new migrations to apply")
		return
	} else if err != nil {
		log.Fatalf("Failed to create migration driver: %v", err)
		return
	}

	log.Println("Migrations applied successfully")
}
