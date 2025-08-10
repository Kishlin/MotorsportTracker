package database

import (
	"context"
	"errors"
	"fmt"
	"log/slog"
	"strings"
	"sync"
)

// MemoryDatabase is a simple test mock that maps queries to responses
type MemoryDatabase struct {
	mu        sync.RWMutex
	connected bool
	closed    bool

	// Mock responses for specific queries with arguments
	queryResponses map[string]map[string]QueryResponse // query -> argsKey -> response
}

// QueryResponse defines a mock response for a query
type QueryResponse struct {
	Rows    []map[string]interface{}
	Columns []string
	Error   error
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
		case *[]uint8:
			if bytes, ok := value.([]uint8); ok {
				*v = bytes
			} else if str, ok := value.(string); ok {
				*v = []uint8(str)
			} else if value != nil {
				*v = []uint8(fmt.Sprintf("%v", value))
			} else {
				return errors.New("cannot scan nil value into []uint8")
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
		queryResponses: make(map[string]map[string]QueryResponse),
	}
}

// SetQueryResponse sets up a mock response for a specific query with specific arguments
func (m *MemoryDatabase) SetQueryResponse(query string, arguments []any, response QueryResponse) {
	m.mu.Lock()
	defer m.mu.Unlock()

	normalizedQuery := m.normalizeQuery(query)
	argsKey := m.buildArgsKey(arguments)

	if m.queryResponses[normalizedQuery] == nil {
		m.queryResponses[normalizedQuery] = make(map[string]QueryResponse)
	}

	m.queryResponses[normalizedQuery][argsKey] = response
}

// buildArgsKey creates a unique key for the arguments to allow multiple setups with different args
func (m *MemoryDatabase) buildArgsKey(args []any) string {
	if len(args) == 0 {
		return "no_args"
	}

	var keyParts []string
	for _, arg := range args {
		keyParts = append(keyParts, fmt.Sprintf("%v:%T", arg, arg))
	}
	return strings.Join(keyParts, "|")
}

// normalizeQuery normalizes SQL queries to handle whitespace differences
func (m *MemoryDatabase) normalizeQuery(query string) string {
	// Convert to lowercase for case-insensitive matching
	query = strings.ToLower(query)

	// Split by lines and normalize each line
	lines := strings.Split(strings.TrimSpace(query), "\n")
	var normalized []string

	for _, line := range lines {
		// Trim whitespace from each line
		trimmed := strings.TrimSpace(line)
		if trimmed != "" {
			// Replace multiple spaces with single spaces
			normalized = append(normalized, strings.Join(strings.Fields(trimmed), " "))
		}
	}

	// Join with single spaces
	return strings.Join(normalized, " ")
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

	slog.Info("Successfully connected to in-memory database")

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
func (m *MemoryDatabase) Exec(_ context.Context, sql string, arguments ...any) error {
	m.mu.RLock()
	defer m.mu.RUnlock()

	if m.closed {
		return errors.New("database has been closed")
	}

	if !m.connected {
		return errors.New("database is not connected")
	}

	// Use the same normalization as SetQueryResponse
	normalizedQuery := m.normalizeQuery(sql)
	argsKey := m.buildArgsKey(arguments)

	// Try to find an exact match
	if queryMap, exists := m.queryResponses[normalizedQuery]; exists {
		if response, argsExists := queryMap[argsKey]; argsExists {
			slog.Debug("Executed SQL", "sql", sql, "args_key", argsKey)
			return response.Error
		}
	}

	// Panic if no mock response is configured - this is a programming error in tests
	panic(fmt.Sprintf("no mock configured for query: %s with args: %v", sql, arguments))
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

	// Use the same normalization as SetQueryResponse
	normalizedQuery := m.normalizeQuery(sql)
	argsKey := m.buildArgsKey(arguments)

	// Try to find an exact match
	if queryMap, exists := m.queryResponses[normalizedQuery]; exists {
		if response, argsExists := queryMap[argsKey]; argsExists {
			if response.Error != nil {
				return nil, response.Error
			}

			slog.Debug("Executed SQL", "sql", sql, "args_key", argsKey)

			return &MemoryRows{
				rows:    response.Rows,
				columns: response.Columns,
				current: 0,
			}, nil
		}
	}

	// Panic if no mock response is configured - this is a programming error in tests
	panic(fmt.Sprintf("no mock configured for query: %s with args: %v", sql, arguments))
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

	slog.Info("Closing database connection")
}

// isConnected returns whether the database is currently connected
func (m *MemoryDatabase) isConnected() bool {
	m.mu.RLock()
	defer m.mu.RUnlock()

	return m.connected && !m.closed
}
