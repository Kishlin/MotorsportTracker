package infrastructure

import (
	"bytes"
	"context"
	_ "embed"
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

const (
	endpointSeries         = "/widgets/1.0.0/series"
	endpointSeasons        = "/widgets/1.0.0/series/%s/seasons"
	endpointCalendar       = "/widgets/1.0.0/seasons/%s/calendar"
	endpointClassification = "/widgets/1.0.0/sessions/%s/classification"
)

//go:embed schemas/series.json
var schemaSeries string

//go:embed schemas/seasons.json
var schemaSeasons string

//go:embed schemas/calendar.json
var schemaCalendar string

//go:embed schemas/classification.json
var schemaClassification string
