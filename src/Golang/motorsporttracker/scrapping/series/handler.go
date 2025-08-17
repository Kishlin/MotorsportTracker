package series

import (
	"context"
	"encoding/json"
	"fmt"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
)

type ScrapSeriesHandler struct {
	db         database.Database
	connector  connector.Connector
	repository *FindSeriesRepository
}

// NewScrapSeriesHandler creates a new handler for scrapping series.
func NewScrapSeriesHandler(db database.Database, connector connector.Connector) *ScrapSeriesHandler {
	return &ScrapSeriesHandler{
		repository: NewFindSeriesRepository(db),
		connector:  connector,
		db:         db,
	}
}

// Handle processes the scrapping intent for series.
func (h *ScrapSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	resp, err := h.connector.Get(endpointSeries)
	if err != nil {
		return fmt.Errorf("fetching series data: %w", err)
	}

	// Validate the response content
	if err := scrapping.Validate(ctx, resp, schemaSeries); err != nil {
		return fmt.Errorf("validating series data: %w", err)
	}

	// Unmarshal the response into a slice of Series
	var seriesList []Series
	err = json.Unmarshal(resp, &seriesList)
	if err != nil {
		return fmt.Errorf("unmarshalling series data: %w", err)
	}

	// Get existing series from the database for comparison
	existingSeries, err := h.repository.FindAll(ctx)
	if err != nil {
		return fmt.Errorf("fetching existing series from database: %w", err)
	}

	newSeriesCount := 0
	existingSeriesCount := 0
	warningCount := 0

	for _, series := range seriesList {
		if existingData, exists := existingSeries[series.ExternalUUID]; exists {
			existingSeriesCount++
			slog.Debug("Series already exists", "name", series.Name, "external_uuid", series.ExternalUUID)

			// Compare data and log warnings if different
			if series.IsEqualTo(existingData) == false {
				warningCount++
				slog.Warn("Series data differs", "name", series.Name, "external_uuid", series.ExternalUUID)
				h.logSeriesDifferences(series, existingData)
			}
		} else {
			// Insert new series into the database
			err = h.db.Exec(ctx, `
				INSERT INTO series (name, external_uuid, short_name, short_code, category)
				VALUES ($1, $2, $3, $4, $5)`,
				series.Name, series.ExternalUUID, series.ShortName, series.ShortCode, series.Category)

			if err != nil {
				return fmt.Errorf("inserting series into database: %w", err)
			}

			newSeriesCount++
			slog.Info("Inserted new series", "name", series.Name, "external_uuid", series.ExternalUUID)
		}
	}

	slog.Info(
		"Series scrapping completed",
		"new_series_count",
		newSeriesCount,
		"existing_series_count",
		existingSeriesCount,
		"warning_count",
		warningCount,
	)

	return nil
}

// logSeriesDifferences logs detailed differences between series data
func (h *ScrapSeriesHandler) logSeriesDifferences(newSeries, existingSeries Series) {
	if newSeries.Name != existingSeries.Name {
		slog.Debug("Series names differ", "name", newSeries.Name, "name", existingSeries.Name)
	}
	if newSeries.ShortName != existingSeries.ShortName {
		slog.Debug("Series shortnames differ", "name", newSeries.ShortName, "name", existingSeries.ShortName)
	}
	if newSeries.ShortCode != existingSeries.ShortCode {
		slog.Debug("Series shortcodes differ", "name", newSeries.ShortName, "name", existingSeries.ShortName)
	}
	if newSeries.Category != existingSeries.Category {
		slog.Debug("Series categories differ", "category", newSeries.Category, "category", existingSeries.Category)
	}
}

const endpointSeries = "https://api.motorsportstats.com/widgets/1.0.0/series"

const schemaSeries = `{
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
			"shortName": {
				"type": ["string", "null"]
			},
			"shortCode": {
				"type": "string"
			},
			"category": {
				"type": "string"
			}
		},
		"required": ["name", "uuid", "shortName", "shortCode", "category"]
	},
	"minItems": 1
}`
