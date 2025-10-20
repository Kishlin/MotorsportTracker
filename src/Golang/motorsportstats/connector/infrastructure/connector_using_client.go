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

// NewConnectorUsingClient creates a new MotorsportStarsConnector using an HTTP client.
func NewConnectorUsingClient(client *client.Client) *ConnectorUsingClient {
	return &ConnectorUsingClient{
		client: client,
	}
}

func (c *ConnectorUsingClient) GetSeries(ctx context.Context) ([]byte, error) {
	return c.doGet(ctx, schemaSeries, endpointSeries)
}

func (c *ConnectorUsingClient) GetSeasons(ctx context.Context, seriesUUID string) ([]byte, error) {
	return c.doGet(ctx, schemaSeasons, endpointSeasons, seriesUUID)
}

func (c *ConnectorUsingClient) GetCalendar(ctx context.Context, seasonUUID string) ([]byte, error) {
	return c.doGet(ctx, schemaCalendar, endpointCalendar, seasonUUID)
}

func (c *ConnectorUsingClient) doGet(ctx context.Context, schema string, endpoint string, params ...any) ([]byte, error) {
	url := endpoint
	if len(params) > 0 {
		url = fmt.Sprintf(endpoint, params...)
	}

	resp, err := c.client.Get(url, headers)
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

const endpointSeries = "/widgets/1.0.0/series"

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

const endpointSeasons = "/widgets/1.0.0/series/%s/seasons"

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

const endpointCalendar = "/widgets/1.0.0/seasons/%s/calendar"

const schemaCalendar = `{
  "type": "object",
  "properties": {
    "season": {
      "type": "object",
      "properties": {
        "name": {
          "type": "string"
        },
        "uuid": {
          "type": "string"
        },
        "year": {
          "type": "number"
        },
        "endYear": {
          "type": "number"
        }
      },
      "required": [
        "name",
        "uuid",
        "year",
        "endYear"
      ]
    },
    "events": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "uuid": {
            "type": "string"
          },
          "name": {
            "type": "string"
          },
          "shortName": {
            "type": "string"
          },
          "shortCode": {
            "type": "string"
          },
          "status": {
            "type": "string"
          },
          "startDate": {
            "type": "number"
          },
          "startTimeUtc": {
            "type": "number"
          },
          "endDate": {
            "type": "number"
          },
          "endTimeUtc": {
            "type": "number"
          },
          "venue": {
            "type": "object",
            "properties": {
              "name": {
                "type": "string"
              },
              "uuid": {
                "type": "string"
              },
              "shortName": {
                "type": "string"
              },
              "shortCode": {
                "type": "string"
              },
              "picture": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "name",
              "uuid",
              "shortName",
              "shortCode"
            ]
          },
          "country": {
            "type": "object",
            "properties": {
              "name": {
                "type": "string"
              },
              "uuid": {
                "type": "string"
              },
              "picture": {
                "type": "string"
              }
            },
            "required": [
              "name",
              "uuid",
              "picture"
            ]
          },
          "sessions": {
            "type": "array",
            "items": {
              "type": "object",
              "properties": {
                "uuid": {
                  "type": "string"
                },
                "name": {
                  "type": "string"
                },
                "shortName": {
                  "type": "string"
                },
                "shortCode": {
                  "type": "string"
                },
                "status": {
                  "type": "string"
                },
                "hasResults": {
                  "type": "boolean"
                },
                "startTime": {
                  "type": "number"
                },
                "startTimeUtc": {
                  "type": "number"
                },
                "endTime": {
                  "type": ["number", "null"]
                },
                "endTimeUtc": {
                  "type": ["number", "null"]
                }
              },
              "required": [
                "uuid",
                "name",
                "shortName",
                "shortCode",
                "status",
                "hasResults",
                "startTime",
                "startTimeUtc"
              ]
            }
          }
        },
        "required": [
          "uuid",
          "name",
          "shortName",
          "shortCode",
          "status",
          "startDate",
          "startTimeUtc",
          "endDate",
          "endTimeUtc",
          "venue",
          "country",
          "sessions"
        ]
      }
    }
  },
  "required": [
    "season",
    "events"
  ]
}`
