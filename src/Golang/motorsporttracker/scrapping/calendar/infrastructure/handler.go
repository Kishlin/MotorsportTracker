package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
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
	seriesKeyword, err := messaging.RequireString(message, "series")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	year, err := messaging.RequireInt(message, "year")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword, year)
}
