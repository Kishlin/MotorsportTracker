package series

import (
	"context"
	"errors"
	"strings"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
)

func TestScrapSeriesHandler_Handle_Success(t *testing.T) {
	handler := setupHandler(t,
		withValidSeriesData(),
		withEmptyDatabase(),
		withInsertMocks("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
		withInsertMocks("Formula 2", "f2-uuid-456", "F2", "F2", "Formula"),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeriesHandler_Handle_ConnectorError(t *testing.T) {
	handler := setupHandler(t,
		withConnectorError(errors.New("network timeout")),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err == nil {
		t.Error("Expected error due to connector failure")
	}
}

func TestScrapSeriesHandler_Handle_InvalidJSON(t *testing.T) {
	handler := setupHandler(t,
		withInvalidJSON(),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err == nil {
		t.Error("Expected error due to invalid JSON")
	}
}

func TestScrapSeriesHandler_Handle_EmptyResponse(t *testing.T) {
	handler := setupHandler(t,
		withEmptyResponse(),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err == nil {
		t.Error("Expected error for empty array due to schema validation")
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
	handler := setupHandler(t,
		withSingleSeriesData(),
		withEmptyDatabase(),
		withInsertMocks("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_ExistingSeries(t *testing.T) {
	handler := setupHandler(t,
		withSingleSeriesData(),
		withExistingSeries("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_MixedScenario(t *testing.T) {
	handler := setupHandler(t,
		withValidSeriesData(),
		withExistingSeries("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
		withInsertMocks("Formula 2", "f2-uuid-456", "F2", "F2", "Formula"),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

// Setup types and functions
type setupOption func(*testSetup)

type testSetup struct {
	connectorData map[string]connector.MockResponse
	dbResponses   map[string]map[string]database.QueryResponse
}

func setupHandler(t *testing.T, opts ...setupOption) *ScrapSeriesHandler {
	t.Helper()

	setup := &testSetup{
		connectorData: make(map[string]connector.MockResponse),
		dbResponses:   make(map[string]map[string]database.QueryResponse),
	}

	for _, opt := range opts {
		opt(setup)
	}

	// Setup database
	db := database.NewMemoryDatabase()
	ctx := context.Background()
	if err := db.Connect(ctx); err != nil {
		t.Fatalf("Failed to connect to memory database: %v", err)
	}
	t.Cleanup(func() { db.Close() })

	// Apply database mocks
	for query, argMap := range setup.dbResponses {
		for argsKey, response := range argMap {
			var args []any
			if argsKey == "no_args" {
				args = []any{}
			} else {
				// This is simplified - in practice you might want more sophisticated arg parsing
				args = parseArgsFromKey(argsKey)
			}
			db.SetQueryResponse(query, args, response)
		}
	}

	// Setup connector
	conn := connector.NewInMemoryConnector(setup.connectorData)

	return NewScrapSeriesHandler(db, conn)
}

// Connector options
func withValidSeriesData() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`[
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
			]`),
			Err: nil,
		}
	}
}

func withSingleSeriesData() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`[
				{
					"name": "Formula 1",
					"uuid": "f1-uuid-123",
					"shortName": "F1",
					"shortCode": "F1",
					"category": "Formula"
				}
			]`),
			Err: nil,
		}
	}
}

func withConnectorError(err error) setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: nil,
			Err:  err,
		}
	}
}

func withInvalidJSON() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`{"invalid": json data}`),
			Err:  nil,
		}
	}
}

func withEmptyResponse() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`[]`),
			Err:  nil,
		}
	}
}

// Database options
func withEmptyDatabase() setupOption {
	return func(s *testSetup) {
		query := "SELECT name, external_uuid, short_name, short_code, category FROM series"
		if s.dbResponses[query] == nil {
			s.dbResponses[query] = make(map[string]database.QueryResponse)
		}
		s.dbResponses[query]["no_args"] = database.QueryResponse{
			Rows:    []map[string]interface{}{},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		}
	}
}

func withExistingSeries(name, uuid, shortName, shortCode, category string) setupOption {
	return func(s *testSetup) {
		query := "SELECT name, external_uuid, short_name, short_code, category FROM series"
		if s.dbResponses[query] == nil {
			s.dbResponses[query] = make(map[string]database.QueryResponse)
		}
		s.dbResponses[query]["no_args"] = database.QueryResponse{
			Rows: []map[string]interface{}{
				{
					"name":          name,
					"external_uuid": uuid,
					"short_name":    shortName,
					"short_code":    shortCode,
					"category":      category,
				},
			},
			Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
		}
	}
}

//nolint:unparam
func withInsertMocks(name, uuid, shortName, shortCode, category string) setupOption {
	return func(s *testSetup) {
		query := `
			INSERT INTO series (name, external_uuid, short_name, short_code, category)
			VALUES ($1, $2, $3, $4, $5)`

		if s.dbResponses[query] == nil {
			s.dbResponses[query] = make(map[string]database.QueryResponse)
		}
		argsKey := buildInsertArgsKey(name, uuid, shortName, shortCode, category)
		s.dbResponses[query][argsKey] = database.QueryResponse{Error: nil}
	}
}

// Helper functions - simplified now that database handles normalization
func parseArgsFromKey(argsKey string) []any {
	if argsKey == "no_args" {
		return []any{}
	}
	// For insert queries, parse the pipe-separated values
	parts := strings.Split(argsKey, "|")
	args := make([]any, len(parts))
	for i, part := range parts {
		args[i] = part
	}
	return args
}

func buildInsertArgsKey(name, uuid, shortName, shortCode, category string) string {
	return name + "|" + uuid + "|" + shortName + "|" + shortCode + "|" + category
}
