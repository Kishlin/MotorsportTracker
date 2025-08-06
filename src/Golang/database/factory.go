package database

import (
	"fmt"
	"strings"
)

// Factory is an interface for creating database instances.
type Factory interface {
	NewDatabase(connStr string) (Database, error)
}

// DatabaseFactory is a factory for creating database instances.
type DatabaseFactory struct{}

// NewDatabaseFactory creates a new instance of DatabaseFactory.
func NewDatabaseFactory() *DatabaseFactory {
	return &DatabaseFactory{}
}

// NewDatabase creates a new database instance based on the connection string.
func (f *DatabaseFactory) NewDatabase(connStr string) (Database, error) {
	if strings.HasPrefix(strings.ToLower(connStr), "memory://") {
		return NewMemoryDatabase(), nil
	}

	if strings.HasPrefix(strings.ToLower(connStr), "postgres://") ||
		strings.HasPrefix(strings.ToLower(connStr), "postgresql://") {
		return NewPGXPoolAdapter(connStr), nil
	}

	return nil, fmt.Errorf("unsupported database connection string: %s", connStr)
}
