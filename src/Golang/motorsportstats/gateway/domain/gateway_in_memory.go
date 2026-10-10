package domain

import "context"

// GatewayInMemory is an in-memory implementation of the Gateway interface
type GatewayInMemory struct {
	series               []*Series
	seasons              []*Season
	calendar             *Calendar
	availableStandings   *AvailableStandings
	driverStandings      *DriverStandings
	teamStandings        *TeamStandings
	constructorStandings *ConstructorStandings
	classification       *Classification
}

// NewGatewayInMemory creates a new instance of GatewayInMemory
func NewGatewayInMemory() *GatewayInMemory {
	return &GatewayInMemory{
		series:               []*Series{},
		seasons:              []*Season{},
		calendar:             &Calendar{},
		availableStandings:   &AvailableStandings{},
		driverStandings:      &DriverStandings{},
		teamStandings:        &TeamStandings{},
		constructorStandings: &ConstructorStandings{},
		classification:       &Classification{},
	}
}

// GetSeries retrieves the list of series from the in-memory store
func (g *GatewayInMemory) GetSeries(_ context.Context) ([]*Series, error) {
	return g.series, nil
}

// GetSeasons retrieves the list of seasons from the in-memory store
func (g *GatewayInMemory) GetSeasons(_ context.Context, _ string) ([]*Season, error) {
	return g.seasons, nil
}

// GetCalendar retrieves the calendar from the in-memory store
func (g *GatewayInMemory) GetCalendar(_ context.Context, _ string) (*Calendar, error) {
	return g.calendar, nil
}

// GetAvailableStandings retrieves the available standings from the in-memory store
func (g *GatewayInMemory) GetAvailableStandings(_ context.Context, _ string) (*AvailableStandings, error) {
	return g.availableStandings, nil
}

// GetDriverStandings retrieves the driver standings from the in-memory store
func (g *GatewayInMemory) GetDriverStandings(_ context.Context, _ string, _ string) (*DriverStandings, error) {
	return g.driverStandings, nil
}

// GetTeamStandings retrieves the team standings from the in-memory store
func (g *GatewayInMemory) GetTeamStandings(_ context.Context, _ string, _ string) (*TeamStandings, error) {
	return g.teamStandings, nil
}

// GetConstructorStandings retrieves the constructor standings from the in-memory store
func (g *GatewayInMemory) GetConstructorStandings(_ context.Context, _ string, _ string) (*ConstructorStandings, error) {
	return g.constructorStandings, nil
}

// GetClassification retrieves the classification from the in-memory store
func (g *GatewayInMemory) GetClassification(_ context.Context, _ string) (*Classification, error) {
	return g.classification, nil
}

// SetSeries sets the list of series in the in-memory store
func (g *GatewayInMemory) SetSeries(series []*Series) {
	g.series = series
}

// SetSeasons sets the list of seasons in the in-memory store
func (g *GatewayInMemory) SetSeasons(seasons []*Season) {
	g.seasons = seasons
}

// SetCalendar sets the calendar in the in-memory store
func (g *GatewayInMemory) SetCalendar(calendar *Calendar) {
	g.calendar = calendar
}

// SetAvailableStandings sets the available standings in the in-memory store
func (g *GatewayInMemory) SetAvailableStandings(availableStandings *AvailableStandings) {
	g.availableStandings = availableStandings
}

// SetDriverStandings sets the driver standings in the in-memory store
func (g *GatewayInMemory) SetDriverStandings(standings *DriverStandings) {
	g.driverStandings = standings
}

// SetTeamStandings sets the team standings in the in-memory store
func (g *GatewayInMemory) SetTeamStandings(standings *TeamStandings) {
	g.teamStandings = standings
}

// SetConstructorStandings sets the constructor standings in the in-memory store
func (g *GatewayInMemory) SetConstructorStandings(standings *ConstructorStandings) {
	g.constructorStandings = standings
}

// SetClassification sets the classification in the in-memory store
func (g *GatewayInMemory) SetClassification(classification *Classification) {
	g.classification = classification
}
