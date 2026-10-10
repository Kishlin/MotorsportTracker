package infrastructure

import (
	"net/http/httptest"
	"testing"

	"github.com/stretchr/testify/suite"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// The test server's seasons and classes the standings tests read.
const (
	f12024   = "afc5ab1b-c44b-4c21-904d-215fcc800578"
	wec2017  = "0a83e053-b179-4b57-b509-d09f26209e74"
	lmp2     = "3da46a4d-11f3-428a-bb81-5c646959c99f"
	lmgtePro = "35c0e386-1af3-4790-bbe2-208269a7c775"
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

func (suite *GatewayUsingConnectorFunctionalTestSuite) TearDownSuite() {
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

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetAvailableStandings() {
	suite.Run("Keeps the overall standing as a nil entry ahead of the classes", func() {
		available, err := suite.gateway.GetAvailableStandings(suite.T().Context(), wec2017)
		suite.Require().NoError(err)

		suite.Require().Len(available.Drivers, 4)
		suite.Require().Nil(available.Drivers[0])
		suite.Require().Equal("3da46a4d-11f3-428a-bb81-5c646959c99f", available.Drivers[1].UUID)
		suite.Require().Equal("LMP2", fn.Deref(available.Drivers[1].Name, ""))

		suite.Require().Len(available.Teams, 3)
		suite.Require().NotNil(available.Teams[0], "classes only, no overall standing")

		suite.Require().Len(available.Constructors, 2)
		suite.Require().Nil(available.Constructors[0])
	})

	suite.Run("A missing season fails", func() {
		available, err := suite.gateway.GetAvailableStandings(suite.T().Context(), "missing-uuid")
		suite.Require().Error(err)
		suite.Require().Nil(available)
	})
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetDriverStandings() {
	suite.Run("An overall standing", func() {
		standings, err := suite.gateway.GetDriverStandings(suite.T().Context(), f12024, "")
		suite.Require().NoError(err)
		suite.Require().Len(standings.Standings, 1)

		row := standings.Standings[0]
		suite.Require().Equal("Max Verstappen", fn.Deref(row.Driver.Name, ""))
		suite.Require().Equal("Oracle Red Bull Racing", fn.Deref(row.Team.Name, ""))
		suite.Require().Equal("Netherlands", fn.Deref(row.Nationality.Name, ""))
		suite.Require().Equal(1, fn.Deref(row.Position, -1))
		suite.Require().InDelta(437.0, fn.Deref(row.Points, -1), 0)
		suite.Require().InDelta(2.96, fn.Deref(row.Analytics.AvgFinishPosition, -1), 0)
		suite.Require().Equal(9, fn.Deref(row.Analytics.Wins, -1))
	})

	suite.Run("A class standing", func() {
		standings, err := suite.gateway.GetDriverStandings(suite.T().Context(), wec2017, lmp2)
		suite.Require().NoError(err)
		suite.Require().Len(standings.Standings, 1)
		suite.Require().Equal("Julien Canal", fn.Deref(standings.Standings[0].Driver.Name, ""))
		suite.Require().Nil(standings.Standings[0].Driver.Picture)
	})

	suite.Run("A class the season does not hold fails", func() {
		standings, err := suite.gateway.GetDriverStandings(suite.T().Context(), f12024, lmp2)
		suite.Require().Error(err)
		suite.Require().Nil(standings)
	})
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetTeamStandings() {
	suite.Run("Keeps the payload order, a team appearing once per car", func() {
		standings, err := suite.gateway.GetTeamStandings(suite.T().Context(), wec2017, lmgtePro)
		suite.Require().NoError(err)

		names := make([]string, 0, len(standings.Standings))
		positions := make([]int, 0, len(standings.Standings))
		for _, row := range standings.Standings {
			names = append(names, fn.Deref(row.Team.Name, ""))
			positions = append(positions, fn.Deref(row.Position, -1))
		}

		suite.Require().Equal([]string{"AF Corse", "Ford Chip Ganassi Team UK", "Porsche GT Team", "AF Corse"}, names)
		suite.Require().Equal([]int{1, 2, 3, 4}, positions)
		suite.Require().Equal(standings.Standings[0].Team.UUID, standings.Standings[3].Team.UUID)
	})

	suite.Run("Parses the team-only fields", func() {
		standings, err := suite.gateway.GetTeamStandings(suite.T().Context(), wec2017, lmgtePro)
		suite.Require().NoError(err)

		row := standings.Standings[0]
		suite.Require().Equal("https://assets.motorsportstats.com/team/icon/teamColour_CFDBDD.svg", fn.Deref(row.Icon, ""))
		suite.Require().Equal("Italy", fn.Deref(row.CountryRepresenting.Name, ""))
		suite.Require().Equal(5, fn.Deref(row.Analytics.ClassWins, -1))
		suite.Require().Equal(0, fn.Deref(row.Analytics.Finishes1And2, -1))
	})

	suite.Run("A standing the season does not list fails", func() {
		standings, err := suite.gateway.GetTeamStandings(suite.T().Context(), f12024, "")
		suite.Require().Error(err)
		suite.Require().Nil(standings)
	})
}

func (suite *GatewayUsingConnectorFunctionalTestSuite) TestGetConstructorStandings() {
	suite.Run("An overall standing", func() {
		standings, err := suite.gateway.GetConstructorStandings(suite.T().Context(), f12024, "")
		suite.Require().NoError(err)
		suite.Require().Len(standings.Standings, 1)

		row := standings.Standings[0]
		suite.Require().Equal("McLaren-Mercedes", fn.Deref(row.Constructor.Name, ""))
		suite.Require().Nil(row.Constructor.Picture)
		suite.Require().Equal("McLaren Formula 1 Team", fn.Deref(row.Team.Name, ""))
		suite.Require().Equal(6, fn.Deref(row.Analytics.Wins, -1))
	})

	suite.Run("A missing season fails", func() {
		standings, err := suite.gateway.GetConstructorStandings(suite.T().Context(), "missing-uuid", "")
		suite.Require().Error(err)
		suite.Require().Nil(standings)
	})
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
	t.Parallel()

	suite.Run(t, new(GatewayUsingConnectorFunctionalTestSuite))
}
