package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ScrapeSeasonsForSeriesKeywordHandler struct {
	useCase *domain.ScrapeSeasonsForSeriesKeywordUseCase
}

// NewScrapeSeasonsForSeriesKeywordHandler creates a new instance of ScrapeSeasonsForSeriesKeywordHandler.
func NewScrapeSeasonsForSeriesKeywordHandler(useCase *domain.ScrapeSeasonsForSeriesKeywordUseCase) *ScrapeSeasonsForSeriesKeywordHandler {
	return &ScrapeSeasonsForSeriesKeywordHandler{
		useCase: useCase,
	}
}

// Handle processes the scrapping intent for seasons by keyword.
func (h *ScrapeSeasonsForSeriesKeywordHandler) Handle(ctx context.Context, message messaging.Message) error {
	seriesKeyword, err := messaging.RequireString(message, "series")
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword)
}
