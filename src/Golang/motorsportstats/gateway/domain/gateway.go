package domain

import (
	"context"
)

type Series struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	ShortName *string `json:"shortName"`
	ShortCode *string `json:"shortCode"`
	Category  *string `json:"category"`
}

type Season struct {
	UUID    string  `json:"uuid"`
	Name    *string `json:"name"`
	Year    *int    `json:"year"`
	EndYear *int    `json:"endYear"`
	Status  *string `json:"status"`
}

type Venue struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	ShortName *string `json:"shortName"`
	ShortCode *string `json:"shortCode"`
}

type Country struct {
	UUID string  `json:"uuid"`
	Name *string `json:"name"`
	Flag *string `json:"picture"`
}

type Session struct {
	UUID       string  `json:"uuid"`
	Name       *string `json:"name"`
	ShortName  *string `json:"shortName"`
	ShortCode  *string `json:"shortCode"`
	Status     *string `json:"status"`
	HasResults *bool   `json:"hasResults"`
	StartTime  *int64  `json:"startTimeUtc"`
	EndTime    *int64  `json:"endTimeUtc"`
}

type Event struct {
	UUID      string     `json:"uuid"`
	Name      *string    `json:"name"`
	ShortName *string    `json:"shortName"`
	ShortCode *string    `json:"shortCode"`
	Status    *string    `json:"status"`
	StartDate *int64     `json:"startDateUtc"`
	EndDate   *int64     `json:"endDateUtc"`
	Venue     *Venue     `json:"venue"`
	Country   *Country   `json:"country"`
	Sessions  []*Session `json:"sessions"`
}

type Calendar struct {
	Events []*Event `json:"events"`
}

type Driver struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	FirstName *string `json:"firstName"`
	LastName  *string `json:"lastName"`
	ShortCode *string `json:"shortCode"`
	Colour    *string `json:"colour"`
	Picture   *string `json:"picture"`
}

type Team struct {
	UUID    string  `json:"uuid"`
	Name    *string `json:"name"`
	Colour  *string `json:"colour"`
	Picture *string `json:"picture"`
	CarIcon *string `json:"carIcon"`
}

type Retirement struct {
	Driver    *Driver `json:"driver"`
	CarNumber string  `json:"carNumber"`
	Reason    *string `json:"reason"`
	Type      *string `json:"type"`
	DNS       *bool   `json:"dns"`
	Lap       *int    `json:"lap"`
	Details   *string `json:"details"`
}

type ClassificationGap struct {
	TimeToLead *float64 `json:"timeToLead"`
	TimeToNext *float64 `json:"timeToNext"`
	LapsToLead *int     `json:"lapsToLead"`
	LapsToNext *int     `json:"lapsToNext"`
}

type ClassificationBest struct {
	Lap     *int     `json:"lap"`
	Time    *float64 `json:"time"`
	Fastest *bool    `json:"fastest"`
	Speed   *float64 `json:"speed"`
}

type ClassificationDetail struct {
	CarNumber          string             `json:"carNumber"`
	FinishPosition     *int               `json:"finishPosition"`
	GridPosition       *int               `json:"gridPosition"`
	Drivers            []*Driver          `json:"drivers"`
	Team               *Team              `json:"team"`
	Nationality        *Country           `json:"nationality"`
	Laps               *int               `json:"laps"`
	Points             *float64           `json:"points"`
	Time               *float64           `json:"time"`
	ClassifiedStatus   *string            `json:"classifiedStatus"`
	AvgLapSpeed        *float64           `json:"avgLapSpeed"`
	FastestLapTime     *float64           `json:"fastestLapTime"`
	ClassificationGap  ClassificationGap  `json:"gap"`
	ClassificationBest ClassificationBest `json:"best"`
}

type Classification struct {
	Details     []*ClassificationDetail `json:"details"`
	Retirements []*Retirement           `json:"retirements"`
}

type Gateway interface {
	GetSeries(ctx context.Context) ([]*Series, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]*Season, error)

	GetCalendar(ctx context.Context, seasonUUID string) (*Calendar, error)

	GetClassification(ctx context.Context, sessionUUID string) (*Classification, error)
}
