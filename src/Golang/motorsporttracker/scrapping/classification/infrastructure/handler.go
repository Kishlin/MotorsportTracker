package infrastructure

import (
	"context"
	"errors"
	"fmt"
	"strconv"

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
	seriesKeyword, year, eventKeyword, sessionKeyword, err := h.paramsFromMessage(message)
	if err != nil {
		return fmt.Errorf("getting params from message: %w", err)
	}

	return h.useCase.Execute(ctx, seriesKeyword, year, eventKeyword, sessionKeyword)
}

func (h *ScrapeClassificationHandler) paramsFromMessage(message messaging.Message) (string, int, string, string, error) {
	seriesKeyword, ok := message.Metadata["series"]
	if !ok || seriesKeyword == "" {
		return "", 0, "", "", errors.New("series search keywords is required")
	}

	yearStr, ok := message.Metadata["year"]
	if !ok || yearStr == "" {
		return "", 0, "", "", errors.New("year is required")
	}

	year, err := strconv.Atoi(yearStr)
	if err != nil {
		return "", 0, "", "", errors.New("invalid year format")
	}

	eventKeyword, ok := message.Metadata["event"]
	if !ok || eventKeyword == "" {
		return "", 0, "", "", errors.New("event search keywords is required")
	}

	sessionKeyword, ok := message.Metadata["session"]
	if !ok || sessionKeyword == "" {
		return "", 0, "", "", errors.New("session keyword is required")
	}

	return seriesKeyword, year, eventKeyword, sessionKeyword, nil
}
