package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type Season motorsportstats.Season

type SearchSeriesRepository interface {
	GetSeriesIdentifier(ctx context.Context, keyword string) (ref string, hit bool, err error)
}

type ExistingSeasonsRepository interface {
	GetExistingSeasons(ctx context.Context, series string) (map[string]bool, error)
}

type SaveSeasonsRepository interface {
	SaveSeasons(ctx context.Context, series string, seasons []*Season) error
}

// ScrapeSeasonsHandler is the handler for scrapping series.
type ScrapeSeasonsHandler struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoExistingSeasons    ExistingSeasonsRepository
	repoSaveSeasons        SaveSeasonsRepository
	repoSeriesId           SearchSeriesRepository
}

// NewScrapeSeasonsHandler creates a new handler for scrapping series.
func NewScrapeSeasonsHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	repoExistingSeasons ExistingSeasonsRepository,
	repoSaveSeasons SaveSeasonsRepository,
	repoSeriesId SearchSeriesRepository,
) *ScrapeSeasonsHandler {
	return &ScrapeSeasonsHandler{
		motorsportStatsGateway: motorsportStatsGateway,
		repoExistingSeasons:    repoExistingSeasons,
		repoSaveSeasons:        repoSaveSeasons,
		repoSeriesId:           repoSeriesId,
	}
}

// Handle handles the scrapping of series.
func (h *ScrapeSeasonsHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, ok := message.Metadata["series"]
	if !ok || seriesKeyword == "" {
		return fmt.Errorf("series search keywords is required")
	}

	seriesRef, hit, err := h.repoSeriesId.GetSeriesIdentifier(ctx, seriesKeyword)
	if err != nil {
		return fmt.Errorf("getting series keyword identifier: %w", err)
	}
	if !hit {
		slog.Warn("Series identifier not found", "seriesKeyword", seriesKeyword)
		return nil
	}

	existingSeasons, err := h.repoExistingSeasons.GetExistingSeasons(ctx, seriesRef)
	if err != nil {
		return fmt.Errorf("getting existing seasons: %w", err)
	}

	seasons, err := h.motorsportStatsGateway.GetSeasons(ctx, seriesRef)
	if err != nil {
		return fmt.Errorf("getting seasons from motorsportstats: %w", err)
	}

	newSeasons := h.filterNewSeasons(seasons, existingSeasons)
	if len(newSeasons) == 0 {
		slog.Info("No new seasons to insert", "for_series_ref", seriesRef, "seriesKeyword", seriesKeyword)
		return nil
	}

	if len(newSeasons) == 0 {
		slog.Info("No new seasons to insert", "for_series_ref", seriesRef, "seriesKeyword", seriesKeyword)
		return nil
	}

	err = h.repoSaveSeasons.SaveSeasons(ctx, seriesRef, newSeasons)
	if err != nil {
		return fmt.Errorf("saving seasons: %w", err)
	}

	slog.Info("Wrote seasons data", "count", len(newSeasons), "for_series_ref", seriesRef, "seriesKeyword", seriesKeyword)

	return nil
}

func (h *ScrapeSeasonsHandler) filterNewSeasons(fetchedSeasons []*motorsportstats.Season, existingSeasons map[string]bool) []*Season {
	var toInsert []*Season

	for _, season := range fetchedSeasons {
		if _, exists := existingSeasons[season.UUID]; exists {
			slog.Debug("Existing season", "season_name", season.Name)
			continue
		}

		toInsert = append(toInsert, (*Season)(season))
	}

	return toInsert
}
