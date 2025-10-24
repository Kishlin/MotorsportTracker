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

func (c *ConnectorUsingClient) GetClassification(ctx context.Context, sessionUUD string) ([]byte, error) {
	return c.doGet(ctx, schemaClassification, endpointClassification, sessionUUD)
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
			"uuid": {
				"type": "string"
			},
			"name": {
				"type": ["string", "null"]
			},
			"shortName": {
				"type": ["string", "null"]
			},
			"shortCode": {
				"type": ["string", "null"]
			},
			"category": {
				"type": ["string", "null"]
			}
		},
		"required": ["uuid", "name", "shortName", "shortCode", "category"]
	},
	"minItems": 1
}`

const endpointSeasons = "/widgets/1.0.0/series/%s/seasons"

const schemaSeasons = `{
	"type": "array",
	"items": {
		"type": "object",
		"properties": {
			"uuid": {
				"type": "string"
			},
			"name": {
				"type": ["string", "null"]
			},
			"year": {
				"type": ["number", "null"]
			},
			"endYear": {
				"type": ["number", "null"]
			},
			"status": {
				"type": ["string", "null"]
			}
		},
		"required": ["uuid", "name", "year", "endYear", "status"]
	}
}`

const endpointCalendar = "/widgets/1.0.0/seasons/%s/calendar"

const schemaCalendar = `{
  "type": "object",
  "properties": {
    "season": {
      "type": "object",
      "properties": {
        "uuid": {
          "type": "string"
        },
        "name": {
          "type": ["string", "null"]
        },
        "year": {
          "type": ["number", "null"]
        },
        "endYear": {
          "type": ["number", "null"]
        }
      },
      "required": [
        "uuid",
        "name",
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
            "type": ["string", "null"]
          },
          "shortName": {
            "type": ["string", "null"]
          },
          "shortCode": {
            "type": ["string", "null"]
          },
          "status": {
            "type": ["string", "null"]
          },
          "startDate": {
            "type": ["number", "null"]
          },
          "startTimeUtc": {
            "type": ["number", "null"]
          },
          "endDate": {
            "type": ["number", "null"]
          },
          "endTimeUtc": {
            "type": ["number", "null"]
          },
          "venue": {
            "type": "object",
            "properties": {
              "uuid": {
                "type": "string"
              },
              "name": {
                "type": ["string", "null"]
              },
              "shortName": {
                "type": ["string", "null"]
              },
              "shortCode": {
                "type": ["string", "null"]
              },
              "picture": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "uuid",
              "name",
              "shortName",
              "shortCode"
            ]
          },
          "country": {
            "type": "object",
            "properties": {
              "uuid": {
                "type": "string"
              },
              "name": {
                "type": ["string", "null"]
              },
              "picture": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "uuid",
              "name",
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
                  "type": ["string", "null"]
                },
                "shortName": {
                  "type": ["string", "null"]
                },
                "shortCode": {
                  "type": ["string", "null"]
                },
                "status": {
                  "type": ["string", "null"]
                },
                "hasResults": {
                  "type": ["boolean", "null"]
                },
                "startTime": {
                  "type": ["number", "null"]
                },
                "startTimeUtc": {
                  "type": ["number", "null"]
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

const endpointClassification = "/widgets/1.0.0/sessions/%s/classification"

const schemaClassification = `{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "title": "Generated schema for Root",
  "type": "object",
  "properties": {
    "details": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "carNumber": {
            "type": "string"
          },
          "finishPosition": {
            "type": ["number", "null"]
          },
          "gridPosition": {
            "type": ["number", "null"]
          },
          "drivers": {
            "type": "array",
            "items": {
              "type": "object",
              "properties": {
                "uuid": {
                  "type": "string"
                },
                "name": {
                  "type": ["string", "null"]
                },
                "firstName": {
                  "type": ["string", "null"]
                },
                "lastName": {
                  "type": ["string", "null"]
                },
                "shortCode": {
                  "type": ["string", "null"]
                },
                "colour": {
                  "type": ["string", "null"]
                },
                "picture": {
                  "type": ["string", "null"]
                }
              },
              "required": [
				"uuid",
                "name",
                "firstName",
                "lastName",
                "shortCode"
              ]
            }
          },
          "team": {
            "type": "object",
            "properties": {
              "uuid": {
                "type": "string"
              },
              "name": {
                "type": ["string", "null"]
              },
              "colour": {
                "type": ["string", "null"]
              },
              "picture": {
                "type": ["string", "null"]
              },
              "carIcon": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "uuid",
              "name",
              "colour",
              "picture",
              "carIcon"
            ]
          },
          "nationality": {
            "type": "object",
            "properties": {
              "uuid": {
                "type": "string"
              },
              "name": {
                "type": ["string", "null"]
              },
              "picture": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "uuid",
              "name",
              "picture"
            ]
          },
          "laps": {
            "type": ["number", "null"]
          },
          "points": {
            "type": ["number", "null"]
          },
          "time": {
            "type": ["number", "null"]
          },
          "classifiedStatus": {
            "type": ["string", "null"]
          },
          "avgLapSpeed": {
            "type": ["number", "null"]
          },
          "fastestLapTime": {
            "type": ["number", "null"]
          },
          "gap": {
            "type": "object",
            "properties": {
              "timeToLead": {
                "type": ["number", "null"]
              },
              "timeToNext": {
                "type": ["number", "null"]
              },
              "lapsToLead": {
                "type": ["number", "null"]
              },
              "lapsToNext": {
                "type": ["number", "null"]
              }
            },
            "required": [
              "timeToLead",
              "timeToNext",
              "lapsToLead",
              "lapsToNext"
            ]
          },
          "best": {
            "type": "object",
            "properties": {
              "lap": {
                "type": ["number", "null"]
              },
              "time": {
                "type": ["number", "null"]
              },
              "fastest": {
                "type": ["boolean", "null"]
              },
              "speed": {
                "type": ["number", "null"]
              }
            },
            "required": [
              "lap",
              "time",
              "fastest"
            ]
          }
        },
        "required": [
          "carNumber",
          "finishPosition",
          "gridPosition",
          "drivers",
          "team",
          "nationality",
          "laps",
          "points",
          "time",
          "classifiedStatus",
          "avgLapSpeed",
          "fastestLapTime",
          "gap",
          "best"
        ]
      }
    },
    "retirements": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "driver": {
            "type": "object",
            "properties": {
              "uuid": {
                "type": "string"
              },
              "name": {
                "type": ["string", "null"]
              },
              "firstName": {
                "type": ["string", "null"]
              },
              "lastName": {
                "type": ["string", "null"]
              },
              "shortCode": {
                "type": ["string", "null"]
              },
              "colour": {
				"type": ["string", "null"]
			  },
              "picture": {
                "type": ["string", "null"]
              }
            },
            "required": [
              "uuid",
              "name",
              "firstName",
              "lastName",
              "shortCode",
              "colour",
              "picture"
            ]
          },
          "carNumber": {
            "type": "string"
          },
          "reason": {
            "type": ["string", "null"]
          },
          "type": {
            "type": ["string", "null"]
          },
          "dns": {
            "type": ["boolean", "null"]
          },
          "lap": {
            "type": ["number", "null"]
          },
		  "details": {
			"type": ["string", "null"]
		  }
        },
        "required": [
          "driver",
          "carNumber",
          "reason",
          "type",
          "dns",
          "lap",
          "details"
        ]
      }
    }
  },
  "required": [
    "details",
    "retirements"
  ]
}`
