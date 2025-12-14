package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

// ScrapeSeasonsForSeriesIdentifierUseCase is the use case for scrapping seasons for a single series identifier.
type ScrapeSeasonsForSeriesIdentifierUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveSeasons        SaveSeasonsRepository
}

// NewScrapeSeasonsForSeriesIdentifierUseCase creates a new use case for scrapping seasons for a single series.
func NewScrapeSeasonsForSeriesIdentifierUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveSeasons SaveSeasonsRepository,
) *ScrapeSeasonsForSeriesIdentifierUseCase {
	return &ScrapeSeasonsForSeriesIdentifierUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveSeasons:        repoSaveSeasons,
	}
}

// Execute scrapes and saves seasons for a given series identifier.
func (u *ScrapeSeasonsForSeriesIdentifierUseCase) Execute(ctx context.Context, seriesIdentifier string) error {
	if seriesIdentifier == "" {
		return fmt.Errorf("series identifier is required")
	}

	seasons, err := u.motorsportStatsGateway.GetSeasons(ctx, seriesIdentifier)
	if err != nil {
		return fmt.Errorf("getting seasons from motorsportstats: %w", err)
	}

	err = u.repoSaveSeasons.SaveSeasons(ctx, seriesIdentifier, seasons)
	if err != nil {
		return fmt.Errorf("saving seasons: %w", err)
	}

	slog.Info("Saved seasons", "series", seriesIdentifier)

	return nil
}
