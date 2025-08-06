package connector

import (
	"errors"
	"fmt"
	"io"
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
		return []byte{}, errors.New("making request: " + err.Error())
	}

	defer func(Body io.ReadCloser) {
		err := Body.Close()
		if err != nil {
			fmt.Println("error closing response body:", err)
		}
	}(resp.Body)

	if resp.StatusCode != http.StatusOK {
		return []byte{}, errors.New("fetching data: " + resp.Status)
	}

	return io.ReadAll(resp.Body)
}
