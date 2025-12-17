package infrastructure

import (
	"context"
	"fmt"
	"log/slog"

	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
)

type ScrapeSeasonsForAllSeriesHandler struct {
	repository *SearchAllSeriesIdentifiersRepository
	queue      *messaging.SQSQueue
}

// NewScrapeSeasonsForAllSeriesHandler creates a new instance of ScrapeSeasonsForAllSeriesHandler.
func NewScrapeSeasonsForAllSeriesHandler(
	repository *SearchAllSeriesIdentifiersRepository,
	queue *messaging.SQSQueue,
) *ScrapeSeasonsForAllSeriesHandler {
	return &ScrapeSeasonsForAllSeriesHandler{
		repository: repository,
		queue:      queue,
	}
}

// Handle processes the scrapping intent for all seasons by fetching all series identifiers
// and sending a scrape:seasons-one intent for each one to the queue.
func (h *ScrapeSeasonsForAllSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	identifiers, err := h.repository.GetAllSeriesIdentifiers(ctx)
	if err != nil {
		return fmt.Errorf("getting all series identifiers: %w", err)
	}

	if len(identifiers) == 0 {
		slog.Warn("No series identifiers found")
		return nil
	}

	intent := NewScrapeSeasonsForSeriesIDIntent()
	for _, identifier := range identifiers {
		message, err := intent.ToMessage([]string{identifier}, map[string]string{})
		if err != nil {
			slog.Error("Failed to create message for series", "identifier", identifier, "error", err)
			continue
		}

		if err := h.queue.Send(message); err != nil {
			slog.Error("Failed to send message for series", "identifier", identifier, "error", err)
			continue
		}
	}

	slog.Info("Sent scrape intents for all series", "count", len(identifiers))

	return nil
}
