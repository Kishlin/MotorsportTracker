package infrastructure

import (
	"context"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ScrapeSeasonsForAllSeriesHandler struct {
	useCase *domain.ScrapeSeasonsForAllSeriesUseCase
}

func NewScrapeSeasonsForAllSeriesHandler(
	useCase *domain.ScrapeSeasonsForAllSeriesUseCase,
) *ScrapeSeasonsForAllSeriesHandler {
	return &ScrapeSeasonsForAllSeriesHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for all seasons by fetching all series identifiers
// and sending a scrape:seasons-one intent for each one to the queue.
func (h *ScrapeSeasonsForAllSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	return h.useCase.Execute(ctx)
}
