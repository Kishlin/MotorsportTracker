package connector

import (
	"errors"
	"testing"
)

func TestInMemoryConnector_Get_ReturnsData(t *testing.T) {
	expectedData := []byte("mock response data")
	mockData := map[string]MockResponse{
		"https://example.com/api/data": {
			data: expectedData,
			err:  nil,
		},
	}

	connector := NewInMemoryConnector(mockData)

	result, err := connector.Get("https://example.com/api/data")

	if err != nil {
		t.Errorf("Expected no error, got %v", err)
	}
	if string(result) != string(expectedData) {
		t.Errorf("Expected data '%s', got '%s'", string(expectedData), string(result))
	}
}

func TestInMemoryConnector_Get_ReturnsError(t *testing.T) {
	expectedError := errors.New("mock error response")
	mockData := map[string]MockResponse{
		"https://example.com/api/error": {
			data: nil,
			err:  expectedError,
		},
	}

	connector := NewInMemoryConnector(mockData)

	result, err := connector.Get("https://example.com/api/error")

	if !errors.Is(err, expectedError) {
		t.Errorf("Expected error '%v', got '%v'", expectedError, err)
	}
	if result != nil {
		t.Errorf("Expected nil data when error occurs, got '%s'", string(result))
	}
}

func TestInMemoryConnector_Get_PanicsOnUnexpectedURL(t *testing.T) {
	mockData := map[string]MockResponse{
		"https://example.com/api/known": {
			data: []byte("known data"),
			err:  nil,
		},
	}

	connector := NewInMemoryConnector(mockData)

	defer func() {
		if r := recover(); r == nil {
			t.Error("Expected panic when accessing unexpected URL, but no panic occurred")
		} else {
			expectedPanic := "attempt to get from unexpected URL: https://example.com/api/unknown"
			if r != expectedPanic {
				t.Errorf("Expected panic message '%s', got '%v'", expectedPanic, r)
			}
		}
	}()

	_, _ = connector.Get("https://example.com/api/unknown")
}

func TestInMemoryConnector_Get_MultipleURLs(t *testing.T) {
	mockData := map[string]MockResponse{
		"https://api.com/users": {
			data: []byte(`{"users": []}`),
			err:  nil,
		},
		"https://api.com/products": {
			data: []byte(`{"products": []}`),
			err:  nil,
		},
		"https://api.com/error": {
			data: nil,
			err:  errors.New("service unavailable"),
		},
	}

	connector := NewInMemoryConnector(mockData)

	// Test first URL
	result1, err1 := connector.Get("https://api.com/users")
	if err1 != nil {
		t.Errorf("Expected no error for users URL, got %v", err1)
	}
	if string(result1) != `{"users": []}` {
		t.Errorf("Unexpected data for users URL: %s", string(result1))
	}

	// Test second URL
	result2, err2 := connector.Get("https://api.com/products")
	if err2 != nil {
		t.Errorf("Expected no error for products URL, got %v", err2)
	}
	if string(result2) != `{"products": []}` {
		t.Errorf("Unexpected data for products URL: %s", string(result2))
	}

	// Test error URL
	result3, err3 := connector.Get("https://api.com/error")
	if err3 == nil {
		t.Error("Expected error for error URL, got nil")

	} else if err3.Error() != "service unavailable" {
		t.Errorf("Expected 'service unavailable' error, got '%v'", err3)
	}
	if result3 != nil {
		t.Errorf("Expected nil data for error URL, got '%s'", string(result3))
	}
}
