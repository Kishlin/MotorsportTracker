package client

import (
	"io"
	"net/http"
	"strings"
	"testing"
)

func TestClient_TestGet(t *testing.T) {
	dummyResponse := "Hello World!"
	mockResponses := map[string]http.Response{
		"https://example.com": {
			StatusCode: http.StatusOK,
			Body:       io.NopCloser(strings.NewReader(dummyResponse)),
		},
		"https://example.com/404": {
			StatusCode: http.StatusNotFound,
			Body:       io.NopCloser(strings.NewReader("")), // prevents potential nil pointer dereference
		},
	}

	connector := setup(withInMemoryRoundTripper(mockResponses))

	t.Run("OK Response", func(t *testing.T) {
		// Test with a valid URL
		data, err := connector.Get("https://example.com")
		if err != nil {
			t.Errorf("Expected no error, got %v", err)
		}
		if string(data) != dummyResponse {
			t.Errorf("Expected '%s', got '%s'", dummyResponse, string(data))
		}
	})

	t.Run("Error Response", func(t *testing.T) {
		// Test with an error response
		_, err := connector.Get("https://example.com/404")
		if err == nil {
			t.Error("Expected an error, got none")
		}
	})
}

type setupOption func(*Connector)

func setup(opts ...setupOption) *Connector {
	connector := NewConnector()

	for _, opt := range opts {
		opt(connector)
	}

	return connector
}

type inMemoryRoundTripper struct {
	responses map[string]http.Response
}

func (irt *inMemoryRoundTripper) RoundTrip(req *http.Request) (*http.Response, error) {
	if resp, ok := irt.responses[req.URL.String()]; ok {
		return &resp, nil
	}

	return &http.Response{
		StatusCode: http.StatusOK,
		Body:       io.NopCloser(strings.NewReader("")),
	}, nil
}

func withInMemoryRoundTripper(responses map[string]http.Response) setupOption {
	return func(c *Connector) {
		c.client.Transport = &inMemoryRoundTripper{responses}
	}
}
