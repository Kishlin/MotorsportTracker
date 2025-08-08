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

	// Set up mock for the existing series query (returns empty - no existing series)
	db.SetQueryResponse(
		"SELECT name, external_uuid, short_name, short_code, category FROM series",
		[]any{},
		database.QueryResponse{
			Rows:    []map[string]interface{}{},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		},
	)

	// Set up mock for the INSERT query
	db.SetQueryResponse(
		"INSERT INTO series (name, external_uuid, short_name, short_code, category)\n\t\t\t\tVALUES ($1, $2, $3, $4, $5)",
		[]any{"Formula 1", "f1-uuid-123", "F1", "F1", "Formula"},
		database.QueryResponse{Error: nil},
	)
	db.SetQueryResponse(
		"INSERT INTO series (name, external_uuid, short_name, short_code, category)\n\t\t\t\tVALUES ($1, $2, $3, $4, $5)",
		[]any{"Formula 2", "f2-uuid-456", "F2", "F2", "Formula"},
		database.QueryResponse{Error: nil},
	)

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
	// No need to verify database queries - connector failure happens before DB calls
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
	// No need to verify database queries - JSON validation happens before DB calls
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
	// No need to verify database queries - schema validation happens before DB calls
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

	// Set up mock for the existing series query (returns empty - no existing series)
	db.SetQueryResponse(
		"SELECT name, external_uuid, short_name, short_code, category FROM series",
		[]any{},
		database.QueryResponse{
			Rows:    []map[string]interface{}{},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		},
	)

	// Set up mock for the INSERT query
	db.SetQueryResponse(
		"INSERT INTO series (name, external_uuid, short_name, short_code, category)\n\t\t\t\tVALUES ($1, $2, $3, $4, $5)",
		[]any{"Formula 1", "f1-uuid-123", "F1", "F1", "Formula"},
		database.QueryResponse{Error: nil},
	)

	// Setup in-memory connector with valid series data
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

	// No need to verify database queries - using strict mocks
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

	// Set up mock response for existing series query
	db.SetQueryResponse(
		"SELECT name, external_uuid, short_name, short_code, category FROM series",
		[]any{},
		database.QueryResponse{
			Rows: []map[string]interface{}{
				{
					"name":          "Formula 1",
					"external_uuid": "f1-uuid-123",
					"short_name":    "F1",
					"short_code":    "F1",
					"category":      "Formula",
				},
			},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		},
	)

	// Setup in-memory connector with same series data (no changes)
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

	// Since the series already exists, no INSERT should be called
	// The strict mock will error if any unexpected queries are made
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

	// Set up mock response for existing series query (Formula 1 exists)
	db.SetQueryResponse(
		"SELECT name, external_uuid, short_name, short_code, category FROM series",
		[]any{},
		database.QueryResponse{
			Rows: []map[string]interface{}{
				{
					"name":          "Formula 1",
					"external_uuid": "f1-uuid-123",
					"short_name":    "F1",
					"short_code":    "F1",
					"category":      "Formula",
				},
			},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		},
	)

	// Set up mock for INSERT of the new series (Formula 2)
	db.SetQueryResponse(
		"INSERT INTO series (name, external_uuid, short_name, short_code, category)\n\t\t\t\tVALUES ($1, $2, $3, $4, $5)",
		[]any{"Formula 2", "f2-uuid-456", "F2", "F2", "Formula"},
		database.QueryResponse{Error: nil},
	)

	// Setup in-memory connector with one existing and one new series
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
	// No need to verify query details - using strict mocks
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

	// Set up mock response for existing series query (with different data)
	db.SetQueryResponse(
		"SELECT name, external_uuid, short_name, short_code, category FROM series",
		[]any{},
		database.QueryResponse{
			Rows: []map[string]interface{}{
				{
					"name":          "Formula One", // Different name
					"external_uuid": "f1-uuid-123",
					"short_name":    "F1",
					"short_code":    "F1",
					"category":      "Single Seater", // Different category
				},
			},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		},
	)

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

	// Since the series exists but data differs, no INSERT should be called
	// The handler should only log warnings
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
