package domain

import "context"

// GatewayInMemory is an in-memory implementation of the Gateway interface
type GatewayInMemory struct {
	series  []*Series
	seasons []*Season
}

// NewGatewayInMemory creates a new instance of GatewayInMemory
func NewGatewayInMemory() *GatewayInMemory {
	return &GatewayInMemory{
		series:  []*Series{},
		seasons: []*Season{},
	}
}

// GetSeries retrieves the list of series from the in-memory store
func (g *GatewayInMemory) GetSeries(_ context.Context) ([]*Series, error) {
	return g.series, nil
}

// GetSeasons retrieves the list of seasons for a given series reference from the in-memory store
func (g *GatewayInMemory) GetSeasons(_ context.Context, _ string) ([]*Season, error) {
	return g.seasons, nil
}

// SetSeries sets the list of series in the in-memory store
func (g *GatewayInMemory) SetSeries(series []*Series) {
	g.series = series
}

// SetSeasons sets the list of seasons in the in-memory store
func (g *GatewayInMemory) SetSeasons(seasons []*Season) {
	g.seasons = seasons
}
