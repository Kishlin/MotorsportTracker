package database

import (
	"context"
	"errors"
	"testing"
)

// --- Tests ---
func TestPostgresDB_CloseMultipleTimesAndUsageAfterClose(t *testing.T) {
	pool := &mockPool{}
	factory := &mockFactory{pool: pool}

	db := GetInstance(factory)
	if err := db.Connect(context.Background(), "mock-conn-str"); err != nil {
		t.Fatalf("Connect failed: %v", err)
	}

	db.Close()
	db.Close()

	if pool.closeCount != 1 {
		t.Errorf("Expected Close to be called once on the pool, got %d", pool.closeCount)
	}

	// After closing, Ping should fail
	if err := db.Ping(context.Background()); err == nil {
		t.Error("Expected Ping to fail after pool is closed, got nil error")
	}

	// Try to reconnect after close (should not reopen)
	if err := db.Connect(context.Background(), "mock-conn-str"); err != nil {
		t.Errorf("Reconnect after close should be a no-op, got error: %v", err)
	}
	if pool.closeCount != 1 {
		t.Errorf("Reconnect after close should not close again, got closeCount=%d", pool.closeCount)
	}
}

func TestPostgresDB_ConnectErrorHandling(t *testing.T) {
	factory := &mockFactory{parseConfigErr: errors.New("parse error")}
	db := GetInstance(factory)
	if err := db.Connect(context.Background(), "bad-conn-str"); err == nil {
		t.Error("Expected error on bad config, got nil")
	}

	factory = &mockFactory{newWithConfigErr: errors.New("new pool error")}
	db = GetInstance(factory)
	if err := db.Connect(context.Background(), "mock-conn-str"); err == nil {
		t.Error("Expected error on new pool, got nil")
	}

	factory = &mockFactory{pool: &mockPool{pingErr: errors.New("ping error")}}
	db = GetInstance(factory)
	if err := db.Connect(context.Background(), "mock-conn-str"); err == nil {
		t.Error("Expected error on ping, got nil")
	}
}

func TestPostgresDB_MultipleDatabasesIsolation(t *testing.T) {
	pool1 := &mockPool{}
	factory1 := &mockFactory{pool: pool1}
	pool2 := &mockPool{}
	factory2 := &mockFactory{pool: pool2}

	db1 := GetInstance(factory1)
	db2 := GetInstance(factory2)

	if err := db1.Connect(context.Background(), "db1-conn-str"); err != nil {
		t.Fatalf("db1 Connect failed: %v", err)
	}
	if err := db2.Connect(context.Background(), "db2-conn-str"); err != nil {
		t.Fatalf("db2 Connect failed: %v", err)
	}

	db1.Close()
	if !pool1.closed {
		t.Error("db1 pool should be closed after db1.Close()")
	}
	if pool2.closed {
		t.Error("db2 pool should NOT be closed after db1.Close()")
	}

	db2.Close()
	if !pool2.closed {
		t.Error("db2 pool should be closed after db2.Close()")
	}
}

// --- Mocks ---
type mockPool struct {
	closed     bool
	pingErr    error
	closeCount int
}

func (m *mockPool) Close() {
	m.closed = true
	m.closeCount++
}
func (m *mockPool) Ping(context.Context) error {
	if m.closed {
		return errors.New("pool is closed")
	}
	return m.pingErr
}

type mockFactory struct {
	parseConfigErr   error
	newWithConfigErr error
	pool             *mockPool
}

func (f *mockFactory) ParseConfig(string) (any, error) {
	return struct{}{}, f.parseConfigErr
}
func (f *mockFactory) NewWithConfig(_ context.Context, _ any) (PostgresDBPool, error) {
	if f.newWithConfigErr != nil {
		return nil, f.newWithConfigErr
	}
	return f.pool, nil
}
