package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type SaveSeriesRepository interface {
	SaveSeries(ctx context.Context, series []*motorsportstats.Series) error
}

type ScrapeSeriesUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	saveSeriesRepository   SaveSeriesRepository
}

// NewScrapeSeriesUseCase creates a new instance of ScrapeSeriesUseCase.
func NewScrapeSeriesUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	saveSeriesRepository SaveSeriesRepository,
) *ScrapeSeriesUseCase {
	return &ScrapeSeriesUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		saveSeriesRepository:   saveSeriesRepository,
	}
}

// Execute scrapes and saves series.
func (u *ScrapeSeriesUseCase) Execute(ctx context.Context) error {
	fetchedSeries, err := u.motorsportStatsGateway.GetSeries(ctx)
	if err != nil {
		return fmt.Errorf("fetching series: %w", err)
	}

	if len(fetchedSeries) == 0 {
		slog.Warn("Gateway returned 0 series, aborting")
		return nil
	}

	err = u.saveSeriesRepository.SaveSeries(ctx, fetchedSeries)
	if err != nil {
		return fmt.Errorf("saving fetched series: %w", err)
	}

	slog.Info("Saved series", "count", len(fetchedSeries))

	return nil
}
