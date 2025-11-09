package domain

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type SearchSessionRepository interface {
	GetSessionIdentifier(ctx context.Context, seriesKeyword string, year int, eventKeyword string, sessionKeyword string) (ref string, hit bool, err error)
}

type SaveClassificationRepository interface {
	SaveClassification(ctx context.Context, session string, classification *motorsportstats.Classification) error
}

// ScrapeClassificationUseCase is the use case for scrapping classifications
type ScrapeClassificationUseCase struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSearchSession      SearchSessionRepository
	repoSaveClassification SaveClassificationRepository
}

// NewScrapeClassificationUseCase creates a new use case for scrapping classifications
func NewScrapeClassificationUseCase(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSearchSession SearchSessionRepository,
	repoSaveClassification SaveClassificationRepository,
) *ScrapeClassificationUseCase {
	return &ScrapeClassificationUseCase{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSearchSession:      repoSearchSession,
		repoSaveClassification: repoSaveClassification,
	}
}

// Execute scrapes and saves classification for a given session.
func (u *ScrapeClassificationUseCase) Execute(ctx context.Context, seriesKeyword string, year int, eventKeyword string, sessionKeyword string) error {
	sessionRef, hit, err := u.repoSearchSession.GetSessionIdentifier(ctx, seriesKeyword, year, eventKeyword, sessionKeyword)
	if err != nil {
		return fmt.Errorf("getting session identifier: %w", err)
	}
	if !hit {
		slog.Warn(
			"Session identifier not found",
			slog.String("seriesKeyword", seriesKeyword),
			slog.Int("year", year),
			slog.String("eventKeyword", eventKeyword),
			slog.String("sessionKeyword", sessionKeyword),
		)
		return nil
	}

	classification, err := u.motorsportStatsGateway.GetClassification(ctx, sessionRef)
	if err != nil {
		return fmt.Errorf("getting classification from gateway: %w", err)
	}

	err = u.repoSaveClassification.SaveClassification(ctx, sessionRef, classification)
	if err != nil {
		return fmt.Errorf("saving classification: %w", err)
	}

	slog.Info(
		"Saved classification",
		slog.String("seriesKeyword", seriesKeyword),
		slog.Int("year", year),
		slog.String("eventKeyword", eventKeyword),
		slog.String("sessionKeyword", sessionKeyword),
	)

	return nil
}
