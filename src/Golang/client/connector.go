package client

import (
	"errors"
	"io"
	"net/http"
)

type Connector struct {
	client *http.Client
}

// NewConnector creates a new Connector instance with a default HTTP client.
func NewConnector() *Connector {
	return &Connector{
		client: &http.Client{},
	}
}

// Get retrieves data
func (c *Connector) Get(url string) ([]byte, error) {
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

	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		return []byte{}, errors.New("fetching data: " + resp.Status)
	}

	return io.ReadAll(resp.Body)
}
