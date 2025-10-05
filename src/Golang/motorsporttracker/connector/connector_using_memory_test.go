package connector

import (
	"errors"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type InMemoryConnectorUnitTestSuite struct {
	suite.Suite

	connector Connector
}

func (suite *InMemoryConnectorUnitTestSuite) SetupSuite() {
	mockData := map[string]MockResponse{
		"https://api.com/users": {
			Data: []byte(`{"users": []}`),
			Err:  nil,
		},
		"https://api.com/error": {
			Data: nil,
			Err:  errors.New("service unavailable"),
		},
	}

	suite.connector = NewInMemoryConnector(mockData)
}

func (suite *InMemoryConnectorUnitTestSuite) TestGet() {
	suite.T().Run("it returns prepared data", func(t *testing.T) {
		data, err := suite.connector.Get("https://api.com/users")
		require.NoError(t, err)
		require.Equal(t, `{"users": []}`, string(data))
	})

	suite.T().Run("it returns prepared errors", func(t *testing.T) {
		data, err := suite.connector.Get("https://api.com/error")
		require.Error(t, err)
		require.Nil(t, data)
		require.Equal(t, "service unavailable", err.Error())
	})

	suite.T().Run("it panics on unexpected URL", func(t *testing.T) {
		defer func() {
			require.NotNil(t, recover())
		}()

		_, _ = suite.connector.Get("https://api.com/unknown")
	})
}

func TestUnit_InMemoryConnector(t *testing.T) {
	suite.Run(t, new(InMemoryConnectorUnitTestSuite))
}
