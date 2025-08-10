package connector

import (
	"fmt"
	"io"
	"log/slog"
	"net/http"
)

type MotorsportStatsConnector struct {
	client *http.Client
}

// NewConnector creates a new HTTPConnector instance with a default HTTP client.
func NewConnector() *MotorsportStatsConnector {
	return &MotorsportStatsConnector{
		client: &http.Client{},
	}
}

// Get retrieves data
func (c *MotorsportStatsConnector) Get(url string) ([]byte, error) {
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return []byte{}, err
	}

	req.Header.Add("X-Parent-Referer", "https://motorsportstats.com/")
	req.Header.Add("Origin", "https://widgets.motorsportstats.com")

	resp, err := c.client.Do(req)
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
