package database

import (
	"context"
	"fmt"
	"log"
	"sync"

	"github.com/jackc/pgx/v5/pgxpool"
)

type PGXPoolAdapter struct {
	once sync.Once

	connStr string

	pool *pgxpool.Pool
}

func NewPGXPoolAdapter(connStr string) *PGXPoolAdapter {
	return &PGXPoolAdapter{
		connStr: connStr,
	}
}

func (p *PGXPoolAdapter) Connect(ctx context.Context) error {
	var err error
	p.once.Do(func() {
		config, configErr := pgxpool.ParseConfig(p.connStr)
		if configErr != nil {
			err = fmt.Errorf("unable to parse database connection config: %w", configErr)
			return
		}

		pool, poolErr := pgxpool.NewWithConfig(ctx, config)
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

		log.Printf("Successfully connected to the database at %s", p.connStr)

		p.pool = pool
	})

	return err
}

func (p *PGXPoolAdapter) Ping(ctx context.Context) error {
	return p.pool.Ping(ctx)
}

func (p *PGXPoolAdapter) Exec(ctx context.Context, sql string, arguments ...any) error {
	_, err := p.pool.Exec(ctx, sql, arguments...)
	if err != nil {
		return fmt.Errorf("failed to execute SQL: %w", err)
	}
	return nil
}

func (p *PGXPoolAdapter) Close() {
	p.pool.Close()
}
