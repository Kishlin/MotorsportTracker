package infrastructure

import (
	"context"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeSeriesHandler struct {
	useCase *domain.ScrapeSeriesUseCase
}

// NewScrapeSeriesHandler creates a new instance of ScrapeSeriesHandler.
func NewScrapeSeriesHandler(useCase *domain.ScrapeSeriesUseCase) *ScrapeSeriesHandler {
	return &ScrapeSeriesHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for series.
func (h *ScrapeSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	return h.useCase.Execute(ctx)
}
