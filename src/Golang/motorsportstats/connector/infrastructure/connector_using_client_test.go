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

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetClassification() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	data, err := connector.GetClassification(suite.T().Context(), "8ebecded-edca-4521-bba3-aabfd89f45de")
	suite.NoError(err)
	suite.NotEmpty(data)

	data, err = connector.GetClassification(suite.T().Context(), "missing-uuid")
	suite.Error(err)
	suite.Empty(data)
}

// The row is car #13 in the 2015 6 Hours of Nürburgring race classification, verbatim: an entry
// without drivers, and so without a nationality.
func (suite *ConnectorUsingClientFunctionalTestSuite) TestValidateClassificationWithoutNationality() {
	payload := `{"details":[{"finishPosition":10,"gridPosition":8,"carNumber":"13","drivers":[],"team":{"name":"Rebellion Racing","uuid":"29a38a28-4469-4d6f-93ae-b0fd2f0902bd","colour":"#C02828","picture":"https://assets.motorsportstats.com/team/icon/teamColour_C02828.svg","carIcon":"https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_C02828.svg"},"nationality":null,"laps":15,"points":0.0,"time":109171.0,"classifiedStatus":"DNF","avgLapSpeed":169.396,"fastestLapTime":0.0,"gap":{"timeToLead":9970.0,"timeToNext":1548.0,"lapsToLead":0,"lapsToNext":0},"best":{"lap":0,"time":0.0,"fastest":false,"speed":0.0}}],"retirements":[]}`

	err := suite.connector.validate(suite.T().Context(), []byte(payload), schemaClassification)
	require.NoError(suite.T(), err)
}

func TestFunctional_ConnectorUsingClient(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(ConnectorUsingClientFunctionalTestSuite))
}
