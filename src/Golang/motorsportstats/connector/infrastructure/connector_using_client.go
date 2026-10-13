package infrastructure

import (
	"bytes"
	"context"
	_ "embed"
	"errors"
	"fmt"
	"strings"

	"github.com/santhosh-tekuri/jsonschema/v6"

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

func (c *ConnectorUsingClient) GetAvailableStandings(ctx context.Context, seasonUUID string) ([]byte, error) {
	return c.doGet(ctx, schemaAvailableStandings, endpointAvailableStandings, seasonUUID)
}

func (c *ConnectorUsingClient) GetStandings(
	ctx context.Context,
	seasonUUID string,
	kind StandingKind,
	classUUID string,
) ([]byte, error) {
	schema, known := schemaStandings[kind]
	if known == false {
		return []byte{}, fmt.Errorf("getting standings: unknown standing kind %q", kind)
	}

	if classUUID == "" {
		return c.doGet(ctx, schema, endpointStandings, seasonUUID, kind)
	}

	return c.doGet(ctx, schema, endpointStandingsOfClass, seasonUUID, kind, classUUID)
}

func (c *ConnectorUsingClient) GetClassification(ctx context.Context, sessionUUD string) ([]byte, error) {
	return c.doGet(ctx, schemaClassification, endpointClassification, sessionUUD)
}

func (c *ConnectorUsingClient) doGet(ctx context.Context, schema *jsonschema.Schema, endpoint string, params ...any) ([]byte, error) {
	url := endpoint
	if len(params) > 0 {
		url = fmt.Sprintf(endpoint, params...)
	}

	resp, err := c.client.Get(url, headers)
	if err != nil {
		return []byte{}, fmt.Errorf("getting %s: %w", url, err)
	}

	if err := c.validate(ctx, resp, schema); err != nil {
		return []byte{}, fmt.Errorf("validating response from %s: %w", url, err)
	}

	return resp, nil
}

// validate checks if the content conforms to the expected schema. A failure lists one line per
// leaf error, "<instance path>: <message>", which the CacheWarmer report and ApiCanary both read.
func (c *ConnectorUsingClient) validate(_ context.Context, content []byte, schema *jsonschema.Schema) error {
	instance, err := jsonschema.UnmarshalJSON(bytes.NewReader(content))
	if err != nil {
		return fmt.Errorf("unmarshalling content: %w", err)
	}

	err = schema.Validate(instance)
	if err == nil {
		return nil
	}

	var validationErr *jsonschema.ValidationError
	if errors.As(err, &validationErr) == false {
		return fmt.Errorf("validating content: %w", err)
	}

	var buf bytes.Buffer
	writeLeafErrors(&buf, validationErr.DetailedOutput())

	return fmt.Errorf("validation errors: %s", buf.String())
}

// writeLeafErrors writes the errors at the bottom of the output tree. The units above them only group
// their causes, under a $ref or a oneOf, and say nothing a leaf does not.
func writeLeafErrors(buf *bytes.Buffer, unit *jsonschema.OutputUnit) {
	if len(unit.Errors) == 0 {
		path := unit.InstanceLocation
		if path == "" {
			path = "/"
		}

		buf.WriteString(path + ": " + unit.Error.String() + "\n")

		return
	}

	for i := range unit.Errors {
		writeLeafErrors(buf, &unit.Errors[i])
	}
}

// compileSchema compiles an embedded schema once, at start-up. Schemas without a $schema keyword are
// read as draft 2019-09, and format is asserted, so format: uri rejects a value that is not a URI. A
// schema that does not compile is a bug in this repository, hence the panic.
func compileSchema(name string, raw string) *jsonschema.Schema {
	doc, err := jsonschema.UnmarshalJSON(strings.NewReader(raw))
	if err != nil {
		panic(fmt.Sprintf("parsing schema %s: %v", name, err))
	}

	compiler := jsonschema.NewCompiler()
	compiler.DefaultDraft(jsonschema.Draft2019)
	compiler.AssertFormat()

	if err := compiler.AddResource(name, doc); err != nil {
		panic(fmt.Sprintf("adding schema %s: %v", name, err))
	}

	return compiler.MustCompile(name)
}

var headers = map[string]string{
	"Origin":           "https://widgets.motorsportstats.com",
	"X-Parent-Referer": "https://motorsportstats.com/",
}

const (
	endpointSeries             = "/widgets/1.0.0/series"
	endpointSeasons            = "/widgets/1.0.0/series/%s/seasons"
	endpointCalendar           = "/widgets/1.0.0/seasons/%s/calendar"
	endpointAvailableStandings = "/widgets/1.0.0/seasons/%s/standings"
	endpointStandings          = "/widgets/1.0.0/seasons/%s/standings/%s"
	endpointStandingsOfClass   = endpointStandings + "?seriesClassUuid=%s"
	endpointClassification     = "/widgets/1.0.0/sessions/%s/classification"
)

//go:embed schemas/series.json
var rawSchemaSeries string

var schemaSeries = compileSchema("series.json", rawSchemaSeries)

//go:embed schemas/seasons.json
var rawSchemaSeasons string

var schemaSeasons = compileSchema("seasons.json", rawSchemaSeasons)

//go:embed schemas/calendar.json
var rawSchemaCalendar string

var schemaCalendar = compileSchema("calendar.json", rawSchemaCalendar)

//go:embed schemas/available_standings.json
var rawSchemaAvailableStandings string

var schemaAvailableStandings = compileSchema("available_standings.json", rawSchemaAvailableStandings)

//go:embed schemas/standings_drivers.json
var rawSchemaStandingsDrivers string

var schemaStandingsDrivers = compileSchema("standings_drivers.json", rawSchemaStandingsDrivers)

//go:embed schemas/standings_teams.json
var rawSchemaStandingsTeams string

var schemaStandingsTeams = compileSchema("standings_teams.json", rawSchemaStandingsTeams)

//go:embed schemas/standings_constructors.json
var rawSchemaStandingsConstructors string

var schemaStandingsConstructors = compileSchema("standings_constructors.json", rawSchemaStandingsConstructors)

var schemaStandings = map[StandingKind]*jsonschema.Schema{
	StandingDrivers:      schemaStandingsDrivers,
	StandingTeams:        schemaStandingsTeams,
	StandingConstructors: schemaStandingsConstructors,
}

//go:embed schemas/classification.json
var rawSchemaClassification string

var schemaClassification = compileSchema("classification.json", rawSchemaClassification)
