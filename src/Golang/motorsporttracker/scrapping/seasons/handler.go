package seasons

import (
	"context"
	"encoding/json"
	"fmt"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/database"
)

type ScrapSeasonsHandler struct {
	db        database.Database
	connector connector.Connector

	repositoryWriteSeasons    *RepositoryWriteSeasons
	repositorySearchSeriesRef *SearchSeriesRefRepository
}

// NewScrapSeasonsHandler creates a new handler for scrapping seasons.
func NewScrapSeasonsHandler(db database.Database, connector connector.Connector) *ScrapSeasonsHandler {
	handler := &ScrapSeasonsHandler{
		repositorySearchSeriesRef: NewSearchSeriesRefRepository(db),
		repositoryWriteSeasons:    NewRepositoryWriteSeasons(db),

		connector: connector,
		db:        db,
	}

	return handler
}

// Handle processes the scrapping intent for seasons.
func (h *ScrapSeasonsHandler) Handle(ctx context.Context, message messaging.Message) error {
	ref, err := h.repositorySearchSeriesRef.Search(ctx, message.Metadata["series"])
	if err != nil {
		return fmt.Errorf("getting series ref: %w", err)
	}

	resp, err := h.connector.Get(fmt.Sprintf(endpointSeasons, ref))
	if err != nil {
		return fmt.Errorf("fetching seasons data: %w", err)
	}

	// Validate the response content
	if err := scrapping.Validate(ctx, resp, schemaSeasons); err != nil {
		return fmt.Errorf("validating seasons data: %w", err)
	}

	// Unmarshal the response into a slice of Seasons
	var seasonsList []Season
	err = json.Unmarshal(resp, &seasonsList)
	if err != nil {
		return fmt.Errorf("unmarshalling seasons data: %w", err)
	}

	err = h.repositoryWriteSeasons.Write(ctx, seasonsList)
	if err != nil {
		return fmt.Errorf("writing seasons data: %w", err)
	}

	slog.Info("wrote seasons data", "count", len(seasonsList), "for_series_ref", ref, "series")

	return nil
}

const endpointSeasons = "https://api.motorsportstats.com/widgets/1.0.0/series/%s/seasons"

const schemaSeasons = `{
	"type": "array",
	"items": {
		"type": "object",
		"properties": {
			"name": {
				"type": "string"
			},
			"uuid": {
				"type": "string"
			},
			"year": {
				"type": "integer"
			},
			"endYear": {
				"type": "integer"
			},
			"status": {
				"type": "string"
			}
		},
		"required": ["name", "uuid", "year", "endYear", "status"]
	}
}`
