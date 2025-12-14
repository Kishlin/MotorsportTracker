package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ScrapeSeasonsForSeriesIDHandler struct {
	useCase *domain.ScrapeSeasonsForSeriesIdentifierUseCase
}

// NewScrapeSeasonsForSeriesIDHandler creates a new instance of ScrapeSeasonsForSeriesIDHandler.
func NewScrapeSeasonsForSeriesIDHandler(useCase *domain.ScrapeSeasonsForSeriesIdentifierUseCase) *ScrapeSeasonsForSeriesIDHandler {
	return &ScrapeSeasonsForSeriesIDHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for a single season by identifier.
func (h *ScrapeSeasonsForSeriesIDHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesIdentifier, ok := message.Metadata["identifier"]
	if !ok || seriesIdentifier == "" {
		return fmt.Errorf("series identifier is required")
	}

	return h.useCase.Execute(ctx, seriesIdentifier)
}
