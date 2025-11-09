package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeSeasonsHandler struct {
	useCase *domain.ScrapeSeasonsUseCase
}

// NewScrapeSeasonsHandler creates a new instance of ScrapeSeasonsHandler.
func NewScrapeSeasonsHandler(useCase *domain.ScrapeSeasonsUseCase) *ScrapeSeasonsHandler {
	return &ScrapeSeasonsHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for seasons.
func (h *ScrapeSeasonsHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, ok := message.Metadata["series"]
	if !ok || seriesKeyword == "" {
		return fmt.Errorf("series search keywords is required")
	}

	return h.useCase.Execute(ctx, seriesKeyword)
}
