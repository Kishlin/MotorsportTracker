package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type SearchSeriesRepository interface {
	GetSeriesIdentifier(ctx context.Context, keyword string) (ref string, hit bool, err error)
}

type SaveSeasonsRepository interface {
	SaveSeasons(ctx context.Context, series string, seasons []*motorsportstats.Season) error
}

// ScrapeSeasonsUseCase is the use case for scrapping seasons.
type ScrapeSeasonsUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveSeasons        SaveSeasonsRepository
	repoSeriesID           SearchSeriesRepository
}

// NewScrapeSeasonsUseCase creates a new use case for scrapping seasons.
func NewScrapeSeasonsUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveSeasons SaveSeasonsRepository,
	repoSeriesID SearchSeriesRepository,
) *ScrapeSeasonsUseCase {
	return &ScrapeSeasonsUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveSeasons:        repoSaveSeasons,
		repoSeriesID:           repoSeriesID,
	}
}

// Execute scrapes and saves seasons for a given series keyword.
func (u *ScrapeSeasonsUseCase) Execute(ctx context.Context, seriesKeyword string) error {
	if seriesKeyword == "" {
		return fmt.Errorf("series search keyword is required")
	}

	seriesRef, hit, err := u.repoSeriesID.GetSeriesIdentifier(ctx, seriesKeyword)
	if err != nil {
		return fmt.Errorf("getting series keyword identifier: %w", err)
	}
	if !hit {
		slog.Warn("Series identifier not found", "seriesKeyword", seriesKeyword)
		return nil
	}

	seasons, err := u.motorsportStatsGateway.GetSeasons(ctx, seriesRef)
	if err != nil {
		return fmt.Errorf("getting seasons from motorsportstats: %w", err)
	}

	err = u.repoSaveSeasons.SaveSeasons(ctx, seriesRef, seasons)
	if err != nil {
		return fmt.Errorf("saving seasons: %w", err)
	}

	slog.Info("Saved seasons", "series", seriesRef)

	return nil
}
