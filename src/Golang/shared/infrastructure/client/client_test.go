package client

import (
	"encoding/json"
	"io"
	"net/http"
	"strings"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	_func "github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/func"
)

type inMemoryRoundTripper struct {
	responses map[string]http.Response
}

func (irt *inMemoryRoundTripper) RoundTrip(req *http.Request) (*http.Response, error) {
	if resp, ok := irt.responses[req.URL.String()]; ok {
		return &resp, nil
	}

	headers := map[string]string{}
	for key, values := range req.Header {
		if key[0] == 'X' && len(values) > 0 {
			headers[key] = values[0]
		}
	}

	headersJSON := _func.MustReturn(json.Marshal(headers)).([]byte)

	return &http.Response{
		StatusCode: http.StatusOK,
		Body:       io.NopCloser(strings.NewReader(string(headersJSON))),
	}, nil
}

type ClientUnitTestSuite struct {
	suite.Suite

	client *Client
}

func (suite *ClientUnitTestSuite) SetupSuite() {
	suite.client = NewClient()

	suite.client.inner.Transport = &inMemoryRoundTripper{}
}

func (suite *ClientUnitTestSuite) TestGet() {
	dummyResponse := "Hello World!"

	prepareMockResponses(suite.client, dummyResponse)

	suite.T().Run("OK Response", func(t *testing.T) {
		data, err := suite.client.Get("https://example.com", map[string]string{})
		require.NoError(t, err)
		require.Equal(t, dummyResponse, string(data))
	})

	suite.T().Run("Error Response", func(t *testing.T) {
		_, err := suite.client.Get("https://example.com/404", map[string]string{})
		require.Error(t, err)
	})

	suite.T().Run("Headers are sent", func(t *testing.T) {
		headersToSend := map[string]string{
			"X-Custom-Header": "CustomValue",
			"X-Another-One":   "AnotherValue",
		}

		data, err := suite.client.Get("https://example.com/with-headers", headersToSend)
		require.NoError(t, err)

		var receivedHeaders map[string]string
		err = json.Unmarshal(data, &receivedHeaders)
		require.NoError(t, err)

		require.Equal(t, headersToSend, receivedHeaders)
	})
}

func TestUnit_Client(t *testing.T) {
	suite.Run(t, new(ClientUnitTestSuite))
}

func prepareMockResponses(client *Client, dummyResponse string) {
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

	client.inner.Transport.(*inMemoryRoundTripper).responses = mockResponses
}
