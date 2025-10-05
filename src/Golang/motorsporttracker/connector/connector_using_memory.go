package connector

import "log/slog"

type MockResponse struct {
	Data []byte
	Err  error
}

type InMemoryConnector struct {
	data map[string]MockResponse
}

// NewInMemoryConnector creates a new InMemoryConnector instance.
func NewInMemoryConnector(data map[string]MockResponse) *InMemoryConnector {
	return &InMemoryConnector{
		data: data,
	}
}

func (c *InMemoryConnector) SetMockResponse(url string, response MockResponse) {
	c.data[url] = response
}

func (c *InMemoryConnector) ClearMockResponses() {
	c.data = make(map[string]MockResponse)
}

// Get retrieves data from the in-memory store.
func (c *InMemoryConnector) Get(url string) ([]byte, error) {
	if response, exists := c.data[url]; exists {
		slog.Debug("Using memory connector for url: " + url)

		return response.Data, response.Err
	}

	panic("attempt to get from unexpected URL: " + url)
}
