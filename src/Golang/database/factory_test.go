package database

import (
	"testing"
)

func TestDatabaseFactory_NewDatabase_Memory(t *testing.T) {
	factory := NewDatabaseFactory()

	db, err := factory.NewDatabase("memory://test")
	if err != nil {
		t.Errorf("Factory should create memory database without error: %v", err)
	}

	// Verify it's actually a memory database
	memDb, ok := db.(*MemoryDatabase)
	if !ok {
		t.Error("Factory should return MemoryDatabase for memory:// connection string")
	}
	if memDb == nil {
		t.Error("Created memory database should not be nil")
	}
}

func TestDatabaseFactory_NewDatabase_EmptyConnectionString(t *testing.T) {
	factory := NewDatabaseFactory()

	db, err := factory.NewDatabase("")
	if err == nil {
		t.Error("Factory should return error for empty connection string")
	}
	if db != nil {
		t.Error("Factory should return nil database for empty connection string")
	}
}
