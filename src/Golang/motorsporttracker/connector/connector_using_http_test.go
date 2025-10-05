package connector

import (
	"io"
	"net/http"
	"strings"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

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

type ConnectorUsingHttpFunctionalTestSuite struct {
	suite.Suite

	connector *MotorsportStatsConnector
}

func (suite *ConnectorUsingHttpFunctionalTestSuite) SetupSuite() {
	suite.connector = NewConnector()

	suite.connector.client.Transport = &inMemoryRoundTripper{}
}

func (suite *ConnectorUsingHttpFunctionalTestSuite) TestGet() {
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

	prepareMockResponses(suite.connector, mockResponses)

	suite.T().Run("OK Response", func(t *testing.T) {
		data, err := suite.connector.Get("https://example.com")
		require.NoError(t, err)
		require.Equal(t, dummyResponse, string(data))
	})

	suite.T().Run("Error Response", func(t *testing.T) {
		_, err := suite.connector.Get("https://example.com/404")
		require.Error(t, err)
	})
}

func TestFunctional_ConnectorUsingHttp(t *testing.T) {
	suite.Run(t, new(ConnectorUsingHttpFunctionalTestSuite))
}

func prepareMockResponses(connector Connector, responses map[string]http.Response) {
	connector.(*MotorsportStatsConnector).client.Transport.(*inMemoryRoundTripper).responses = responses
}
