package domain

import (
	"context"
	"errors"
	"fmt"
	"log/slog"
	"strconv"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type SearchSessionRepository interface {
	GetSessionIdentifier(ctx context.Context, seriesKeyword string, year int, eventKeyword string, sessionKeyword string) (ref string, hit bool, err error)
}

type SaveClassificationRepository interface {
	SaveClassification(ctx context.Context, session string, classification *motorsportstats.Classification) error
}

// ScrapeClassificationHandler is the handler for scrapping classifications
type ScrapeClassificationHandler struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSearchSession      SearchSessionRepository
	repoSaveClassification SaveClassificationRepository
}

// NewScrapeClassificationHandler creates a new handler for scrapping classifications
func NewScrapeClassificationHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSearchSession SearchSessionRepository,
	repoSaveClassification SaveClassificationRepository,
) *ScrapeClassificationHandler {
	return &ScrapeClassificationHandler{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSearchSession:      repoSearchSession,
		repoSaveClassification: repoSaveClassification,
	}
}

// Handle handles the scrapping of classifications
func (h *ScrapeClassificationHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, year, eventKeyword, sessionKeyword, err := h.paramsFromMessage(message)
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	sessionRef, hit, err := h.repoSearchSession.GetSessionIdentifier(ctx, seriesKeyword, year, eventKeyword, sessionKeyword)
	if err != nil {
		return fmt.Errorf("getting session identifier: %w", err)
	}
	if hit == false {
		slog.Warn(
			"Session identifier not found",
			slog.String("seriesKeyword", seriesKeyword),
			slog.Int("year", year),
			slog.String("eventKeyword", eventKeyword),
			slog.String("sessionKeyword", sessionKeyword),
		)
	}

	classification, err := h.motorsportStatsGateway.GetClassification(ctx, sessionRef)
	if err != nil {
		return fmt.Errorf("getting classification from gateway: %w", err)
	}

	err = h.repoSaveClassification.SaveClassification(ctx, sessionRef, classification)
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

func (h *ScrapeClassificationHandler) paramsFromMessage(message messaging.Message) (string, int, string, string, error) {
	seriesKeyword, ok := message.Metadata["series"]
	if ok == false || seriesKeyword == "" {
		return "", 0, "", "", errors.New("series search keywords is required")
	}

	yearStr, ok := message.Metadata["year"]
	if ok == false || yearStr == "" {
		return "", 0, "", "", errors.New("year is required")
	}

	year, err := strconv.Atoi(yearStr)
	if err != nil {
		return "", 0, "", "", errors.New("invalid year format")
	}

	eventKeyword, ok := message.Metadata["event"]
	if ok == false || eventKeyword == "" {
		return "", 0, "", "", errors.New("event search keywords is required")
	}

	sessionKeyword, ok := message.Metadata["session"]
	if ok == false || sessionKeyword == "" {
		return "", 0, "", "", errors.New("session keyword is required")
	}

	return seriesKeyword, year, eventKeyword, sessionKeyword, nil
}
