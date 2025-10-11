package events

import (
	"context"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
)

type ScrapEventsHandler struct {
}

// NewScrapEventsHandler creates a new handler for scrapping events.
func NewScrapEventsHandler() *ScrapEventsHandler {
	handler := &ScrapEventsHandler{}

	return handler
}

// Handle processes the scrapping intent for events.
func (h *ScrapEventsHandler) Handle(ctx context.Context, message messaging.Message) error {
	// Here you would implement the logic to scrap events data.
	// For now, we will just log the intent.
	slog.Info("Scrapping events intent received", "series", message.Metadata["series"], "season", message.Metadata["season"])

	// Simulate scrapping process
	// In a real implementation, you would fetch data from an API or database.

	return nil
}
