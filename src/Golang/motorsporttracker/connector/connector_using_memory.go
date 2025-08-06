package connector

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

// Get retrieves data from the in-memory store.
func (c *InMemoryConnector) Get(url string) ([]byte, error) {
	if response, exists := c.data[url]; exists {
		return response.Data, response.Err
	}

	panic("attempt to get from unexpected URL: " + url)
}
