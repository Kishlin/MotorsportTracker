package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type SearchSeasonsRepository interface {
	GetSeasonIdentifier(ctx context.Context, seriesKeyword string, year int) (ref string, hit bool, err error)
}

type SaveEventsRepository interface {
	SaveCalendar(ctx context.Context, season string, calendar *motorsportstats.Calendar) error
}

// ScrapeEventsUseCase is the use case for scrapping events.
type ScrapeEventsUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveEvents         SaveEventsRepository
	repoSeasonsID          SearchSeasonsRepository
}

// NewScrapeEventsUseCase creates a new use case for scrapping events.
func NewScrapeEventsUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveEvents SaveEventsRepository,
	repoSeasonsID SearchSeasonsRepository,
) *ScrapeEventsUseCase {
	return &ScrapeEventsUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveEvents:         repoSaveEvents,
		repoSeasonsID:          repoSeasonsID,
	}
}

// Execute scrapes and saves events for a given season.
func (u *ScrapeEventsUseCase) Execute(ctx context.Context, seriesKeyword string, year int) error {
	seasonRef, hit, err := u.repoSeasonsID.GetSeasonIdentifier(ctx, seriesKeyword, year)
	if err != nil {
		return fmt.Errorf("getting season identifier: %w", err)
	}
	if !hit {
		slog.Warn("Season identifier not found", slog.String("seriesKeyword", seriesKeyword), slog.Int("year", year))
		return nil
	}

	calendar, err := u.motorsportStatsGateway.GetCalendar(ctx, seasonRef)
	if err != nil {
		return fmt.Errorf("getting calendar from motorsportstats gateway: %w", err)
	}

	if err := u.repoSaveEvents.SaveCalendar(ctx, seasonRef, calendar); err != nil {
		return fmt.Errorf("saving events: %w", err)
	}

	slog.Info("Saved calendar", slog.String("seriesKeyword", seriesKeyword), slog.Int("year", year))

	return nil
}
