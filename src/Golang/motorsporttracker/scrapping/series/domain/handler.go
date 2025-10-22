package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type SaveSeriesRepository interface {
	SaveSeries(ctx context.Context, series []*motorsportstats.Series) error
}

type ScrapeSeriesHandler struct {
	motorsportStatsGateway motorsportstats.Gateway
	saveSeriesRepository   SaveSeriesRepository
}

// NewScrapeSeriesHandler creates a new instance of ScrapeSeriesHandler.
func NewScrapeSeriesHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	saveSeriesRepository SaveSeriesRepository,
) *ScrapeSeriesHandler {
	return &ScrapeSeriesHandler{
		motorsportStatsGateway: motorsportStatsGateway,
		saveSeriesRepository:   saveSeriesRepository,
	}
}

// Handle processes the scrapping intent for series.
func (h *ScrapeSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	fetchedSeries, err := h.motorsportStatsGateway.GetSeries(ctx)
	if err != nil {
		return fmt.Errorf("fetching series fetchedSeries: %w", err)
	}

	if len(fetchedSeries) == 0 {
		slog.Warn("Gateway returned 0 series, aborting")
		return nil
	}

	err = h.saveSeriesRepository.SaveSeries(ctx, fetchedSeries)
	if err != nil {
		return fmt.Errorf("saving fetched series: %w", err)
	}

	slog.Info("Saved series", "count", len(fetchedSeries))

	return nil
}
