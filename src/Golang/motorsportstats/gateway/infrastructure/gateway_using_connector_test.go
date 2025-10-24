package infrastructure

import (
	"net/http/httptest"
	"testing"

	"github.com/stretchr/testify/suite"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
)

type GatewayUsingConnectorFunctionalTestSuite struct {
	suite.Suite

	testServer *httptest.Server
	gateway    *GatewayUsingConnector
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) SetupSuite() {
	suite.testServer = connector.NewTestServer()

	suite.gateway = NewGatewayUsingConnector(
		connector.ConnectorForTestServer(suite.testServer),
	)
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TeardownSuite() {
	suite.testServer.Close()
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetSeries() {
	series, err := suite.gateway.GetSeries(suite.T().Context())
	suite.Require().NoError(err)
	suite.Require().NotEmpty(series)
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetSeasons() {
	seasons, err := suite.gateway.GetSeasons(suite.T().Context(), "a33f8b4a-2b22-41ce-8e7d-0aea08f0e176")
	suite.Require().NoError(err)
	suite.Require().NotEmpty(seasons)

	seasons, err = suite.gateway.GetSeasons(suite.T().Context(), "missing-uuid")
	suite.Require().Error(err)
	suite.Require().Empty(seasons)
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetCalendar() {
	calendar, err := suite.gateway.GetCalendar(suite.T().Context(), "71fdf79a-0cf3-4aab-99f6-b9a836c333da")
	suite.Require().NoError(err)
	suite.Require().NotEmpty(calendar)

	calendar, err = suite.gateway.GetCalendar(suite.T().Context(), "missing-uuid")
	suite.Require().Error(err)
	suite.Require().Empty(calendar)
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetClassification() {
	classification, err := suite.gateway.GetClassification(suite.T().Context(), "8ebecded-edca-4521-bba3-aabfd89f45de")
	suite.NoError(err)
	suite.NotEmpty(classification)

	classification, err = suite.gateway.GetClassification(suite.T().Context(), "missing-uuid")
	suite.Error(err)
	suite.Empty(classification)
}

func TestFunctional_GatewayUsingConnector(t *testing.T) {
	suite.Run(t, new(GatewayUsingConnectorFunctionalTestSuite))
}
