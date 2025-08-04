package database

import (
	"context"
)

// Database is an interface for database operations.
type Database interface {
	Connect(ctx context.Context) error

	Ping(ctx context.Context) error

	Exec(ctx context.Context, sql string, arguments ...any) error

	Close()
}
