package series

import (
	"context"
	"errors"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

func TestSeries_Repository_NoData(t *testing.T) {
	t.Parallel()

	repo := setupRepositoryTest(t, withNoSeriesInDatabase())

	series, err := repo.FindAll(context.Background())
	if err != nil {
		t.Fatalf("expected no error, got %v", err)
	}

	if len(series) != 0 {
		t.Fatalf("expected no series, got %d", len(series))
	}
}

func TestSeries_Repository_WithSeries(t *testing.T) {
	t.Parallel()

	repo := setupRepositoryTest(t, withTwoSeriesInDatabase())

	series, err := repo.FindAll(context.Background())
	if err != nil {
		t.Fatalf("expected no error, got %v", err)
	}

	if len(series) != 2 {
		t.Fatalf("expected 2 series, got %d", len(series))
	}
}

func TestSeries_Repository_WithDatabaseError(t *testing.T) {
	t.Parallel()

	repo := setupRepositoryTest(t, withDatabaseError())

	_, err := repo.FindAll(context.Background())
	if err == nil {
		t.Fatal("expected error, got nil")
	}
}

type RepositoryTestSetupOpt func(t *testing.T, db *database.MemoryDatabase)

func setupRepositoryTest(t *testing.T, opts ...RepositoryTestSetupOpt) *FindSeriesRepository {
	t.Helper()

	db := database.NewMemoryDatabase()
	err := db.Connect(context.Background())
	if err != nil {
		t.Fatalf("failed to connect to memory database: %v", err)
	}

	for _, opt := range opts {
		opt(t, db)
	}

	repo := NewFindSeriesRepository(db)
	if repo == nil {
		t.Fatal("expected non-nil repository, got nil")
	}

	return repo
}

func withNoSeriesInDatabase() RepositoryTestSetupOpt {
	return func(t *testing.T, db *database.MemoryDatabase) {
		t.Helper()

		db.SetQueryResponse(
			"SELECT name, external_uuid, short_name, short_code, category FROM series",
			make([]interface{}, 0),
			database.QueryResponse{
				Rows:    make([]map[string]interface{}, 0),
				Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
				Error:   nil,
			},
		)
	}
}

func withTwoSeriesInDatabase() RepositoryTestSetupOpt {
	return func(t *testing.T, db *database.MemoryDatabase) {
		t.Helper()

		db.SetQueryResponse(
			"SELECT name, external_uuid, short_name, short_code, category FROM series",
			make([]interface{}, 0),
			database.QueryResponse{
				Rows: []map[string]interface{}{
					{
						"name":          "Formula 1",
						"external_uuid": "f1-uuid-123",
						"short_name":    "F1",
						"short_code":    "F1",
						"category":      "Open Wheel",
					},
					{
						"name":          "Formula E",
						"external_uuid": "fe-uuid-456",
						"short_name":    "FE",
						"short_code":    "FE",
						"category":      "Open Wheel",
					},
				},
				Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
				Error:   nil,
			},
		)
	}
}

func withDatabaseError() RepositoryTestSetupOpt {
	return func(t *testing.T, db *database.MemoryDatabase) {
		t.Helper()

		db.SetQueryResponse(
			"SELECT name, external_uuid, short_name, short_code, category FROM series",
			make([]interface{}, 0),
			database.QueryResponse{
				Rows:    make([]map[string]interface{}, 0),
				Columns: []string{"name", "external_uuid", "short_name", "short_code", "category"},
				Error:   errors.New("database error"),
			},
		)
	}
}
