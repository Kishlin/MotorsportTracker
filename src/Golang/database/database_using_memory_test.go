package database

import (
	"context"
	"fmt"
	"testing"
	"time"
)

func TestMemoryDatabase_NewMemoryDatabase(t *testing.T) {
	db := NewMemoryDatabase()
	if db == nil {
		t.Fatal("NewMemoryDatabase should return a non-nil database")
	}
	if db.IsConnected() {
		t.Error("New database should not be connected initially")
	}
	if len(db.GetExecutedQueries()) != 0 {
		t.Error("New database should have no executed queries")
	}
}

func TestMemoryDatabase_Connect_Success(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	err := db.Connect(ctx)
	if err != nil {
		t.Errorf("Connect should not return error: %v", err)
	}
	if !db.IsConnected() {
		t.Error("Database should be connected after Connect")
	}
}

func TestMemoryDatabase_Connect_AlreadyConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first time
	err1 := db.Connect(ctx)
	if err1 != nil {
		t.Errorf("First connect should not return error: %v", err1)
	}

	// Connect second time
	err2 := db.Connect(ctx)
	if err2 != nil {
		t.Errorf("Second connect should not return error: %v", err2)
	}
	if !db.IsConnected() {
		t.Error("Database should still be connected")
	}
}

func TestMemoryDatabase_Connect_AfterClose(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect and then close
	_ = db.Connect(ctx)
	db.Close()

	// Try to connect again
	err := db.Connect(ctx)
	if err == nil {
		t.Error("Connect should return error after database is closed")
	}
	if db.IsConnected() {
		t.Error("Database should not be connected after being closed")
	}
}

func TestMemoryDatabase_Ping_Success(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)

	err := db.Ping(ctx)
	if err != nil {
		t.Errorf("Ping should not return error when connected: %v", err)
	}
}

func TestMemoryDatabase_Ping_NotConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	err := db.Ping(ctx)
	if err == nil {
		t.Error("Ping should return error when not connected")
	}
	expectedError := "database is not connected"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}
}

func TestMemoryDatabase_Ping_AfterClose(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect and then close
	_ = db.Connect(ctx)
	db.Close()

	err := db.Ping(ctx)
	if err == nil {
		t.Error("Ping should return error after database is closed")
	}
	expectedError := "database has been closed"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}
}

func TestMemoryDatabase_Exec_Success(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)

	sql := "INSERT INTO users (name, email) VALUES ($1, $2)"
	args := []any{"John Doe", "john@example.com"}

	err := db.Exec(ctx, sql, args...)
	if err != nil {
		t.Errorf("Exec should not return error: %v", err)
	}

	queries := db.GetExecutedQueries()
	if len(queries) != 1 {
		t.Errorf("Expected 1 executed query, got %d", len(queries))
	}
	if queries[0].SQL != sql {
		t.Errorf("Expected SQL '%s', got '%s'", sql, queries[0].SQL)
	}
	if len(queries[0].Arguments) != len(args) {
		t.Errorf("Expected %d arguments, got %d", len(args), len(queries[0].Arguments))
	}
	for i, arg := range args {
		if queries[0].Arguments[i] != arg {
			t.Errorf("Expected argument %d to be %v, got %v", i, arg, queries[0].Arguments[i])
		}
	}
}

func TestMemoryDatabase_Exec_MultipleQueries(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)

	queries := []string{
		"CREATE TABLE users (id SERIAL PRIMARY KEY, name TEXT)",
		"INSERT INTO users (name) VALUES ('Alice')",
		"INSERT INTO users (name) VALUES ('Bob')",
	}

	for _, sql := range queries {
		err := db.Exec(ctx, sql)
		if err != nil {
			t.Errorf("Exec should not return error for query '%s': %v", sql, err)
		}
	}

	executedQueries := db.GetExecutedQueries()
	if len(executedQueries) != len(queries) {
		t.Errorf("Expected %d executed queries, got %d", len(queries), len(executedQueries))
	}

	for i, expectedSQL := range queries {
		if executedQueries[i].SQL != expectedSQL {
			t.Errorf("Query %d: expected '%s', got '%s'", i, expectedSQL, executedQueries[i].SQL)
		}
	}
}

func TestMemoryDatabase_Exec_EmptySQL(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)

	err := db.Exec(ctx, "")
	if err == nil {
		t.Error("Exec should return error for empty SQL")
	}
	expectedError := "empty SQL statement"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}

	err = db.Exec(ctx, "   ")
	if err == nil {
		t.Error("Exec should return error for whitespace-only SQL")
	}
}

func TestMemoryDatabase_Exec_NotConnected(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	err := db.Exec(ctx, "SELECT 1")
	if err == nil {
		t.Error("Exec should return error when not connected")
	}
	expectedError := "database is not connected"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}
}

func TestMemoryDatabase_Exec_AfterClose(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect and then close
	_ = db.Connect(ctx)
	db.Close()

	err := db.Exec(ctx, "SELECT 1")
	if err == nil {
		t.Error("Exec should return error after database is closed")
	}
	expectedError := "database has been closed"
	if err != nil && err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}
}

func TestMemoryDatabase_Close(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)
	if !db.IsConnected() {
		t.Error("Database should be connected")
	}

	// Close the database
	db.Close()
	if db.IsConnected() {
		t.Error("Database should not be connected after Close")
	}

	db.Close()
	db.Close()

	if db.IsConnected() {
		t.Error("Database should still be closed after multiple Close calls")
	}
}

func TestMemoryDatabase_ClearExecutedQueries(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect and execute some queries
	_ = db.Connect(ctx)
	_ = db.Exec(ctx, "SELECT 1")
	_ = db.Exec(ctx, "SELECT 2")

	if len(db.GetExecutedQueries()) != 2 {
		t.Error("Should have 2 executed queries before clear")
	}

	db.ClearExecutedQueries()
	if len(db.GetExecutedQueries()) != 0 {
		t.Error("Should have 0 executed queries after clear")
	}
}

func TestMemoryDatabase_GetExecutedQueries_IsCopy(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect and execute a query
	_ = db.Connect(ctx)
	_ = db.Exec(ctx, "SELECT 1")

	queries1 := db.GetExecutedQueries()
	queries2 := db.GetExecutedQueries()

	// Modify one slice
	if len(queries1) > 0 {
		queries1[0].SQL = "MODIFIED"
	}

	// The other slice should not be affected
	if len(queries2) > 0 && queries2[0].SQL == "MODIFIED" {
		t.Error("GetExecutedQueries should return independent copies")
	}
}

func TestMemoryDatabase_ConcurrentAccess(t *testing.T) {
	db := NewMemoryDatabase()
	ctx := context.Background()

	// Connect first
	_ = db.Connect(ctx)

	// Test concurrent access
	done := make(chan bool)
	numGoroutines := 10
	queriesPerGoroutine := 5

	for i := 0; i < numGoroutines; i++ {
		go func(id int) {
			for j := 0; j < queriesPerGoroutine; j++ {
				sql := fmt.Sprintf("INSERT INTO test%d VALUES (%d)", id, j)
				_ = db.Exec(ctx, sql)
			}
			done <- true
		}(i)
	}

	// Wait for all goroutines to complete
	for i := 0; i < numGoroutines; i++ {
		select {
		case <-done:
		case <-time.After(5 * time.Second):
			t.Fatal("Concurrent test timed out")
		}
	}

	queries := db.GetExecutedQueries()
	expectedCount := numGoroutines * queriesPerGoroutine
	if len(queries) != expectedCount {
		t.Errorf("Expected %d queries from concurrent access, got %d", expectedCount, len(queries))
	}
}
