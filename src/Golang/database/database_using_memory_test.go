package database

import (
	"context"
	"testing"
)

// Constructor tests
func TestNewMemoryDatabase(t *testing.T) {
	db := NewMemoryDatabase()
	if db == nil {
		t.Fatal("NewMemoryDatabase should return a non-nil database")
	}
	if db.isConnected() {
		t.Error("New database should not be connected initially")
	}
}

// Connection lifecycle tests
func TestConnect_Success(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Connect should not return error: %v", err)
	}
	if !db.isConnected() {
		t.Error("Database should be connected after Connect")
	}
}

func TestConnect_AlreadyConnected(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	// Connect second time should fail
	if err := db.Connect(ctx); err == nil {
		t.Error("Second connect should return error")
	}
}

func TestConnect_AfterClose(t *testing.T) {
	db, ctx := setupConnectedDB(t)
	db.Close()

	if err := db.Connect(ctx); err == nil {
		t.Error("Connect should return error after database is closed")
	}
}

// Ping tests
func TestPing_Success(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	if err := db.Ping(ctx); err != nil {
		t.Errorf("Ping should not return error when connected: %v", err)
	}
}

func TestPing_NotConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	if err := db.Ping(ctx); err == nil {
		t.Error("Ping should return error when not connected")
	}
}

func TestPing_AfterClose(t *testing.T) {
	db, ctx := setupConnectedDB(t)
	db.Close()

	if err := db.Ping(ctx); err == nil {
		t.Error("Ping should return error after database is closed")
	}
}

// Mock setup tests
func TestSetQueryResponse(t *testing.T) {
	db := NewMemoryDatabase()

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"id": 123, "name": "John Doe"}},
		Columns: []string{"id", "name"},
	}

	// Should not panic
	db.SetQueryResponse("SELECT * FROM users WHERE id = $1", []any{123}, response)
}

// Query normalization tests (new functionality!)
func TestNormalizeQuery_Internal(t *testing.T) {
	db := NewMemoryDatabase()

	testCases := []struct {
		name     string
		input    string
		expected string
	}{
		{
			"Multi-line with indentation",
			`SELECT *
			FROM users
			WHERE id = 1`,
			"select * from users where id = 1",
		},
		{
			"Mixed case with extra whitespace",
			`SELECT  ID,  Name
				FROM   Users
				WHERE  ID = $1`,
			"select id, name from users where id = $1",
		},
		{
			"Complex formatting with tabs and empty lines",
			"\t\tSELECT\tid,\tname\n\n\t\t\tFROM users\n\t\t\tWHERE id = $1\n\n",
			"select id, name from users where id = $1",
		},
	}

	for _, tc := range testCases {
		t.Run(tc.name, func(t *testing.T) {
			result := db.normalizeQuery(tc.input)
			if result != tc.expected {
				t.Errorf("Expected '%s', got '%s'", tc.expected, result)
			}
		})
	}
}

// Query success tests
func TestQuery_Success(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"id": 123, "name": "John Doe"}},
		Columns: []string{"id", "name"},
	}
	db.SetQueryResponse("SELECT id, name FROM users WHERE id = $1", []any{123}, response)

	rows, err := db.Query(ctx, "SELECT id, name FROM users WHERE id = $1", 123)
	if err != nil {
		t.Fatalf("Query should not return error: %v", err)
	}
	if rows == nil {
		t.Fatal("Query should return non-nil rows")
	}

	// Test row scanning
	if !rows.Next() {
		t.Fatal("Should have at least one row")
	}

	var id int
	var name string
	if err := rows.Scan(&id, &name); err != nil {
		t.Fatalf("Scan should not return error: %v", err)
	}

	if id != 123 || name != "John Doe" {
		t.Errorf("Expected id=123, name='John Doe', got id=%d, name='%s'", id, name)
	}

	if rows.Next() {
		t.Error("Should not have more rows")
	}
}

// Query whitespace normalization tests (new functionality!)
func TestQuery_WhitespaceNormalization(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"id": 123, "name": "John Doe"}},
		Columns: []string{"id", "name"},
	}

	// Set up mock with single-line query
	db.SetQueryResponse("SELECT id, name FROM users WHERE id = $1", []any{123}, response)

	// Test key formatting variations that should all match
	testCases := []struct {
		name  string
		query string
	}{
		{"Multi-line indented", `SELECT id, name
			FROM users
			WHERE id = $1`},
		{"Mixed case", "select ID, NAME from USERS where ID = $1"},
		{"Extra spaces and tabs", "SELECT  id,  name\t\tFROM\tusers\tWHERE\tid = $1"},
	}

	for _, tc := range testCases {
		t.Run(tc.name, func(t *testing.T) {
			rows, err := db.Query(ctx, tc.query, 123)
			if err != nil {
				t.Errorf("Query should not return error for %s: %v", tc.name, err)
				return
			}

			if !rows.Next() {
				t.Error("Should have at least one row")
				return
			}

			var id int
			var name string
			if err := rows.Scan(&id, &name); err != nil {
				t.Errorf("Scan should not return error: %v", err)
				return
			}

			if id != 123 || name != "John Doe" {
				t.Errorf("Expected id=123, name='John Doe', got id=%d, name='%s'", id, name)
			}
		})
	}
}

