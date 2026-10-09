package infrastructure

import (
	"encoding/json"
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

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetAvailableStandings() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	data, err := connector.GetAvailableStandings(suite.T().Context(), "afc5ab1b-c44b-4c21-904d-215fcc800578")
	require.NoError(suite.T(), err)
	require.NotEmpty(suite.T(), data)

	data, err = connector.GetAvailableStandings(suite.T().Context(), "missing-uuid")
	require.Error(suite.T(), err)
	require.Empty(suite.T(), data)
}

func (suite *ConnectorUsingClientFunctionalTestSuite) TestGetStandings() {
	testServer := NewTestServer()
	defer testServer.Close()

	connector := ConnectorForTestServer(testServer)

	suite.Run("An overall standing", func() {
		data, err := connector.GetStandings(suite.T().Context(), "afc5ab1b-c44b-4c21-904d-215fcc800578", StandingDrivers, "")
		require.NoError(suite.T(), err)
		require.Contains(suite.T(), string(data), "Max Verstappen")
	})

	suite.Run("A class standing", func() {
		data, err := connector.GetStandings(suite.T().Context(), "0a83e053-b179-4b57-b509-d09f26209e74", StandingDrivers, "3da46a4d-11f3-428a-bb81-5c646959c99f")
		require.NoError(suite.T(), err)
		require.Contains(suite.T(), string(data), "Julien Canal")
	})

	suite.Run("A class the season does not hold", func() {
		data, err := connector.GetStandings(suite.T().Context(), "afc5ab1b-c44b-4c21-904d-215fcc800578", StandingDrivers, "3da46a4d-11f3-428a-bb81-5c646959c99f")
		require.Error(suite.T(), err)
		require.Empty(suite.T(), data)
	})

	suite.Run("An unknown kind fails before any request", func() {
		data, err := connector.GetStandings(suite.T().Context(), "afc5ab1b-c44b-4c21-904d-215fcc800578", StandingKind("nations"), "")
		require.ErrorContains(suite.T(), err, `unknown standing kind "nations"`)
		require.Empty(suite.T(), data)
	})
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

// The base payload is the 2017 World Endurance Championship, verbatim: an overall standing then
// classes for drivers and constructors, classes alone for teams.
func (suite *ConnectorUsingClientFunctionalTestSuite) TestValidateAvailableStandings() {
	lmp2 := `{"name":"LMP2","uuid":"3da46a4d-11f3-428a-bb81-5c646959c99f"}`
	gtePro := `{"name":"LMGTEPro","uuid":"35c0e386-1af3-4790-bbe2-208269a7c775"}`
	gteAm := `{"name":"LMGTEAm","uuid":"d1245d88-10ea-4fe9-81bb-b2cf0596fe89"}`

	payload := func(drivers string, extra string) []byte {
		return []byte(`{"driverStandings":` + drivers + `,"teamStandings":[` + lmp2 + `,` + gtePro + `,` + gteAm + `],"constructorStandings":[null,` + gtePro + `]` + extra + `}`)
	}

	valid := map[string][]byte{
		"An overall standing then classes": payload(`[null,`+lmp2+`,`+gtePro+`,`+gteAm+`]`, ""),
		"An overall standing alone":        payload(`[null]`, ""),
		"Classes without an overall one":   payload(`[`+lmp2+`]`, ""),
		"No standing of a kind at all":     payload(`[]`, ""),
	}

	for name, content := range valid {
		suite.Run(name, func() {
			require.NoError(suite.T(), suite.connector.validate(suite.T().Context(), content, schemaAvailableStandings))
		})
	}

	invalid := map[string][]byte{
		"An overall standing after a class": payload(`[`+lmp2+`,null]`, ""),
		"Two overall standings":             payload(`[null,null]`, ""),
		"The same class twice":              payload(`[null,`+lmp2+`,`+lmp2+`]`, ""),
		"A class with an unknown key":       payload(`[{"name":"LMP2","uuid":"3da46a4d-11f3-428a-bb81-5c646959c99f","colour":"#000"}]`, ""),
		"A class without a uuid":            payload(`[{"name":"LMP2"}]`, ""),
		"A class with an empty name":        payload(`[{"name":"","uuid":"3da46a4d-11f3-428a-bb81-5c646959c99f"}]`, ""),
		"A standing that is not a class":    payload(`["LMP2"]`, ""),
		"An unknown kind of standing":       payload(`[null]`, `,"nationStandings":[null]`),
		"A missing kind of standing":        []byte(`{"driverStandings":[null],"teamStandings":[]}`),
	}

	for name, content := range invalid {
		suite.Run(name, func() {
			require.Error(suite.T(), suite.connector.validate(suite.T().Context(), content, schemaAvailableStandings))
		})
	}
}

// Each base row is verbatim: Max Verstappen in the Formula 1 2024 drivers standing, AF Corse in the
// WEC 2017 LMGTEPro teams standing, and McLaren-Mercedes in the Formula 1 2024 constructors standing.
// Every case changes one thing about one of them.
func (suite *ConnectorUsingClientFunctionalTestSuite) TestValidateStandings() {
	driverRow := `{"driver":{"name":"Max Verstappen","uuid":"01e17884-0f97-4ce1-93b6-4de38b2a2445","shortCode":"VER","colour":null,"picture":"https://content.motorsportstats.com/medium/driverProfilePicture/driverProfilePicture-01e17884-0f97-4ce1-93b6-4de38b2a2445.jpg"},"team":{"name":"Oracle Red Bull Racing","uuid":"42ff413f-91b2-416b-b53f-cbfa77220a8a","colour":"#0600EF","picture":"https://assets.motorsportstats.com/team/icon/teamColour_0600EF.svg","carIcon":"https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_0600EF.svg"},"nationality":{"name":"Netherlands","uuid":"39d8585b-1e74-4280-923d-9d3457ef4f82","picture":"https://assets.motorsportstats.com/flags/svg/nl.svg"},"position":1,"points":437.0,"analytics":{"avgFinishPosition":2.96,"classWins":0,"fastestLaps":3,"finalAppearances":0,"hatTricks":2,"podiums":14,"poles":8,"racesLed":16,"ralliesLed":0,"retirements":1,"semiFinalAppearances":0,"stageWins":0,"starts":24,"top10s":23,"top5s":19,"wins":9,"winsPercentage":37.5}}`
	teamRow := `{"team":{"name":"AF Corse","uuid":"d50b2af7-6fc6-4c96-b8dd-57efa3a45b93","colour":"#CFDBDD","picture":"https://content.motorsportstats.com/medium/teamProfilePicture/teamProfilePicture-d50b2af7-6fc6-4c96-b8dd-57efa3a45b93.jpg","carIcon":"https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_CFDBDD.svg"},"icon":"https://assets.motorsportstats.com/team/icon/teamColour_CFDBDD.svg","countryRepresenting":{"name":"Italy","uuid":"d30d30d2-66b1-4959-b15a-660b3db331ea","picture":"https://assets.motorsportstats.com/flags/svg/it.svg"},"position":1,"points":164.0,"analytics":{"classWins":5,"fastestLaps":0,"finalAppearances":0,"finishes1And2":0,"podiums":0,"poles":0,"qualifies1And2":0,"racesLed":0,"ralliesLed":0,"retirements":0,"semiFinalAppearances":0,"stageWins":0,"starts":9,"top10s":0,"top5s":0,"wins":0,"winsPercentage":0}}`
	constructorRow := `{"constructor":{"name":"McLaren-Mercedes","uuid":"cd967069-65e6-40f6-9cf1-849d3baafa85","picture":null},"team":{"name":"McLaren Formula 1 Team","uuid":"bbdbb738-1b0b-4732-846b-10dc292b2f76","colour":"#FF9800","picture":"https://assets.motorsportstats.com/team/icon/teamColour_FF9800.svg","carIcon":"https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_FF9800.svg"},"countryRepresenting":{"name":"Great Britain","uuid":"5378198e-1d1e-49ef-bbc9-4e43383b4d3e","picture":"https://assets.motorsportstats.com/flags/svg/gb.svg"},"position":1,"points":666.0,"analytics":{"wins":6}}`

	object := func(row map[string]any, key string) map[string]any {
		return row[key].(map[string]any)
	}

	type standingsCase struct {
		name   string
		schema string
		row    string
		change func(row map[string]any)
	}

	valid := []standingsCase{
		{"A driver row as sent", schemaStandingsDrivers, driverRow, func(map[string]any) {}},
		{"An unranked driver", schemaStandingsDrivers, driverRow, func(row map[string]any) { row["position"] = 0 }},
		{"A driver without a team", schemaStandingsDrivers, driverRow, func(row map[string]any) { row["team"] = nil }},
		{"A driver without a nationality", schemaStandingsDrivers, driverRow, func(row map[string]any) { row["nationality"] = nil }},
		{"A driver with a colour", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "driver")["colour"] = "#FF0000" }},
		{"A padded short code", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "driver")["shortCode"] = "DE " }},
		{"A team without a name", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "team")["name"] = "" }},
		{"A team row as sent", schemaStandingsTeams, teamRow, func(map[string]any) {}},
		{"A team without a country", schemaStandingsTeams, teamRow, func(row map[string]any) { row["countryRepresenting"] = nil }},
		{"A team without a picture", schemaStandingsTeams, teamRow, func(row map[string]any) { object(row, "team")["picture"] = nil }},
		{"A constructor row as sent", schemaStandingsConstructors, constructorRow, func(map[string]any) {}},
		{"A constructor without a team", schemaStandingsConstructors, constructorRow, func(row map[string]any) { row["team"] = nil }},
	}

	invalid := []standingsCase{
		{"A lowercase colour", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "team")["colour"] = "#0600ef" }},
		{"A colour without its hash", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "team")["colour"] = "0600EF" }},
		{"A car icon that is not a URL", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "team")["carIcon"] = "caricon_RaceCar_0600EF.svg" }},
		{"A negative position", schemaStandingsDrivers, driverRow, func(row map[string]any) { row["position"] = -1 }},
		{"A negative counter", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "analytics")["wins"] = -1 }},
		{"A fractional counter", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "analytics")["podiums"] = 1.5 }},
		{"A null never observed", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "team")["picture"] = nil }},
		{"An empty uuid", schemaStandingsDrivers, driverRow, func(row map[string]any) { object(row, "driver")["uuid"] = "" }},
		{"An unknown key on a row", schemaStandingsDrivers, driverRow, func(row map[string]any) { row["carNumber"] = "1" }},
		{"A missing counter", schemaStandingsDrivers, driverRow, func(row map[string]any) { delete(object(row, "analytics"), "poles") }},
		{"A team icon that is not a URL", schemaStandingsTeams, teamRow, func(row map[string]any) { row["icon"] = "teamColour_CFDBDD.svg" }},
		{"A driver counter on a team", schemaStandingsTeams, teamRow, func(row map[string]any) { object(row, "analytics")["hatTricks"] = 0 }},
		{"A team counter on a constructor", schemaStandingsConstructors, constructorRow, func(row map[string]any) { object(row, "analytics")["podiums"] = 0 }},
	}

	payload := func(c standingsCase) []byte {
		var row map[string]any
		require.NoError(suite.T(), json.Unmarshal([]byte(c.row), &row))

		c.change(row)

		content, err := json.Marshal(map[string]any{"standings": []any{row}})
		require.NoError(suite.T(), err)

		return content
	}

	for _, c := range valid {
		suite.Run(c.name, func() {
			require.NoError(suite.T(), suite.connector.validate(suite.T().Context(), payload(c), c.schema))
		})
	}

	for _, c := range invalid {
		suite.Run(c.name, func() {
			require.Error(suite.T(), suite.connector.validate(suite.T().Context(), payload(c), c.schema))
		})
	}

	suite.Run("An empty standing", func() {
		require.Error(suite.T(), suite.connector.validate(suite.T().Context(), []byte(`{"standings":[]}`), schemaStandingsDrivers))
	})
}

func TestFunctional_ConnectorUsingClient(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(ConnectorUsingClientFunctionalTestSuite))
}
