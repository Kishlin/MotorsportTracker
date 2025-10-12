package infrastructure

import (
	"fmt"
	"io"
	"log/slog"
	"net/http"
)

type Client struct {
	inner *http.Client
	host  string
}

// NewClient creates a new Client instance
func NewClient(host string) *Client {
	return &Client{
		inner: &http.Client{},
		host:  host,
	}
}

func (c *Client) Get(endpoint string, headers map[string]string) ([]byte, error) {
	url := c.host + endpoint

	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return []byte{}, fmt.Errorf("creating request: %v", err)
	}

	for key, value := range headers {
		req.Header.Set(key, value)
	}

	resp, err := c.inner.Do(req)
	if err != nil {
		return []byte{}, fmt.Errorf("making request: %w", err)
	}

	defer func(Body io.ReadCloser) {
		err := Body.Close()
		if err != nil {
			fmt.Println("cloning response body:", err)
		}
	}(resp.Body)

	if resp.StatusCode != http.StatusOK {
		return []byte{}, fmt.Errorf("fetching data: %s", resp.Status)
	}

	slog.Debug("Fetched data from URL", "url", url)

	return io.ReadAll(resp.Body)
}
