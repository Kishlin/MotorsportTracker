package database

import (
	"context"
	"fmt"
	"log"
	"strings"
	"sync"
)

// MemoryDatabase is an in-memory implementation of the Database interface
type MemoryDatabase struct {
	mu        sync.RWMutex
	connected bool
	closed    bool

	// Store executed SQL statements for testing purposes
	executedQueries []QueryRecord
}

// QueryRecord stores information about executed SQL queries
type QueryRecord struct {
	SQL       string
	Arguments []any
}

// NewMemoryDatabase creates a new in-memory database instance
func NewMemoryDatabase() *MemoryDatabase {
	return &MemoryDatabase{
		executedQueries: make([]QueryRecord, 0),
	}
}

// Connect establishes the connection (simulated for in-memory)
func (m *MemoryDatabase) Connect(_ context.Context) error {
	m.mu.Lock()
	defer m.mu.Unlock()

	if m.closed {
		return fmt.Errorf("database has been closed")
	}

	if m.connected {
		log.Println("Memory database already connected")
		return nil
	}

	m.connected = true
	log.Println("Successfully connected to in-memory database")

	return nil
}

// Ping verifies the database connection
func (m *MemoryDatabase) Ping(_ context.Context) error {
	m.mu.RLock()
	defer m.mu.RUnlock()

	if m.closed {
		return fmt.Errorf("database has been closed")
	}

	if !m.connected {
		return fmt.Errorf("database is not connected")
	}

	return nil
}

// Exec executes a SQL statement (simulated for in-memory)
// The SQL is stored for testing purposes but not actually executed
func (m *MemoryDatabase) Exec(_ context.Context, sql string, arguments ...any) error {
	m.mu.Lock()
	defer m.mu.Unlock()

	if m.closed {
		return fmt.Errorf("database has been closed")
	}

	if !m.connected {
		return fmt.Errorf("database is not connected")
	}

	// Simulate basic SQL validation
	if strings.TrimSpace(sql) == "" {
		return fmt.Errorf("empty SQL statement")
	}

	// Store the query for testing purposes
	record := QueryRecord{
		SQL:       sql,
		Arguments: make([]any, len(arguments)),
	}
	copy(record.Arguments, arguments)

	m.executedQueries = append(m.executedQueries, record)

	log.Printf("Executed SQL: %s with %d arguments", sql, len(arguments))

	return nil
}

// Close closes the database connection
func (m *MemoryDatabase) Close() {
	m.mu.Lock()
	defer m.mu.Unlock()

	if m.closed {
		return
	}

	m.closed = true
	m.connected = false
	log.Println("Memory database connection closed")
}

// GetExecutedQueries returns all executed queries for testing purposes
func (m *MemoryDatabase) GetExecutedQueries() []QueryRecord {
	m.mu.RLock()
	defer m.mu.RUnlock()

	// Return a copy to prevent external modification
	queries := make([]QueryRecord, len(m.executedQueries))
	copy(queries, m.executedQueries)

	return queries
}

// ClearExecutedQueries clears the stored queries (useful for testing)
func (m *MemoryDatabase) ClearExecutedQueries() {
	m.mu.Lock()
	defer m.mu.Unlock()

	m.executedQueries = m.executedQueries[:0]
}

// IsConnected returns whether the database is currently connected
func (m *MemoryDatabase) IsConnected() bool {
	m.mu.RLock()
	defer m.mu.RUnlock()

	return m.connected && !m.closed
}