func TestQuery_RealWorldExample(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"name": "Formula 1", "external_uuid": "f1-uuid-123"}},
		Columns: []string{"name", "external_uuid"},
	}

	// Set up mock with clean, single-line format (like tests might use)
	db.SetQueryResponse("SELECT name, external_uuid FROM series WHERE category = $1", []any{"Formula"}, response)

	// But the actual handler code uses multi-line formatting with indentation
	handlerStyleQuery := `
		SELECT name, external_uuid
		FROM series
		WHERE category = $1`

	rows, err := db.Query(ctx, handlerStyleQuery, "Formula")
	if err != nil {
		t.Fatalf("Query should not return error despite different formatting: %v", err)
	}

	if !rows.Next() {
		t.Fatal("Should have at least one row")
	}

	var name, uuid string
	if err := rows.Scan(&name, &uuid); err != nil {
		t.Fatalf("Scan should not return error: %v", err)
	}

	if name != "Formula 1" || uuid != "f1-uuid-123" {
		t.Errorf("Expected name='Formula 1', uuid='f1-uuid-123', got name='%s', uuid='%s'", name, uuid)
	}
}

// Exec success tests
func TestExec_Success(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{Error: nil}
	db.SetQueryResponse("INSERT INTO users (name) VALUES ($1)", []any{"John Doe"}, response)

	if err := db.Exec(ctx, "INSERT INTO users (name) VALUES ($1)", "John Doe"); err != nil {
		t.Errorf("Exec should not return error: %v", err)
	}
}

// Exec whitespace normalization tests (new functionality!)
func TestExec_WhitespaceNormalization(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{Error: nil}

	// Set up mock with single-line query
	db.SetQueryResponse("INSERT INTO users (name) VALUES ($1)", []any{"John Doe"}, response)

	// Test key formatting variations that should all match
	testCases := []struct {
		name  string
		query string
	}{
		{"Multi-line", `INSERT INTO users (name)
			VALUES ($1)`},
		{"Mixed case", "insert INTO Users (Name) values ($1)"},
		{"Complex multi-line with leading whitespace", `
			INSERT INTO users (name)
			VALUES ($1)`},
	}

	for _, tc := range testCases {
		t.Run(tc.name, func(t *testing.T) {
			if err := db.Exec(ctx, tc.query, "John Doe"); err != nil {
				t.Errorf("Exec should not return error for %s: %v", tc.name, err)
			}
		})
	}
}

// Exec error tests
func TestExec_NotConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	if err := db.Exec(ctx, "INSERT INTO users (name) VALUES ('test')"); err == nil {
		t.Error("Exec should return error when not connected")
	}
}

func TestExec_AfterClose(t *testing.T) {
	db, ctx := setupConnectedDB(t)
	db.Close()

	if err := db.Exec(ctx, "INSERT INTO users (name) VALUES ('test')"); err == nil {
		t.Error("Exec should return error after close")
	}
}

// Close tests
func TestClose_Success(t *testing.T) {
	db, _ := setupConnectedDB(t)

	if !db.isConnected() {
		t.Error("Database should be connected before close")
	}

	db.Close()

	if db.isConnected() {
		t.Error("Database should not be connected after close")
	}
}

func TestClose_Multiple(t *testing.T) {
	db, _ := setupConnectedDB(t)

	// Should not panic on multiple closes
	db.Close()
	db.Close()
	db.Close()

	if db.isConnected() {
		t.Error("Database should not be connected after multiple closes")
	}
}

// Utility tests
func TestBuildArgsKey(t *testing.T) {
	db := NewMemoryDatabase()

	tests := []struct {
		name     string
		args     []any
		expected string
	}{
		{"no args", []any{}, "no_args"},
		{"with args", []any{"test", 123, true}, "test:string|123:int|true:bool"},
	}

	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			key := db.buildArgsKey(tt.args)
			if key != tt.expected {
				t.Errorf("Expected '%s', got '%s'", tt.expected, key)
			}
		})
	}

	// Test consistency
	key1 := db.buildArgsKey([]any{"test", 123, true})
	key2 := db.buildArgsKey([]any{"test", 123, true})
	if key1 != key2 {
		t.Error("Same args should produce same key")
	}

	// Test uniqueness
	key3 := db.buildArgsKey([]any{"different", 456})
	if key1 == key3 {
		t.Error("Different args should produce different keys")
	}
}

// Helper functions
func setupConnectedDB(t *testing.T) (*MemoryDatabase, context.Context) {
	t.Helper()
	db := NewMemoryDatabase()
	ctx := context.Background()
	if err := db.Connect(ctx); err != nil {
		t.Fatalf("Failed to connect database: %v", err)
	}
	return db, ctx
}
