package database

import (
	"context"
	"fmt"
	"sync"

	"github.com/jackc/pgx/v5/pgxpool"
)

// PostgresDBPool is an interface for methods used by PostgresDB
type PostgresDBPool interface {
	Close()
	Ping(ctx context.Context) error
}

type PostgresDBPoolFactory interface {
	ParseConfig(connStr string) (any, error) // 'any' to allow mocking config
	NewWithConfig(ctx context.Context, config any) (PostgresDBPool, error)
}

type PostgresDB struct {
	factory PostgresDBPoolFactory
	once    sync.Once
	pool    PostgresDBPool
}

// GetInstance returns a new instance of PostgresDB for each database
func GetInstance(factory PostgresDBPoolFactory) *PostgresDB {
	return &PostgresDB{factory: factory}
}

// Connect initializes the connection to the core database
func (db *PostgresDB) Connect(ctx context.Context, connStr string) error {
	var err error
	db.once.Do(func() {
		config, configErr := db.factory.ParseConfig(connStr)
		if configErr != nil {
			err = fmt.Errorf("unable to parse database connection config: %w", configErr)
			return
		}

		pool, poolErr := db.factory.NewWithConfig(ctx, config)
		if poolErr != nil {
			err = fmt.Errorf("unable to connect to database: %w", poolErr)
			return
		}

		// Verify the connection
		if pingErr := pool.Ping(ctx); pingErr != nil {
			err = fmt.Errorf("database ping failed: %w", pingErr)
			pool.Close()
			return
		}

		db.pool = pool
	})

	return err
}

// Close closes all database connections
func (db *PostgresDB) Close() {
	if db.pool != nil {
		db.pool.Close()
		db.pool = nil
	}
}

func (db *PostgresDB) Ping(ctx context.Context) error {
	if db.pool == nil {
		return fmt.Errorf("database pool is closed")
	}
	return db.pool.Ping(ctx)
}

// --- Production PGXPoolFactory Implementation ---

type PGXPoolFactory struct{}

func NewPGXPoolFactory() *PGXPoolFactory {
	return &PGXPoolFactory{}
}

func (f *PGXPoolFactory) ParseConfig(connStr string) (any, error) {
	return pgxpool.ParseConfig(connStr)
}

func (f *PGXPoolFactory) NewWithConfig(ctx context.Context, config any) (PostgresDBPool, error) {
	pgxConfig, ok := config.(*pgxpool.Config)
	if !ok {
		return nil, fmt.Errorf("invalid config type for pgxpool")
	}
	pool, err := pgxpool.NewWithConfig(ctx, pgxConfig)
	if err != nil {
		return nil, err
	}
	return &PGXPoolWrapper{pool: pool}, nil
}

// --- PGXPoolWrapper implements PostgresDBPool ---

type PGXPoolWrapper struct {
	pool *pgxpool.Pool
}

func (w *PGXPoolWrapper) Close() {
	w.pool.Close()
}

func (w *PGXPoolWrapper) Ping(ctx context.Context) error {
	return w.pool.Ping(ctx)
}
