package infrastructure

import (
	"context"
	"encoding/json"
	"fmt"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type GatewayUsingConnector struct {
	connector connector.Connector
}

// NewGatewayUsingConnector creates a new GatewayUsingConnector with the provided connector.
func NewGatewayUsingConnector(connector connector.Connector) *GatewayUsingConnector {
	return &GatewayUsingConnector{
		connector: connector,
	}
}

func (g GatewayUsingConnector) GetSeries(ctx context.Context) ([]*domain.Series, error) {
	data, err := g.connector.GetSeries(ctx)
	if err != nil {
		return nil, fmt.Errorf("getting series from connector: %w", err)
	}

	var seriesList []*domain.Series
	err = json.Unmarshal(data, &seriesList)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling series data: %w", err)
	}

	return seriesList, nil
}

func (g GatewayUsingConnector) GetSeasons(ctx context.Context, seriesUUID string) ([]*domain.Season, error) {
	data, err := g.connector.GetSeasons(ctx, seriesUUID)
	if err != nil {
		return nil, fmt.Errorf("getting seasons from connector: %w", err)
	}

	var seasonsList []*domain.Season
	err = json.Unmarshal(data, &seasonsList)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling seasons data: %w", err)
	}

	return seasonsList, nil
}

func (g GatewayUsingConnector) GetCalendar(ctx context.Context, seasonUUID string) (*domain.Calendar, error) {
	data, err := g.connector.GetCalendar(ctx, seasonUUID)
	if err != nil {
		return nil, fmt.Errorf("getting calendar from connector: %w", err)
	}

	var calendar *domain.Calendar
	err = json.Unmarshal(data, &calendar)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling calendar data: %w", err)
	}

	return calendar, nil
}

func (g GatewayUsingConnector) GetAvailableStandings(ctx context.Context, seasonUUID string) (*domain.AvailableStandings, error) {
	data, err := g.connector.GetAvailableStandings(ctx, seasonUUID)
	if err != nil {
		return nil, fmt.Errorf("getting available standings from connector: %w", err)
	}

	var availableStandings *domain.AvailableStandings
	err = json.Unmarshal(data, &availableStandings)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling available standings data: %w", err)
	}

	return availableStandings, nil
}

func (g GatewayUsingConnector) GetDriverStandings(ctx context.Context, seasonUUID string, classUUID string) (*domain.DriverStandings, error) {
	data, err := g.connector.GetStandings(ctx, seasonUUID, connector.StandingDrivers, classUUID)
	if err != nil {
		return nil, fmt.Errorf("getting driver standings from connector: %w", err)
	}

	var standings *domain.DriverStandings
	err = json.Unmarshal(data, &standings)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling driver standings data: %w", err)
	}

	return standings, nil
}

func (g GatewayUsingConnector) GetTeamStandings(ctx context.Context, seasonUUID string, classUUID string) (*domain.TeamStandings, error) {
	data, err := g.connector.GetStandings(ctx, seasonUUID, connector.StandingTeams, classUUID)
	if err != nil {
		return nil, fmt.Errorf("getting team standings from connector: %w", err)
	}

	var standings *domain.TeamStandings
	err = json.Unmarshal(data, &standings)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling team standings data: %w", err)
	}

	return standings, nil
}

func (g GatewayUsingConnector) GetConstructorStandings(ctx context.Context, seasonUUID string, classUUID string) (*domain.ConstructorStandings, error) {
	data, err := g.connector.GetStandings(ctx, seasonUUID, connector.StandingConstructors, classUUID)
	if err != nil {
		return nil, fmt.Errorf("getting constructor standings from connector: %w", err)
	}

	var standings *domain.ConstructorStandings
	err = json.Unmarshal(data, &standings)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling constructor standings data: %w", err)
	}

	return standings, nil
}

func (g GatewayUsingConnector) GetClassification(ctx context.Context, sessionUUID string) (*domain.Classification, error) {
	data, err := g.connector.GetClassification(ctx, sessionUUID)
	if err != nil {
		return nil, fmt.Errorf("getting classification from connector: %w", err)
	}

	var classification *domain.Classification
	err = json.Unmarshal(data, &classification)
	if err != nil {
		return nil, fmt.Errorf("unmarshalling classification data: %w", err)
	}

	return classification, nil
}
