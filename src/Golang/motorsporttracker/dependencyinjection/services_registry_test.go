package dependencyinjection

import (
	"context"
	"os"
	"sync"
	"testing"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/domain"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/database"
)

// Resource Cleanup - Close Method Safety
func TestClose_HandlesNilResources(t *testing.T) {
	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)

	// Should not panic when closing without initializing resources
	defer func() {
		if r := recover(); r != nil {
			t.Errorf("Close should not panic with nil resources: %v", r)
		}
	}()

	registry.Close()
}

// Panic Prevention - Missing Environment Variables
func TestGetCoreDatabase_PanicsOnMissingEnvVar(t *testing.T) {
	// Save original env var
	originalURL := os.Getenv("POSTGRES_CORE_URL")
	defer func() {
		if originalURL != "" {
			_ = os.Setenv("POSTGRES_CORE_URL", originalURL)
		} else {
			_ = os.Unsetenv("POSTGRES_CORE_URL")
		}
	}()

	// Clear the environment variable
	_ = os.Unsetenv("POSTGRES_CORE_URL")

	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)

	defer func() {
		if r := recover(); r == nil {
			t.Error("Expected panic when POSTGRES_CORE_URL is not set")
		}
	}()

	registry.GetCoreDatabase(context.Background())
}

// Thread Safety - Concurrent Access to GetCoreDatabase
func TestGetCoreDatabase_ThreadSafety(t *testing.T) {
	cleanup := replaceEnvVars(map[string]string{
		"POSTGRES_CORE_URL": "memory://test",
	})
	defer cleanup()

	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	const goroutines = 10
	var wg sync.WaitGroup
	results := make([]database.Database, goroutines)

	// Launch multiple goroutines accessing the same method
	for i := 0; i < goroutines; i++ {
		wg.Add(1)
		go func(index int) {
			defer wg.Done()
			results[index] = registry.GetCoreDatabase(context.Background())
		}(i)
	}

	wg.Wait()

	// Verify all goroutines got the same instance
	for i := 1; i < goroutines; i++ {
		if results[i] != results[0] {
			t.Error("GetCoreDatabase should return the same instance for concurrent calls")
		}
	}
}

// Singleton Behavior - Multiple Calls Return Same Instance
func TestGetCoreDatabase_SingletonBehavior(t *testing.T) {
	cleanup := replaceEnvVars(map[string]string{
		"POSTGRES_CORE_URL": "memory://test",
	})
	defer cleanup()

	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	db1 := registry.GetCoreDatabase(context.Background())
	db2 := registry.GetCoreDatabase(context.Background())

	if db1 != db2 {
		t.Error("GetCoreDatabase should return the same instance on multiple calls")
	}
}

// GetConnector - Should Not Panic
func TestGetConnector_DoesNotPanic(t *testing.T) {
	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	defer func() {
		if r := recover(); r != nil {
			t.Errorf("GetConnector should not panic: %v", r)
		}
	}()

	conn := registry.GetConnector()
	if conn == nil {
		t.Error("MotorsportStatsConnectorUsingClient should not be nil")
	}
}

// GetIntentsQueue - Can Create Memory Queue Without Environment
func TestGetIntentsQueue_WithMemoryQueue(t *testing.T) {
	cleanup := replaceEnvVars(map[string]string{
		"QUEUE_SCRAPPING_INTENTS": "intents",
		"SQS_ENDPOINT":            "memory://test",
		"SQS_REGION":              "us-east-1",
		"SQS_ACCESS_KEY":          "test-access-key",
		"SQS_SECRET_KEY":          "test-secret-key",
		"SQS_SESSION_TOKEN":       "",
	})
	defer cleanup()

	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	defer func() {
		if r := recover(); r != nil {
			t.Errorf("GetIntentsQueue should not panic with memory queue: %v", r)
		}
	}()

	queue := registry.GetIntentsQueue()
	if queue == nil {
		t.Error("Queue should not be nil")
	}
}

func TestGetClientCacheDatabase_WithMemoryDatabase(t *testing.T) {
	cleanup := replaceEnvVars(map[string]string{
		"POSTGRES_CLIENT_CACHE_URL": "memory://test",
	})
	defer cleanup()

	registry := NewServicesRegistry(
		connector.NewInMemoryConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	defer func() {
		if r := recover(); r != nil {
			t.Errorf("GetClientCacheDatabase should not panic with memory database: %v", r)
		}
	}()

	db := registry.GetClientCacheDatabase(context.Background())
	if db == nil {
		t.Error("Client cache database should not be nil")
	}
}

func replaceEnvVars(vars map[string]string) func() {
	originals := make(map[string]string)
	for k, v := range vars {
		originals[k] = os.Getenv(k)
		_ = os.Setenv(k, v)
	}

	return func() {
		for k, v := range originals {
			if v == "" {
				_ = os.Unsetenv(k)
			} else {
				_ = os.Setenv(k, v)
			}
		}
	}
}
