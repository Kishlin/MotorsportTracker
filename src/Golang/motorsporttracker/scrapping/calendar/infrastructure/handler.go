package infrastructure

import (
	"context"
	"errors"
	"fmt"
	"strconv"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeEventsHandler struct {
	useCase *domain.ScrapeEventsUseCase
}

// NewScrapeEventsHandler creates a new instance of ScrapeEventsHandler.
func NewScrapeEventsHandler(useCase *domain.ScrapeEventsUseCase) *ScrapeEventsHandler {
	return &ScrapeEventsHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for events.
func (h *ScrapeEventsHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, year, err := h.paramsFromMessage(message)
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword, year)
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
