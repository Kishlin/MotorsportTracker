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

// ScrapeSeasonsForSeriesKeywordUseCase is the use case for scrapping seasons for a series identified by a keyword.
type ScrapeSeasonsForSeriesKeywordUseCase struct {
	scrapeSeasonsForSeriesIdentifierUseCase *ScrapeSeasonsForSeriesIdentifierUseCase
	repoSeriesID                            SearchSeriesRepository
}

// NewScrapeSeasonsForSeriesKeywordUseCase creates a new use case for scrapping seasons by keyword.
func NewScrapeSeasonsForSeriesKeywordUseCase(
	scrapeSeasonsForSeriesIdentifierUseCase *ScrapeSeasonsForSeriesIdentifierUseCase,
	repoSeriesID SearchSeriesRepository,
) *ScrapeSeasonsForSeriesKeywordUseCase {
	return &ScrapeSeasonsForSeriesKeywordUseCase{
		scrapeSeasonsForSeriesIdentifierUseCase: scrapeSeasonsForSeriesIdentifierUseCase,
		repoSeriesID:                            repoSeriesID,
	}
}

// Execute scrapes and saves seasons for a given series keyword.
func (u *ScrapeSeasonsForSeriesKeywordUseCase) Execute(ctx context.Context, seriesKeyword string) error {
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

	return u.scrapeSeasonsForSeriesIdentifierUseCase.Execute(ctx, seriesRef)
}
