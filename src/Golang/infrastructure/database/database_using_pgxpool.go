package database

import (
	"context"
	"fmt"
	"log/slog"
	"regexp"
	"strings"
	"sync"

	"github.com/jackc/pgx/v5"
	"github.com/jackc/pgx/v5/pgxpool"
)

type PGXPoolAdapter struct {
	once sync.Once

	connStr string

	pool *pgxpool.Pool
}

func NewDatabaseUsingPGXPool(connStr string) *PGXPoolAdapter {
	return &PGXPoolAdapter{
		connStr: connStr,
	}
}

func (p *PGXPoolAdapter) Connect(ctx context.Context) error {
	var err error
	p.once.Do(func() {
		config, configErr := pgxpool.ParseConfig(p.connStr)
		if configErr != nil {
			err = fmt.Errorf("parsing database connection config: %w", configErr)
			return
		}

		pool, poolErr := pgxpool.NewWithConfig(ctx, config)
		if poolErr != nil {
			err = fmt.Errorf("connecting to database: %w", poolErr)
			return
		}

		// Verify the connection
		if pingErr := pool.Ping(ctx); pingErr != nil {
			err = fmt.Errorf("pinging database: %w", pingErr)
			pool.Close()
			return
		}

		slog.Info("Connected to the database")

		p.pool = pool
	})

	return err
}

func (p *PGXPoolAdapter) Ping(ctx context.Context) error {
	return p.pool.Ping(ctx)
}

func (p *PGXPoolAdapter) Exec(ctx context.Context, sql string, arguments ...any) error {
	sql = stripExtraWhitespaces(sql)

	_, err := p.pool.Exec(ctx, sql, arguments...)
	if err != nil {
		return fmt.Errorf("executing SQL: %w", err)
	}

	slog.Debug("Executed SQL", "sql", sql, "arguments", len(arguments))

	return nil
}

func (p *PGXPoolAdapter) Query(ctx context.Context, sql string, arguments ...any) (pgx.Rows, error) {
	sql = stripExtraWhitespaces(sql)

	rows, err := p.pool.Query(ctx, sql, arguments...)
	if err != nil {
		return nil, fmt.Errorf("executing query: %w", err)
	}

	slog.Debug("Executed query", "sql", sql, "arguments", len(arguments))

	return rows, nil
}

func (p *PGXPoolAdapter) Close() {
	p.pool.Close()

	slog.Info("Database connection closed")
}

var whitespaceRegex = regexp.MustCompile(`\s+`)

func stripExtraWhitespaces(sql string) string {
	return strings.TrimSpace(whitespaceRegex.ReplaceAllString(sql, " "))
}
