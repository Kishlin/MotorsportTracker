package infrastructure

import (
	"bytes"
	"context"
	"fmt"

	"github.com/qri-io/jsonschema"
	
	client "github.com/kishlin/MotorsportTracker/src/Golang/shared/client/infrastructure"
)

type ConnectorUsingClient struct {
	client *client.Client
}

// NewConnectorUsingClient creates a new MotorsportStarsConnector using a HTTP client.
func NewConnectorUsingClient(client *client.Client) *ConnectorUsingClient {
	return &ConnectorUsingClient{
		client: client,
	}
}

func (c *ConnectorUsingClient) GetSeries(ctx context.Context) ([]byte, error) {
	return c.doGet(ctx, schemaSeries, endpointSeries)
}

func (c *ConnectorUsingClient) GetSeasons(ctx context.Context, seriesUuid string) ([]byte, error) {
	return c.doGet(ctx, schemaSeasons, endpointSeasons, seriesUuid)
}

func (c *ConnectorUsingClient) doGet(ctx context.Context, schema string, endpoint string, params ...string) ([]byte, error) {
	resp, err := c.client.Get(fmt.Sprintf(endpoint, params), headers)
	if err != nil {
		return []byte{}, fmt.Errorf("getting series: %w", err)
	}

	if err := c.validate(ctx, resp, schema); err != nil {
		return []byte{}, fmt.Errorf("validating series data: %w", err)
	}

	return resp, nil
}

// validate checks if the content conforms to the expected schema.
func (c *ConnectorUsingClient) validate(ctx context.Context, content []byte, expectedSchema string) error {
	rs := jsonschema.Schema{}
	if err := rs.UnmarshalJSON([]byte(expectedSchema)); err != nil {
		return fmt.Errorf("unmarshalling expectedSchema: %w", err)
	}

	errs, err := rs.ValidateBytes(ctx, content)
	if err != nil {
		return fmt.Errorf("validating content: %w", err)
	}

	if len(errs) > 0 {
		var buf bytes.Buffer
		for _, e := range errs {
			buf.WriteString(e.Error() + "\n")
		}

		return fmt.Errorf("validation errors: %s", buf.String())
	}

	return nil
}

var headers = map[string]string{
	"Origin":           "https://widgets.motorsportstats.com",
	"X-Parent-Referer": "https://motorsportstats.com/",
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
