package seasons

import (
	"context"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
)

type ScrapSeasonsHandler struct {
}

// NewScrapSeasonsHandler creates a new handler for scrapping seasons.
func NewScrapSeasonsHandler() *ScrapSeasonsHandler {
	handler := &ScrapSeasonsHandler{}

	return handler
}

// Handle processes the scrapping intent for seasons.
func (h *ScrapSeasonsHandler) Handle(ctx context.Context, message messaging.Message) error {
	// Here you would implement the logic to scrap seasons data.
	// For now, we will just log the intent.
	slog.Info("Scrapping seasons intent received", "series", message.Metadata["series"], "season", message.Metadata["season"])

	// Simulate scrapping process
	// In a real implementation, you would fetch data from an API or database.

	return nil
}
