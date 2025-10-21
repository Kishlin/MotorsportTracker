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

type SearchSeasonsRepository interface {
	GetSeasonIdentifier(ctx context.Context, seriesKeyword string, year int) (ref string, hit bool, err error)
}

type SaveEventsRepository interface {
	SaveCalendar(ctx context.Context, season string, calendar *motorsportstats.Calendar) error
}

// ScrapeEventsHandler is the handler for scrapping events.
type ScrapeEventsHandler struct {
	motorsportStatsGateway motorsportstats.Gateway
	repoSaveEvents         SaveEventsRepository
	repoSeasonsID          SearchSeasonsRepository
}

// NewScrapeEventsHandler creates a new handler for scrapping events.
func NewScrapeEventsHandler(
	motorsportStatsGateway motorsportstats.Gateway,
	repoSaveEvents SaveEventsRepository,
	repoSeasonsID SearchSeasonsRepository,
) *ScrapeEventsHandler {
	return &ScrapeEventsHandler{
		motorsportStatsGateway: motorsportStatsGateway,
		repoSaveEvents:         repoSaveEvents,
		repoSeasonsID:          repoSeasonsID,
	}
}

// Handle handles the scrapping of events.
func (h *ScrapeEventsHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, year, err := h.paramsFromMessage(message)
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	seasonRef, hit, err := h.repoSeasonsID.GetSeasonIdentifier(ctx, seriesKeyword, year)
	if err != nil {
		return fmt.Errorf("getting season identifier: %w", err)
	}
	if hit == false {
		slog.Warn("Season identifier not found", "seriesKeyword", seriesKeyword, "year", year)
		return nil
	}

	calendar, err := h.motorsportStatsGateway.GetCalendar(ctx, seasonRef)
	if err != nil {
		return fmt.Errorf("getting calendar from motorsportstats gateway: %w", err)
	}

	if err := h.repoSaveEvents.SaveCalendar(ctx, seasonRef, calendar); err != nil {
		return fmt.Errorf("saving events: %w", err)
	}

	slog.Info("Saved calendar", "seriesKeyword", seriesKeyword, "year", year)

	return nil
}

func (h *ScrapeEventsHandler) paramsFromMessage(message messaging.Message) (string, int, error) {
	seriesKeyword, ok := message.Metadata["series"]
	if !ok || seriesKeyword == "" {
		return "", 0, errors.New("series search keywords is required")
	}

	yearStr, ok := message.Metadata["year"]
	if !ok || yearStr == "" {
		return "", 0, errors.New("year is required")
	}

	year, err := strconv.Atoi(yearStr)
	if err != nil {
		return "", 0, errors.New("invalid year format")
	}
	return seriesKeyword, year, nil
}
