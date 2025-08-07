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

	// Verify the SQL statements (now using simple INSERT without ON CONFLICT)
	expectedSQL := `
				INSERT INTO series (name, external_uuid, short_name, short_code, category)
				VALUES ($1, $2, $3, $4, $5)`

	for i, query := range queries {
		if query.SQL != expectedSQL {
			t.Errorf("Query %d: Expected SQL '%s', got '%s'", i, expectedSQL, query.SQL)
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
		t.Errorf("Expected first series ExternalUUID 'f1-uuid-123', got '%v'", firstQuery.Arguments[1])
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
		t.Error("Expected error due to connector failure")
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
		t.Error("Expected error due to invalid JSON")
	}

	// Verify no database operations occurred
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected no database queries when JSON is invalid, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_Handle_DatabaseError(t *testing.T) {
	// Setup in-memory database that's not connected (will cause query to fail first)
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
		t.Error("Expected error due to database failure")
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
		t.Error("Expected error for empty array due to schema validation")
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

func TestScrapSeriesHandler_Handle_IntelligentBehavior_NewSeries(t *testing.T) {
	// Setup in-memory database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	defer db.Close()

	// Setup connector with new series data
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

	// Execute the handler (no existing data)
	err = handler.Handle(ctx, messaging.Message{})

	// Verify no error occurred
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}

	// Verify only INSERT query was executed (no existing series)
	queries := db.GetExecutedQueries()
	if len(queries) != 1 {
		t.Errorf("Expected 1 database query for new series, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_ExistingSeries(t *testing.T) {
	// Setup in-memory database with existing data
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	defer db.Close()

	// Add existing series data
	existingData := []map[string]interface{}{
		{
			"name":          "Formula 1",
			"external_uuid": "f1-uuid-123",
			"short_name":    "F1",
			"short_code":    "F1",
			"category":      "Formula",
		},
	}
	db.AddTestData("series", existingData)

	// Setup connector with same series data (no changes)
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
	err = handler.Handle(ctx, messaging.Message{})

	// Verify no error occurred
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}

	// Verify no INSERT queries were executed (series already exists)
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected 0 database INSERT queries for existing series, got %d", len(queries))
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_MixedScenario(t *testing.T) {
	// Setup in-memory database with some existing data
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	defer db.Close()

	// Add one existing series
	existingData := []map[string]interface{}{
		{
			"name":          "Formula 1",
			"external_uuid": "f1-uuid-123",
			"short_name":    "F1",
			"short_code":    "F1",
			"category":      "Formula",
		},
	}
	db.AddTestData("series", existingData)

	// Setup connector with one existing and one new series
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

	// Verify only 1 INSERT query was executed (only for the new series)
	queries := db.GetExecutedQueries()
	if len(queries) != 1 {
		t.Errorf("Expected 1 database INSERT query for new series only, got %d", len(queries))
	}

	// Verify the INSERT was for the new series (Formula 2)
	if len(queries) > 0 {
		insertQuery := queries[0]
		if len(insertQuery.Arguments) >= 2 && insertQuery.Arguments[1] != "f2-uuid-456" {
			t.Errorf("Expected INSERT for F2 ExternalUUID, got %v", insertQuery.Arguments[1])
		}
	}
}

func TestScrapSeriesHandler_Handle_DataDifferences_Warning(t *testing.T) {
	// Setup in-memory database with existing data that differs
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	err := db.Connect(ctx)
	if err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	defer db.Close()

	// Add existing series data with different values
	existingData := []map[string]interface{}{
		{
			"name":          "Formula One", // Different name
			"external_uuid": "f1-uuid-123",
			"short_name":    "F1",
			"short_code":    "F1",
			"category":      "Single Seater", // Different category
		},
	}
	db.AddTestData("series", existingData)

	// Setup connector with updated series data
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
	err = handler.Handle(ctx, messaging.Message{})

	// Verify no error occurred
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}

	// Verify no INSERT queries were executed (series exists, but data differs)
	queries := db.GetExecutedQueries()
	if len(queries) != 0 {
		t.Errorf("Expected 0 database INSERT queries when data differs, got %d", len(queries))
	}

	// Note: We can't directly test the log output in unit tests,
	// but the handler should log warnings about the differences
}

func TestScrapSeriesHandler_SeriesAreEqual(t *testing.T) {
	handler := &ScrapSeriesHandler{}

	// Test identical data
	series1 := Series{
		Name:         "Formula 1",
		ExternalUUID: "f1-uuid-123",
		ShortName:    "F1",
		ShortCode:    "F1",
		Category:     "Formula",
	}
	series2 := series1

	if handler.seriesAreEqual(series1, series2) {
		t.Error("Identical series data should not show differences")
	}

	// Test different name
	series2.Name = "Formula One"
	if !handler.seriesAreEqual(series1, series2) {
		t.Error("Different names should show differences")
	}

	// Test different category
	series2.Name = series1.Name // Reset name
	series2.Category = "Single Seater"
	if !handler.seriesAreEqual(series1, series2) {
		t.Error("Different categories should show differences")
	}
}
