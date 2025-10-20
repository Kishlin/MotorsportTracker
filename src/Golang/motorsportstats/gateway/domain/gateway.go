package domain

import (
	"context"
)

type Series struct {
	UUID      string  `json:"uuid"`
	Name      string  `json:"name"`
	ShortName *string `json:"shortName"`
	ShortCode string  `json:"shortCode"`
	Category  string  `json:"category"`
}

type Season struct {
	UUID    string `json:"uuid"`
	Name    string `json:"name"`
	Year    int    `json:"year"`
	EndYear int    `json:"endYear"`
	Status  string `json:"status"`
}

type Venue struct {
	UUID      string `json:"uuid"`
	Name      string `json:"name"`
	ShortName string `json:"shortName"`
	ShortCode string `json:"shortCode"`
}

type Country struct {
	UUID string `json:"uuid"`
	Name string `json:"name"`
	Flag string `json:"flag"`
}

type Session struct {
	UUID       string `json:"uuid"`
	Name       string `json:"name"`
	ShortName  string `json:"shortName"`
	ShortCode  string `json:"shortCode"`
	Status     string `json:"status"`
	HasResults bool   `json:"hasResults"`
	StartTime  int    `json:"startTimeUtc"`
	EndTime    *int   `json:"endTimeUtc"`
}

type Event struct {
	UUID      string     `json:"uuid"`
	Name      string     `json:"name"`
	ShortName string     `json:"shortName"`
	ShortCode string     `json:"shortCode"`
	Status    string     `json:"status"`
	StartDate int        `json:"startDateUtc"`
	EndDate   int        `json:"endDateUtc"`
	Venue     *Venue     `json:"venue"`
	Country   *Country   `json:"country"`
	Sessions  []*Session `json:"sessions"`
}

type Calendar struct {
	Events []*Event `json:"events"`
}

type Gateway interface {
	GetSeries(ctx context.Context) ([]*Series, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]*Season, error)

	GetCalendar(ctx context.Context, seasonUUID string) (*Calendar, error)
}
