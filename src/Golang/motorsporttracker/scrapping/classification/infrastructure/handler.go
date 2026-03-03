package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/classification/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ScrapeClassificationHandler struct {
	useCase *domain.ScrapeClassificationUseCase
}

// NewScrapeClassificationHandler creates a new instance of ScrapeClassificationHandler.
func NewScrapeClassificationHandler(useCase *domain.ScrapeClassificationUseCase) *ScrapeClassificationHandler {
	return &ScrapeClassificationHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for classifications.
func (h *ScrapeClassificationHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, err := messaging.RequireString(message, "series")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	year, err := messaging.RequireInt(message, "year")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	eventKeyword, err := messaging.RequireString(message, "event")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	sessionKeyword, err := messaging.RequireString(message, "session")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword, year, eventKeyword, sessionKeyword)
}
