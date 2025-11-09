package infrastructure

import (
	"context"
	"errors"
	"fmt"
	"strconv"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeCalendarHandler struct {
	useCase *domain.ScrapeCalendarUseCase
}

// NewScrapeCalendarHandler creates a new instance of ScrapeCalendarHandler.
func NewScrapeCalendarHandler(useCase *domain.ScrapeCalendarUseCase) *ScrapeCalendarHandler {
	return &ScrapeCalendarHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for calendars.
func (h *ScrapeCalendarHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, year, err := h.paramsFromMessage(message)
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword, year)
}

func (h *ScrapeCalendarHandler) paramsFromMessage(message messaging.Message) (string, int, error) {
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
