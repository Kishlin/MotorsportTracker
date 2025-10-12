package series

import (
	"context"
	"errors"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/database"
)

func TestScrapSeriesHandler_Handle_Success(t *testing.T) {
	handler := setupHandler(t,
		withTwoSeriesConnectorResponse(),
		withEmptyDatabase(),
		expectInsertQuery("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
		expectInsertQuery("Formula 2", "f2-uuid-456", "F2", "F2", "Formula"),
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
		withInvalidConnectorResponse(),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err == nil {
		t.Error("Expected error due to invalid JSON")
	}
}

func TestScrapSeriesHandler_Handle_EmptyResponse(t *testing.T) {
	handler := setupHandler(t,
		withEmptyConnectorResponse(),
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
		withSingleSeriesConnectorResponse(),
		withEmptyDatabase(),
		expectInsertQuery("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_ExistingSeries(t *testing.T) {
	handler := setupHandler(t,
		withSingleSeriesConnectorResponse(),
		withExistingSeries("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
		expectNoInsertQuery(),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeresHandler_Handle_IntelligentBehavior_ExistingSeriesWithDifferentData(t *testing.T) {
	handler := setupHandler(t,
		withSingleSeriesConnectorResponse(),
		withExistingSeries("Formula 1", "f1-uuid-123", "F1", "F1", "DifferentCategory"),
		expectNoInsertQuery(),
	)

	err := handler.Handle(context.Background(), messaging.Message{})
	if err != nil {
		t.Errorf("Expected no error, got: %v", err)
	}
}

func TestScrapSeriesHandler_Handle_IntelligentBehavior_MixedScenario(t *testing.T) {
	handler := setupHandler(t,
		withTwoSeriesConnectorResponse(),
		withExistingSeries("Formula 1", "f1-uuid-123", "F1", "F1", "Formula"),
		expectInsertQuery("Formula 2", "f2-uuid-456", "F2", "F2", "Formula"),
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
	dbMocks       []dbMock
}

type dbMock struct {
	query    string
	args     []any
	response database.QueryResponse
}

func setupHandler(t *testing.T, opts ...setupOption) *ScrapSeriesHandler {
	t.Helper()

	setup := &testSetup{
		connectorData: make(map[string]connector.MockResponse),
		dbMocks:       []dbMock{},
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

	// Apply database mocks directly
	for _, mock := range setup.dbMocks {
		db.SetQueryResponse(mock.query, mock.args, mock.response)
	}

	// Setup connector
	conn := connector.NewInMemoryConnector(setup.connectorData)

	return NewScrapSeriesHandler(db, conn)
}

// MotorsportStatsConnectorUsingClient options
func withTwoSeriesConnectorResponse() setupOption {
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

func withSingleSeriesConnectorResponse() setupOption {
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

func withInvalidConnectorResponse() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`{"invalid": json data}`),
			Err:  nil,
		}
	}
}

func withEmptyConnectorResponse() setupOption {
	return func(s *testSetup) {
		s.connectorData[endpointSeries] = connector.MockResponse{
			Data: []byte(`[]`),
			Err:  nil,
		}
	}
}

// Database verification options (these set expectations for what queries the handler should execute)
func withEmptyDatabase() setupOption {
	return func(s *testSetup) {
		s.dbMocks = append(s.dbMocks, dbMock{
			query: "SELECT name, external_uuid, short_name, short_code, category FROM series",
			args:  []any{},
			response: database.QueryResponse{
				Rows:    []map[string]interface{}{},
				Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
			},
		})
	}
}

func withExistingSeries(name, uuid, shortName, shortCode, category string) setupOption {
	return func(s *testSetup) {
		s.dbMocks = append(s.dbMocks, dbMock{
			query: "SELECT name, external_uuid, short_name, short_code, category FROM series",
			args:  []any{},
			response: database.QueryResponse{
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
			},
		})
	}
}

// nolint:unparam
func expectInsertQuery(name, uuid, shortName, shortCode, category string) setupOption {
	return func(s *testSetup) {
		s.dbMocks = append(s.dbMocks, dbMock{
			query: `
				INSERT INTO series (name, external_uuid, short_name, short_code, category)
				VALUES ($1, $2, $3, $4, $5)`,
			args:     []any{name, uuid, shortName, shortCode, category},
			response: database.QueryResponse{Error: nil},
		})
	}
}

func expectNoInsertQuery() setupOption {
	return func(s *testSetup) {}
}
