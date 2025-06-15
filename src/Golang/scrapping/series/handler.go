package series

import (
	"context"
	"encoding/json"
	"errors"
	"os"

	"github.com/jackc/pgx/v5/pgxpool"

	"github.com/kishlin/MotorsportTracker/src/Golang/client"
	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping"
)

const EndpointSeries = "https://api.motorsportstats.com/widgets/1.0.0/series"

const SchemaSeries = `{
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

type ScrapSeriesHandler struct {
	*scrapping.BaseScrappingHandler
}

type Series struct {
	Name      string `json:"name"`
	UUID      string `json:"uuid"`
	ShortName string `json:"shortName"`
	ShortCode string `json:"shortCode"`
	Category  string `json:"category"`
}

// NewScrapSeriesHandler creates a new handler for scrapping series.
func NewScrapSeriesHandler() *ScrapSeriesHandler {
	handler := &ScrapSeriesHandler{
		BaseScrappingHandler: &scrapping.BaseScrappingHandler{
			Connector: client.NewConnector(),
		},
	}

	return handler
}

// Handle processes the scrapping intent for series.
func (h *ScrapSeriesHandler) Handle(ctx context.Context, message queue.Message) error {
	resp, err := h.Connector.Get(EndpointSeries)
	if err != nil {
		return errors.New("fetching series data: " + err.Error())
	}

	// Validate the response content
	if err := h.Validate(ctx, resp, SchemaSeries); err != nil {
		return errors.New("validating series data: " + err.Error())
	}

	// Unmarshal the response into a slice of Series
	var seriesList []Series
	err = json.Unmarshal(resp, &seriesList)
	if err != nil {
		return errors.New("unmarshalling series data: " + err.Error())
	}

	dbpool, err := pgxpool.New(ctx, os.Getenv("POSTGRES_CORE_URL"))
	if err != nil {
		return errors.New("connecting to database: " + err.Error())
	}
	defer dbpool.Close()

	for _, series := range seriesList {
		// Insert each series into the database
		_, err = dbpool.Exec(ctx, `
			INSERT INTO series (name, uuid, short_name, short_code, category)
			VALUES ($1, $2, $3, $4, $5)
			ON CONFLICT (uuid) DO NOTHING`,
			series.Name, series.UUID, series.ShortName, series.ShortCode, series.Category)

		if err != nil {
			return errors.New("inserting series into database: " + err.Error())
		}
	}

	return nil
}
