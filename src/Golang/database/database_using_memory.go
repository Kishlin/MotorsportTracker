package database

import (
	"context"
	"errors"
	"fmt"
	"log"
	"strings"
	"sync"
)

// MemoryDatabase is an in-memory implementation of the Database interface
// Useful for testing and development environments
type MemoryDatabase struct {
	mu        sync.RWMutex
	connected bool
	closed    bool

	// Store executed SQL statements for testing purposes
	executedQueries []QueryRecord

	// Store data tables for query simulation
	tables map[string][]map[string]interface{}
}

// QueryRecord stores information about executed SQL queries
type QueryRecord struct {
	SQL       string
	Arguments []any
}

// MemoryRows implements the Rows interface for in-memory query results
type MemoryRows struct {
	rows    []map[string]interface{}
	current int
	columns []string
	err     error
}

func (r *MemoryRows) Next() bool {
	if r.current >= len(r.rows) {
		return false
	}
	r.current++
	return r.current <= len(r.rows)
}

func (r *MemoryRows) Scan(dest ...any) error {
	if r.current <= 0 || r.current > len(r.rows) {
		return errors.New("no current row")
	}

	row := r.rows[r.current-1]
	if len(dest) > len(r.columns) {
		return errors.New("too many destination arguments")
	}

	for i, col := range r.columns {
		if i >= len(dest) {
			break
		}

		value := row[col]
		switch v := dest[i].(type) {
		case *string:
			if str, ok := value.(string); ok {
				*v = str
			} else if value != nil {
				*v = fmt.Sprintf("%v", value)
			}
		case *int:
			if num, ok := value.(int); ok {
				*v = num
			}
		case **string:
			if value != nil {
				if str, ok := value.(string); ok {
					*v = &str
				}
			}
		default:
			return fmt.Errorf("unsupported scan type: %T", dest[i])
		}
	}

	return nil
}

func (r *MemoryRows) Close() error {
	return nil
}

func (r *MemoryRows) Err() error {
	return r.err
}

// NewMemoryDatabase creates a new in-memory database instance
func NewMemoryDatabase() *MemoryDatabase {
	return &MemoryDatabase{
		executedQueries: make([]QueryRecord, 0),
		tables:          make(map[string][]map[string]interface{}),
	}
}

// Connect establishes the connection (simulated for in-memory)
func (m *MemoryDatabase) Connect(_ context.Context) error {
	m.mu.Lock()
	defer m.mu.Unlock()

	if m.closed {
		return errors.New("database has been closed")
	}

	if m.connected {
		return errors.New("database is already connected")
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
		return errors.New("database has been closed")
	}

	if !m.connected {
		return errors.New("database is not connected")
	}

	return nil
}

// Exec executes a SQL statement (simulated for in-memory)
// The SQL is stored for testing purposes but not actually executed
func (m *MemoryDatabase) Exec(_ context.Context, sql string, arguments ...any) error {
	m.mu.Lock()
	defer m.mu.Unlock()

	if m.closed {
		return errors.New("database has been closed")
	}

	if !m.connected {
		return errors.New("database is not connected")
	}

	// Simulate basic SQL validation
	if strings.TrimSpace(sql) == "" {
		return errors.New("empty SQL statement")
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

// Query executes a SQL query and returns rows (simulated for in-memory)
func (m *MemoryDatabase) Query(_ context.Context, sql string, arguments ...any) (Rows, error) {
	m.mu.RLock()
	defer m.mu.RUnlock()

	if m.closed {
		return nil, errors.New("database has been closed")
	}

	if !m.connected {
		return nil, errors.New("database is not connected")
	}

	// Simple SQL parsing for SELECT statements
	sql = strings.TrimSpace(strings.ToLower(sql))
	if !strings.HasPrefix(sql, "select") {
		return nil, errors.New("only SELECT queries are supported in memory database")
	}

	// Extract table name (very basic parsing)
	tableName := m.extractTableName(sql)
	if tableName == "" {
		return nil, errors.New("could not determine table name from query")
	}

	// Get data from the table
	tableData, exists := m.tables[tableName]
	if !exists {
		// Return empty result set for non-existent tables
		return &MemoryRows{rows: []map[string]interface{}{}, columns: []string{}}, nil
	}

	// Filter results based on WHERE clause (basic implementation)
	filteredData := m.filterData(tableData, sql, arguments)

	// Determine columns (use first row keys or default columns)
	columns := m.determineColumns(filteredData, tableName)

	log.Printf("Executed query: %s with %d arguments, returned %d rows", sql, len(arguments), len(filteredData))

	return &MemoryRows{
		rows:    filteredData,
		columns: columns,
		current: 0,
	}, nil
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

// AddTestData adds data to a table for testing purposes
func (m *MemoryDatabase) AddTestData(tableName string, data []map[string]interface{}) {
	m.mu.Lock()
	defer m.mu.Unlock()

	m.tables[tableName] = data
}

// GetTestData retrieves data from a table for testing purposes
func (m *MemoryDatabase) GetTestData(tableName string) []map[string]interface{} {
	m.mu.RLock()
	defer m.mu.RUnlock()

	return m.tables[tableName]
}

// extractTableName extracts table name from SQL (basic implementation)
func (m *MemoryDatabase) extractTableName(sql string) string {
	// Look for "FROM tablename" pattern
	parts := strings.Fields(sql)
	for i, part := range parts {
		if part == "from" && i+1 < len(parts) {
			return parts[i+1]
		}
	}
	return ""
}

// filterData filters data based on WHERE conditions (basic implementation)
func (m *MemoryDatabase) filterData(data []map[string]interface{}, sql string, args []any) []map[string]interface{} {
	// For now, return all data - can be enhanced for specific WHERE conditions
	// This is sufficient for the series handler use case
	return data
}

// determineColumns determines column names for the result set
func (m *MemoryDatabase) determineColumns(data []map[string]interface{}, tableName string) []string {
	// Always return columns in a consistent order for known tables
	switch tableName {
	case "series":
		return []string{"name", "uuid", "short_name", "short_code", "category"}
	default:
		// For unknown tables, use the first row's keys if available
		if len(data) > 0 {
			columns := make([]string, 0, len(data[0]))
			for key := range data[0] {
				columns = append(columns, key)
			}
			return columns
		}
		return []string{}
	}
}
