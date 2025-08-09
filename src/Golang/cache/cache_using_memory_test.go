package cache

import "testing"

func TestInMemoryCache_NominalUse(t *testing.T) {
	cache := setupInMemoryCache()

	namespace := "testNamespace"
	key := "testKey"
	value := []byte("testValue")

	// First get should return nil
	retrievedValue, hit, err := cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("Failed to get value from cache: %v", err)
	}
	if hit {
		t.Error("Expected hit to be false, got true")
	}
	if retrievedValue != nil {
		t.Errorf("Expected nil, got %s", retrievedValue)
	}

	// Count should be 0 when it's empty
	if count := cache.ItemsCount(); count != 0 {
		t.Errorf("Expected 0 items in cache, got %d", count)
	}

	// Set a value in the cache
	if err := cache.Set(namespace, key, value); err != nil {
		t.Fatalf("Failed to set value in cache: %v", err)
	}

	// Get the value from the cache
	retrievedValue, hit, err = cache.Get(namespace, key)
	if err != nil {
		t.Fatalf("Failed to get value from cache: %v", err)
	}
	if !hit {
		t.Error("Expected hit to be true, got false")
	}
	if string(retrievedValue) != string(value) {
		t.Errorf("Expected %s, got %s", value, retrievedValue)
	}

	// Count should be 1 after setting a value
	if count := cache.ItemsCount(); count != 1 {
		t.Errorf("Expected 1 item in cache, got %d", count)
	}

	// Set another value in the same namespace
	anotherKey := "anotherKey"
	anotherValue := []byte("anotherValue")
	if err := cache.Set(namespace, anotherKey, anotherValue); err != nil {
		t.Fatalf("Failed to set another value in cache: %v", err)
	}

	// Count should be 2 after setting another value
	if count := cache.ItemsCount(); count != 2 {
		t.Errorf("Expected 2 items in cache, got %d", count)
	}

	// Set the same key again with a different value
	newValue := []byte("newValue")
	if err := cache.Set(namespace, key, newValue); err != nil {
		t.Fatalf("Failed to set new value in cache: %v", err)
	}

	// Count should still be 2
	if count := cache.ItemsCount(); count != 2 {
		t.Errorf("Expected 2 items in cache, got %d", count)
	}
}

func setupInMemoryCache() *InMemoryCache {
	cache := NewInMemoryCache()
	if cache == nil {
		panic("Failed to create InMemoryCache")
	}

	return cache
}
