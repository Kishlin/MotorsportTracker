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
}

func setupInMemoryCache() *InMemoryCache {
	cache := NewInMemoryCache()
	if cache == nil {
		panic("Failed to create InMemoryCache")
	}

	return cache
}
