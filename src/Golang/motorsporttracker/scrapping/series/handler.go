package series

import (
	"context"
	"encoding/json"
	"errors"
	"fmt"
	"log"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
)

type ScrapSeriesHandler struct {
	db        database.Database
	connector connector.Connector
}

type Series struct {
	Name      string `json:"name"`
	UUID      string `json:"uuid"`
	ShortName string `json:"shortName"`
	ShortCode string `json:"shortCode"`
	Category  string `json:"category"`
}

// NewScrapSeriesHandler creates a new handler for scrapping series.
func NewScrapSeriesHandler(db database.Database, connector connector.Connector) *ScrapSeriesHandler {
	return &ScrapSeriesHandler{
		connector: connector,
		db:        db,
	}
}

// Handle processes the scrapping intent for series.
func (h *ScrapSeriesHandler) Handle(ctx context.Context, _ messaging.Message) error {
	resp, err := h.connector.Get(endpointSeries)
	if err != nil {
		return errors.New("fetching series data: " + err.Error())
	}

	// Validate the response content
	if err := scrapping.Validate(ctx, resp, schemaSeries); err != nil {
		return errors.New("validating series data: " + err.Error())
	}

	// Unmarshal the response into a slice of Series
	var seriesList []Series
	err = json.Unmarshal(resp, &seriesList)
	if err != nil {
		return errors.New("unmarshalling series data: " + err.Error())
	}

	// Get existing series from database for comparison
	existingSeries, err := h.getExistingSeries(ctx)
	if err != nil {
		return errors.New("fetching existing series from database: " + err.Error())
	}

	newSeriesCount := 0
	existingSeriesCount := 0
	warningCount := 0

	for _, series := range seriesList {
		if existingData, exists := existingSeries[series.UUID]; exists {
			existingSeriesCount++
			log.Printf("Series already exists: %s (UUID: %s)", series.Name, series.UUID)

			// Compare data and log warnings if different
			if h.compareSeriesData(series, existingData) {
				warningCount++
				log.Printf("WARNING: Series data differs for %s (UUID: %s)", series.Name, series.UUID)
				h.logSeriesDifferences(series, existingData)
			}
		} else {
			// Insert new series into the database
			err = h.db.Exec(ctx, `
				INSERT INTO series (name, uuid, short_name, short_code, category)
				VALUES ($1, $2, $3, $4, $5)`,
				series.Name, series.UUID, series.ShortName, series.ShortCode, series.Category)

			if err != nil {
				return errors.New("inserting series into database: " + err.Error())
			}

			newSeriesCount++
			log.Printf("Inserted new series: %s (UUID: %s)", series.Name, series.UUID)
		}
	}

	log.Printf("Series scrapping completed - New: %d, Existing: %d, Warnings: %d",
		newSeriesCount, existingSeriesCount, warningCount)

	return nil
}

// getExistingSeries retrieves all existing series from the database
func (h *ScrapSeriesHandler) getExistingSeries(ctx context.Context) (map[string]Series, error) {
	rows, err := h.db.Query(ctx, "SELECT name, uuid, short_name, short_code, category FROM series")
	if err != nil {
		return nil, fmt.Errorf("querying existing series: %w", err)
	}
	defer func(rows database.Rows) {
		err := rows.Close()
		if err != nil {
			log.Printf("Error closing rows: %v", err)
		}
	}(rows)

	existingSeries := make(map[string]Series)

	for rows.Next() {
		var series Series
		err := rows.Scan(&series.Name, &series.UUID, &series.ShortName, &series.ShortCode, &series.Category)
		if err != nil {
			return nil, fmt.Errorf("scanning series row: %w", err)
		}

		existingSeries[series.UUID] = series
	}

	if err := rows.Err(); err != nil {
		return nil, fmt.Errorf("iterating series rows: %w", err)
	}

	log.Printf("Retrieved %d existing series from database", len(existingSeries))
	return existingSeries, nil
}

// compareSeriesData compares new series data with existing data
// Returns true if there are differences
func (h *ScrapSeriesHandler) compareSeriesData(newSeries, existingSeries Series) bool {
	return newSeries.Name != existingSeries.Name ||
		newSeries.ShortName != existingSeries.ShortName ||
		newSeries.ShortCode != existingSeries.ShortCode ||
		newSeries.Category != existingSeries.Category
}

// logSeriesDifferences logs detailed differences between series data
func (h *ScrapSeriesHandler) logSeriesDifferences(newSeries, existingSeries Series) {
	if newSeries.Name != existingSeries.Name {
		log.Printf("  Name differs: '%s' -> '%s'", existingSeries.Name, newSeries.Name)
	}
	if newSeries.ShortName != existingSeries.ShortName {
		log.Printf("  ShortName differs: '%s' -> '%s'", existingSeries.ShortName, newSeries.ShortName)
	}
	if newSeries.ShortCode != existingSeries.ShortCode {
		log.Printf("  ShortCode differs: '%s' -> '%s'", existingSeries.ShortCode, newSeries.ShortCode)
	}
	if newSeries.Category != existingSeries.Category {
		log.Printf("  Category differs: '%s' -> '%s'", existingSeries.Category, newSeries.Category)
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
