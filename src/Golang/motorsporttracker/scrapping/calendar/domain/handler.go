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

type SaveCalendarRepository interface {
	SaveCalendar(ctx context.Context, season string, calendar *motorsportstats.Calendar) error
}

// ScrapeCalendarUseCase is the use case for scrapping calendars.
type ScrapeCalendarUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveCalendar       SaveCalendarRepository
	repoSeasonsID          SearchSeasonsRepository
}

// NewScrapeCalendarUseCase creates a new use case for scrapping calendars.
func NewScrapeCalendarUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveCalendar SaveCalendarRepository,
	repoSeasonsID SearchSeasonsRepository,
) *ScrapeCalendarUseCase {
	return &ScrapeCalendarUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveCalendar:       repoSaveCalendar,
		repoSeasonsID:          repoSeasonsID,
	}
}

// Execute scrapes and saves a calendar for a given season.
func (u *ScrapeCalendarUseCase) Execute(ctx context.Context, seriesKeyword string, year int) error {
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

	if err := u.repoSaveCalendar.SaveCalendar(ctx, seasonRef, calendar); err != nil {
		return fmt.Errorf("saving calendar: %w", err)
	}

	slog.Info("Saved calendar", slog.String("seriesKeyword", seriesKeyword), slog.Int("year", year))

	return nil
}
