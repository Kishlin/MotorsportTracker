package domain

import "context"

// GatewayInMemory is an in-memory implementation of the Gateway interface
type GatewayInMemory struct {
	series         []*Series
	seasons        []*Season
	calendar       *Calendar
	classification *Classification
}

// NewGatewayInMemory creates a new instance of GatewayInMemory
func NewGatewayInMemory() *GatewayInMemory {
	return &GatewayInMemory{
		series:         []*Series{},
		seasons:        []*Season{},
		calendar:       &Calendar{},
		classification: &Classification{},
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

// SetClassification sets the classification in the in-memory store
func (g *GatewayInMemory) SetClassification(classification *Classification) {
	g.classification = classification
}
