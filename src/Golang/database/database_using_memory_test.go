package database

import (
	"context"
	"fmt"
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

func TestQuery_NoArgs(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{
		Rows:    []map[string]interface{}{{"count": 42}},
		Columns: []string{"count"},
	}
	db.SetQueryResponse("SELECT COUNT(*) FROM users", []any{}, response)

	rows, err := db.Query(ctx, "SELECT COUNT(*) FROM users")
	if err != nil {
		t.Fatalf("Query should not return error: %v", err)
	}

	if !rows.Next() {
		t.Fatal("Should have at least one row")
	}

	var count int
	if err := rows.Scan(&count); err != nil {
		t.Fatalf("Scan should not return error: %v", err)
	}

	if count != 42 {
		t.Errorf("Expected count=42, got %d", count)
	}
}

func TestQuery_ReturnsError(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{Error: fmt.Errorf("table does not exist")}
	db.SetQueryResponse("SELECT * FROM nonexistent", []any{}, response)

	rows, err := db.Query(ctx, "SELECT * FROM nonexistent")
	if err == nil {
		t.Error("Query should return error")
	}
	if rows != nil {
		t.Error("Query should return nil rows on error")
	}
}

// Query error tests
func TestQuery_NotConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	if _, err := db.Query(ctx, "SELECT 1"); err == nil {
		t.Error("Query should return error when not connected")
	}
}

func TestQuery_AfterClose(t *testing.T) {
	db, ctx := setupConnectedDB(t)
	db.Close()

	if _, err := db.Query(ctx, "SELECT 1"); err == nil {
		t.Error("Query should return error after close")
	}
}

// Query panic tests
func TestQuery_NoMockConfigured_Panics(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	defer func() {
		if r := recover(); r == nil {
			t.Error("Query should panic when no mock is configured")
		}
	}()

	_, _ = db.Query(ctx, "SELECT * FROM users")
}

func TestQuery_DifferentArgs_Panics(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	// Set up mock for specific args
	response := QueryResponse{Rows: []map[string]interface{}{}, Columns: []string{}}
	db.SetQueryResponse("SELECT * FROM users WHERE id = $1", []any{123}, response)

	defer func() {
		if r := recover(); r == nil {
			t.Error("Query should panic when called with different args")
		}
	}()

	_, _ = db.Query(ctx, "SELECT * FROM users WHERE id = $1", 456)
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

func TestExec_ReturnsError(t *testing.T) {
	db, ctx := setupConnectedDB(t)

	response := QueryResponse{Error: fmt.Errorf("table does not exist")}
	db.SetQueryResponse("INSERT INTO nonexistent (name) VALUES ($1)", []any{"John Doe"}, response)

	if err := db.Exec(ctx, "INSERT INTO nonexistent (name) VALUES ($1)", "John Doe"); err == nil {
		t.Error("Exec should return error")
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
