package database

import (
	"context"
	"fmt"
	"sync"

	"github.com/jackc/pgx/v5/pgxpool"
)

// PostgresDB is a singleton wrapper around pgxpool.Pool
type PostgresDB struct {
	CorePool *pgxpool.Pool
	once     sync.Once
}

var instance *PostgresDB
var once sync.Once

// GetInstance returns the singleton instance of PostgresDB
func GetInstance() *PostgresDB {
	once.Do(func() {
		instance = &PostgresDB{}
	})
	return instance
}

// ConnectCore initializes the connection to the core database
func (db *PostgresDB) ConnectCore(ctx context.Context, connStr string) error {
	var err error
	db.once.Do(func() {
		config, configErr := pgxpool.ParseConfig(connStr)
		if configErr != nil {
			err = fmt.Errorf("unable to parse database connection config: %w", configErr)
			return
		}

		// Set some reasonable defaults for the connection pool
		config.MaxConns = 10

		db.CorePool, err = pgxpool.NewWithConfig(ctx, config)
		if err != nil {
			err = fmt.Errorf("unable to connect to database: %w", err)
			return
		}

		// Verify the connection
		if pingErr := db.CorePool.Ping(ctx); pingErr != nil {
			err = fmt.Errorf("database ping failed: %w", pingErr)
			db.CorePool.Close()
			db.CorePool = nil
			return
		}
	})

	return err
}

// Close closes all database connections
func (db *PostgresDB) Close() {
	if db.CorePool != nil {
		db.CorePool.Close()
	}
}
