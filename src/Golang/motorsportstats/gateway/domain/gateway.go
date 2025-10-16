package domain

import "context"

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

type Gateway interface {
	GetSeries(ctx context.Context) ([]*Series, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]*Season, error)
}
