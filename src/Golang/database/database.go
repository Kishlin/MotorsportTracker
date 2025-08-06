package database

import (
	"context"
)

// Database is an interface for database operations.
type Database interface {
	Connect(ctx context.Context) error

	Ping(ctx context.Context) error

	Exec(ctx context.Context, sql string, arguments ...any) error

	Query(ctx context.Context, sql string, arguments ...any) (Rows, error)

	Close()
}

// Rows represents the result set from a database query
type Rows interface {
	Next() bool
	Scan(dest ...any) error
	Close() error
	Err() error
}
