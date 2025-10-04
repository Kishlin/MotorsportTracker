package cache

import (
	"context"
	"fmt"
	"os"
	"testing"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/env"
	_func "github.com/kishlin/MotorsportTracker/src/Golang/func"
)

func TestDatabaseCache_GetOnEmptyCache(t *testing.T) {
	namespace, key := "series", "test_key"
	ctx := context.Background()

	cache, cleanDB := setupDatabaseCache(t, ctx)
	defer cleanDB()

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
	namespace, key := "series", "test_key"
	value := []byte("test_value")
	ctx := context.Background()

	cache, cleanDB := setupDatabaseCache(t, ctx,
		withExistingCache(namespace, key, value),
	)
	defer cleanDB()

	actual, hit, err := cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("unexpected error on Search: %v", err)
	}
	if hit == false {
		t.Error("expected hit to be true, got false")
	}
	if string(value) != string(actual) {
		t.Errorf("expected 'test_value', got %s", value)
	}
}

func TestDatabaseCache_SetExisting(t *testing.T) {
	namespace, key := "series", "test_key"
	value := []byte("test_value")
	ctx := context.Background()

	cache, cleanDB := setupDatabaseCache(t, ctx)
	defer cleanDB()

	err := cache.Set(namespace, key, value)
	if err != nil {
		t.Fatalf("unexpected error on first Set: %v", err)
	}

	actual, hit, err := cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("unexpected error on first Get: %v", err)
	}
	if hit == false {
		t.Error("expected hit to be true on first Get, got false")
	}
	if string(value) != string(actual) {
		t.Errorf("expected 'test_value' on first Get, got %s", value)
	}
}

type databaseCacheSetupOpt func(ctx context.Context, db database.Database)

func setupDatabaseCache(t *testing.T, ctx context.Context, opts ...databaseCacheSetupOpt) (cache *DatabaseCache, cleanDB func()) {
	t.Helper()

	env.OverrideAppEnv("tests")
	_func.Must(env.LoadEnv())

	db := database.NewPGXPoolAdapter(os.Getenv("POSTGRES_CLIENT_CACHE_URL"))
	err := db.Connect(context.Background())
	if err != nil {
		t.Fatalf("unable to connect to database: %v", err)
	}

	for _, opt := range opts {
		opt(ctx, db)
	}

	cache = NewDatabaseCache(db)
	cleanDB = func() {
		//goland:noinspection SqlWithoutWhere
		err = db.Exec(ctx, "DELETE FROM series")
		if err != nil {
			t.Fatalf("unable to clean database: %v", err)
		}
		db.Close()
	}

	return cache, cleanDB
}

func withExistingCache(namespace, key string, value []byte) databaseCacheSetupOpt {
	return func(ctx context.Context, db database.Database) {
		query := fmt.Sprintf(
			"INSERT INTO %s (key, value) VALUES ($1, $2)",
			namespace,
		)
		_func.Must(db.Exec(ctx, query, key, value))
	}
}
