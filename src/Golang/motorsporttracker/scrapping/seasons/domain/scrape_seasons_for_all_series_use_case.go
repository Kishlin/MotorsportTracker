package domain

import (
	"context"
	"fmt"
	"log/slog"
)

type SearchAllSeriesIdentifiersRepository interface {
	GetAllSeriesIdentifiers(ctx context.Context) ([]string, error)
}

type SeasonsScrapper interface {
	ScrapeSeasonsForSeries(ctx context.Context, seriesID string) error
}

type ScrapeSeasonsForAllSeriesUseCase struct {
	repoSearchAllSeries SearchAllSeriesIdentifiersRepository
	scrapper            SeasonsScrapper
}

func NewScrapeSeasonsForAllSeriesUseCase(
	repoSearchAllSeries SearchAllSeriesIdentifiersRepository,
	scrapper SeasonsScrapper,
) *ScrapeSeasonsForAllSeriesUseCase {
	return &ScrapeSeasonsForAllSeriesUseCase{
		repoSearchAllSeries: repoSearchAllSeries,
		scrapper:            scrapper,
	}
}

func (u *ScrapeSeasonsForAllSeriesUseCase) Execute(ctx context.Context) error {
	identifiers, err := u.repoSearchAllSeries.GetAllSeriesIdentifiers(ctx)
	if err != nil {
		return fmt.Errorf("getting all series identifiers: %w", err)
	}

	if len(identifiers) == 0 {
		slog.Warn("No series identifiers found")
		return nil
	}

	scrapped := 0
	for _, identifier := range identifiers {
		err := u.scrapper.ScrapeSeasonsForSeries(ctx, identifier)
		if err == nil {
			scrapped++
			continue
		}

		slog.Error("Failed to scrape seasons for series", "identifier", identifier, "error", err)
	}

	slog.Info("Scraped seasons for available series",
		slog.Int("found", len(identifiers)),
		slog.Int("scrapped", scrapped),
	)

	return nil
}
