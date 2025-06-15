package database

import (
	"context"
	"fmt"
	"sort"
)

// Migration represents a database migration
type Migration struct {
	Version     int
	Description string
	SQL         string
}

// MigrationRunner handles database migrations
type MigrationRunner struct {
	DB *PostgresDB
}

// NewMigrationRunner creates a new migration runner
func NewMigrationRunner(db *PostgresDB) *MigrationRunner {
	return &MigrationRunner{
		DB: db,
	}
}

// EnsureMigrationTable ensures the migrations table exists in the database
func (r *MigrationRunner) EnsureMigrationTable(ctx context.Context) error {
	_, err := r.DB.CorePool.Exec(ctx, `
		CREATE TABLE IF NOT EXISTS migrations (
			id SERIAL PRIMARY KEY,
			version INT NOT NULL UNIQUE,
			description TEXT NOT NULL,
			executed_at TIMESTAMP NOT NULL DEFAULT NOW()
		)
	`)
	return err
}

// GetAppliedMigrations returns the versions of migrations that have already been applied
func (r *MigrationRunner) GetAppliedMigrations(ctx context.Context) (map[int]bool, error) {
	rows, err := r.DB.CorePool.Query(ctx, "SELECT version FROM migrations ORDER BY version")
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	appliedMigrations := make(map[int]bool)
	for rows.Next() {
		var version int
		if err := rows.Scan(&version); err != nil {
			return nil, err
		}
		appliedMigrations[version] = true
	}

	return appliedMigrations, rows.Err()
}

// RunMigration applies a single migration
func (r *MigrationRunner) RunMigration(ctx context.Context, migration Migration) error {
	fmt.Printf("Running migration %d: %s\n", migration.Version, migration.Description)

	// Begin a transaction
	tx, err := r.DB.CorePool.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)

	// Execute the migration SQL
	_, err = tx.Exec(ctx, migration.SQL)
	if err != nil {
		return fmt.Errorf("migration %d failed: %w", migration.Version, err)
	}

	// Record the migration
	_, err = tx.Exec(ctx, "INSERT INTO migrations (version, description) VALUES ($1, $2)",
		migration.Version, migration.Description)
	if err != nil {
		return err
	}

	// Commit the transaction
	return tx.Commit(ctx)
}

// RunMigrations runs all pending migrations from either embedded SQL or files
func (r *MigrationRunner) RunMigrations(ctx context.Context, migrations []Migration) error {
	// Ensure the migrations table exists
	if err := r.EnsureMigrationTable(ctx); err != nil {
		return err
	}

	// Get already applied migrations
	appliedMigrations, err := r.GetAppliedMigrations(ctx)
	if err != nil {
		return err
	}

	// Sort migrations by version
	sort.Slice(migrations, func(i, j int) bool {
		return migrations[i].Version < migrations[j].Version
	})

	// Apply pending migrations
	for _, migration := range migrations {
		if !appliedMigrations[migration.Version] {
			if err := r.RunMigration(ctx, migration); err != nil {
				return err
			}
		} else {
			fmt.Printf("Migration %d already applied, skipping\n", migration.Version)
		}
	}

	return nil
}

// GetCoreMigrations returns the list of core migrations
func GetCoreMigrations() []Migration {
	return []Migration{
		{
			Version:     1,
			Description: "Create series table",
			SQL: `
				CREATE TABLE IF NOT EXISTS series (
					id SERIAL PRIMARY KEY,
					uuid VARCHAR(255) NOT NULL UNIQUE,
					name VARCHAR(255) NOT NULL,
					short_name VARCHAR(255),
					short_code VARCHAR(50) NOT NULL,
					category VARCHAR(100) NOT NULL,
					created_at TIMESTAMP NOT NULL DEFAULT NOW(),
					updated_at TIMESTAMP NOT NULL DEFAULT NOW()
				);
				
				CREATE INDEX IF NOT EXISTS idx_series_uuid ON series(uuid);
			`,
		},
		// Add more migrations here as needed
	}
}
