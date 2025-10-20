package infrastructure

import (
	"net/http/httptest"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type ConnectorUsingClientFunctionalTestSuite struct {
	suite.Suite

	testServer *httptest.Server
	connector  *ConnectorUsingClient
}

func (suite *ConnectorUsingClientFunctionalTestSuite) SetupSuite() {
	suite.testServer = NewTestServer()
	suite.connector = ConnectorForTestServer(suite.testServer)
}

func (suite *ConnectorUsingClientFunctionalTestSuite) TearDownSuite() {
	suite.testServer.Close()
}

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetSeries() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	data, err := connector.GetSeries(suite.T().Context())
	require.NoError(suite.T(), err)
	require.NotEmpty(suite.T(), data)
}

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetSeasons() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	data, err := connector.GetSeasons(suite.T().Context(), "a33f8b4a-2b22-41ce-8e7d-0aea08f0e176")
	require.NoError(suite.T(), err)
	require.NotEmpty(suite.T(), data)

	data, err = connector.GetSeasons(suite.T().Context(), "missing-uuid")
	require.Error(suite.T(), err)
	require.Empty(suite.T(), data)
}

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetCalendar() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	data, err := connector.GetCalendar(suite.T().Context(), "71fdf79a-0cf3-4aab-99f6-b9a836c333da")
	require.NoError(suite.T(), err)
	require.NotEmpty(suite.T(), data)

	data, err = connector.GetCalendar(suite.T().Context(), "missing-uuid")
	require.Error(suite.T(), err)
	require.Empty(suite.T(), data)
}

func TestFunctional_ConnectorUsingClient(t *testing.T) {
	suite.Run(t, new(ConnectorUsingClientFunctionalTestSuite))
}
