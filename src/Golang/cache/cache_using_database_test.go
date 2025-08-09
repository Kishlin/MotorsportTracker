package cache

import (
	"context"
	"fmt"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

func TestDatabaseCache_GetOnEmptyCache(t *testing.T) {
	namespace, key := "test_namespace", "test_key"

	cache := setupDatabaseCache(
		withEmptyCache(namespace, key),
	)

	actual, hit, err := cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("unexpected error: %v", err)
	}
	if hit {
		t.Error("expected hit to be false, got true")
	}
	if actual != nil {
		t.Errorf("expected nil value, got %v", actual)
	}
}

func TestDatabaseCache_GetOnExistingCache(t *testing.T) {
	namespace, key := "test_namespace", "test_key"
	value := []byte("test_value")

	cache := setupDatabaseCache(
		withExistingCache(namespace, key, value),
	)

	actual, hit, err := cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("unexpected error on Get: %v", err)
	}
	if !hit {
		t.Error("expected hit to be true, got false")
	}
	if string(value) != string(actual) {
		t.Errorf("expected 'test_value', got %s", value)
	}
}

func TestDatabaseCache_SetExisting(t *testing.T) {
	namespace, key := "test_namespace", "test_key"
	value := []byte("test_value")

	cache := setupDatabaseCache(
		expectInsertionQuery(namespace, key, value),
	)

	err := cache.Set(namespace, key, value)
	if err != nil {
		t.Fatalf("unexpected error on first Set: %v", err)
	}
}

type databaseCacheSetupOpt func(database2 *database.MemoryDatabase)

func setupDatabaseCache(opts ...databaseCacheSetupOpt) *DatabaseCache {
	db := database.NewMemoryDatabase()
	err := db.Connect(context.Background())
	if err != nil {
		panic(err)
	}

	for _, opt := range opts {
		opt(db)
	}

	return NewDatabaseCache(db)
}

func withEmptyCache(namespace, key string) databaseCacheSetupOpt {
	return func(db *database.MemoryDatabase) {
		db.SetQueryResponse(
			fmt.Sprintf("SELECT value FROM %s WHERE key = $1 LIMIT 1", namespace),
			[]any{key},
			database.QueryResponse{
				Rows:    make([]map[string]interface{}, 0),
				Columns: make([]string, 0),
				Error:   nil,
			},
		)
	}
}

func withExistingCache(namespace, key string, value []byte) databaseCacheSetupOpt {
	return func(db *database.MemoryDatabase) {
		db.SetQueryResponse(
			fmt.Sprintf("SELECT value FROM %s WHERE key = $1 LIMIT 1", namespace),
			[]any{key},
			database.QueryResponse{
				Rows: []map[string]interface{}{
					{"value": value},
				},
				Columns: []string{"value"},
				Error:   nil,
			},
		)
	}
}

func expectInsertionQuery(namespace, key string, value []byte) databaseCacheSetupOpt {
	return func(db *database.MemoryDatabase) {
		db.SetQueryResponse(
			fmt.Sprintf("INSERT INTO %s (key, value) VALUES ($1, $2) ON CONFLICT(key) DO UPDATE SET value = $3", namespace),
			[]any{key, value, value},
			database.QueryResponse{},
		)
	}
}
