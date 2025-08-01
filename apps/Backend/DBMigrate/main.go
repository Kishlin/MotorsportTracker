package main

import (
	"context"
	"errors"
	"log"
	"os"

	"github.com/golang-migrate/migrate/v4"
	migratepgx "github.com/golang-migrate/migrate/v4/database/pgx/v5"
	_ "github.com/golang-migrate/migrate/v4/source/file"
	pgxstd "github.com/jackc/pgx/v5/stdlib"
	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

func main() {
	// Create a context for the application
	ctx, cancel := context.WithCancel(context.Background())
	defer cancel()

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

	// Initialize database connection
	pool := database.GetInstance()
	if err := pool.ConnectCore(ctx, connStr); err != nil {
		log.Fatalf("Failed to connect to database: %v", err)
	}
	defer pool.Close()

	// Create a migration driver using the pgx connection pool
	driver, err := migratepgx.WithInstance(pgxstd.OpenDBFromPool(pool.CorePool), &migratepgx.Config{})
	if err != nil {
		log.Fatalf("Failed to create migration driver: %v", err)
		return
	}

	// Create a new migration instance
	m, err := migrate.NewWithDatabaseInstance(sourceStr, "pgx", driver)
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
