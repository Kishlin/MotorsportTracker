package domain

import "context"

type Connector interface {
	GetSeries(ctx context.Context) ([]byte, error)

	GetSeasons(ctx context.Context, seriesUuid string) ([]byte, error)
}
