package infrastructure

import "context"

type Connector interface {
	GetSeries(ctx context.Context) ([]byte, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]byte, error)

	GetCalendar(ctx context.Context, seasonUUID string) ([]byte, error)
}
