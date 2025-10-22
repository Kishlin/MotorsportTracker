package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type SearchSeriesRepository interface {
	GetSeriesIdentifier(ctx context.Context, keyword string) (ref string, hit bool, err error)
}

type SaveSeasonsRepository interface {
	SaveSeasons(ctx context.Context, series string, seasons []*motorsportstats.Season) error
}

// ScrapeSeasonsHandler is the handler for scrapping series.
type ScrapeSeasonsHandler struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveSeasons        SaveSeasonsRepository
	repoSeriesID           SearchSeriesRepository
}

// NewScrapeSeasonsHandler creates a new handler for scrapping series.
func NewScrapeSeasonsHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveSeasons SaveSeasonsRepository,
	repoSeriesID SearchSeriesRepository,
) *ScrapeSeasonsHandler {
	return &ScrapeSeasonsHandler{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveSeasons:        repoSaveSeasons,
		repoSeriesID:           repoSeriesID,
	}
}

// Handle handles the scrapping of series.
func (h *ScrapeSeasonsHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, ok := message.Metadata["series"]
	if !ok || seriesKeyword == "" {
		return fmt.Errorf("series search keywords is required")
	}

	seriesRef, hit, err := h.repoSeriesID.GetSeriesIdentifier(ctx, seriesKeyword)
	if err != nil {
		return fmt.Errorf("getting series keyword identifier: %w", err)
	}
	if hit == false {
		slog.Warn("Series identifier not found", "seriesKeyword", seriesKeyword)
		return nil
	}

	seasons, err := h.motorsportStatsGateway.GetSeasons(ctx, seriesRef)
	if err != nil {
		return fmt.Errorf("getting seasons from motorsportstats: %w", err)
	}

	err = h.repoSaveSeasons.SaveSeasons(ctx, seriesRef, seasons)
	if err != nil {
		return fmt.Errorf("saving seasons: %w", err)
	}

	slog.Info("Saved seasons", "series", seriesRef)

	return nil
}
