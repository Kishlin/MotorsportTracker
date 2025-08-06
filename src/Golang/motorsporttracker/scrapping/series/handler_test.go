package series

import (
	"context"
	"errors"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
)

func TestScrapSeriesHandler_Handle_Success(t *testing.T) {
	// Setup in-memory database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	defer db.Close()

	// Setup in-memory connector with valid series data
	validSeriesData := `[
		{
			"name": "Formula 1",
			"uuid": "f1-uuid-123",
			"shortName": "F1",
			"shortCode": "F1",
			"category": "Formula"
		},
		{
			"name": "Formula 2",
			"uuid": "f2-uuid-456",
			"shortName": "F2",
			"shortCode": "F2",
			"category": "Formula"
		}
	]`

	mockData := map[string]connector.MockResponse{
		endpointSeries: {
			Data: []byte(validSeriesData),
			Err:  nil,
		},
	}
	conn := connector.NewInMemoryConnector(mockData)

	// Create handler
	handler := NewScrapSeriesHandler(db, conn)

	// Execute the handler
	err = handler.Handle(ctx, messaging.Message{})

	// Verify no error occurred
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}

	// Verify database operations
	queries := db.GetExecutedQueries()
	if len(queries) != 2 {
		t.Errorf("Expected 2 database queries (one per series), got %d", len(queries))
	}

	// Verify the SQL statements
	for i, query := range queries {
		expectedSQL := `
			INSERT INTO series (name, uuid, short_name, short_code, category)
			VALUES ($1, $2, $3, $4, $5)
			ON CONFLICT (uuid) DO NOTHING`
		if query.SQL != expectedSQL {
			t.Errorf("Query %d: Expected SQL to contain INSERT INTO series, got: %s", i, query.SQL)
		}
		if len(query.Arguments) != 5 {
			t.Errorf("Query %d: Expected 5 arguments, got %d", i, len(query.Arguments))
		}
	}

	// Verify specific series data was inserted
	firstQuery := queries[0]
	if firstQuery.Arguments[0] != "Formula 1" {
		t.Errorf("Expected first series name 'Formula 1', got '%v'", firstQuery.Arguments[0])
	}
	if firstQuery.Arguments[1] != "f1-uuid-123" {
		t.Errorf("Expected first series UUID 'f1-uuid-123', got '%v'", firstQuery.Arguments[1])
	}
}

func TestScrapSeriesHandler_Handle_ConnectorError(t *testing.T) {
	// Setup in-memory database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	_ = db.Connect(ctx)
	defer db.Close()

	// Setup connector to return an error
	mockData := map[string]connector.MockResponse{
		endpointSeries: {
			Data: nil,
			Err:  errors.New("network timeout"),
		},
	}
	conn := connector.NewInMemoryConnector(mockData)

	// Create handler
	handler := NewScrapSeriesHandler(db, conn)

	// Execute the handler
	err := handler.Handle(ctx, messaging.Message{})

	// Verify error occurred
	if err == nil {
		t.Error("Expected error due to connector failure, got nil")
	}

	expectedError := "fetching series data: network timeout"
	if err.Error() != expectedError {
		t.Errorf("Expected error '%s', got '%s'", expectedError, err.Error())
	}

	// Verify no database operations occurred
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected no database queries when connector fails, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_Handle_InvalidJSON(t *testing.T) {
	// Setup in-memory database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	_ = db.Connect(ctx)
	defer db.Close()

	// Setup connector with invalid JSON
	invalidJSON := `{"invalid": json data}`
	mockData := map[string]connector.MockResponse{
		endpointSeries: {
			Data: []byte(invalidJSON),
			Err:  nil,
		},
	}
	conn := connector.NewInMemoryConnector(mockData)

	// Create handler
	handler := NewScrapSeriesHandler(db, conn)

	// Execute the handler
	err := handler.Handle(ctx, messaging.Message{})

	// Verify error occurred
	if err == nil {
		t.Error("Expected error due to invalid JSON, got nil")
	}

	// Should fail during validation (not unmarshalling as originally expected)
	if !contains(err.Error(), "validating series data") {
		t.Errorf("Expected validation error, got: %s", err.Error())
	}

	// Verify no database operations occurred
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected no database queries when JSON is invalid, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_Handle_DatabaseError(t *testing.T) {
	// Setup in-memory database that's not connected (will cause exec to fail)
	db := database.NewMemoryDatabase()
	// Note: deliberately not calling Connect() to simulate database error

	// Setup connector with valid data
	validSeriesData := `[
		{
			"name": "Formula 1",
			"uuid": "f1-uuid-123",
			"shortName": "F1",
			"shortCode": "F1",
			"category": "Formula"
		}
	]`

	mockData := map[string]connector.MockResponse{
		endpointSeries: {
			Data: []byte(validSeriesData),
			Err:  nil,
		},
	}
	conn := connector.NewInMemoryConnector(mockData)

	// Create handler
	handler := NewScrapSeriesHandler(db, conn)

	// Execute the handler
	ctx := context.Background()
	err := handler.Handle(ctx, messaging.Message{})

	// Verify error occurred
	if err == nil {
		t.Error("Expected error due to database failure, got nil")
	}

	if !contains(err.Error(), "inserting series into database") {
		t.Errorf("Expected database insertion error, got: %s", err.Error())
	}
}

func TestScrapSeriesHandler_Handle_EmptyResponse(t *testing.T) {
	// Setup in-memory database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	_ = db.Connect(ctx)
	defer db.Close()

	// Setup connector with empty array
	emptyData := `[]`
	mockData := map[string]connector.MockResponse{
		endpointSeries: {
			Data: []byte(emptyData),
			Err:  nil,
		},
	}
	conn := connector.NewInMemoryConnector(mockData)

	// Create handler
	handler := NewScrapSeriesHandler(db, conn)

	// Execute the handler
	err := handler.Handle(ctx, messaging.Message{})

	// Verify error occurred (schema requires minimum 1 item)
	if err == nil {
		t.Error("Expected error for empty array due to schema validation, got nil")
	}

	if !contains(err.Error(), "validating series data") {
		t.Errorf("Expected validation error for empty array, got: %s", err.Error())
	}

	// Verify no database operations occurred
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected no database queries for invalid empty response, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_NewScrapSeriesHandler(t *testing.T) {
	db := database.NewMemoryDatabase()
	conn := connector.NewInMemoryConnector(map[string]connector.MockResponse{})

	handler := NewScrapSeriesHandler(db, conn)

	if handler == nil {
		t.Fatal("NewScrapSeriesHandler should return non-nil handler")
	}
	if handler.db != db {
		t.Error("Handler should have correct database reference")
	}
	if handler.connector != conn {
		t.Error("Handler should have correct connector reference")
	}
}

// Helper function to check if a string contains a substring
func contains(s, substr string) bool {
	return len(s) >= len(substr) &&
		(s == substr ||
			s[:len(substr)] == substr ||
			s[len(s)-len(substr):] == substr ||
			findSubstring(s, substr))
}

func findSubstring(s, substr string) bool {
	for i := 0; i <= len(s)-len(substr); i++ {
		if s[i:i+len(substr)] == substr {
			return true
		}
	}
	return false
}
