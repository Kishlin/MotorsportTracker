package main

import (
	"context"
	"encoding/json"
	"flag"
	"fmt"
	"io"
	"os"
	"sort"
	"strings"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	client "github.com/kishlin/MotorsportTracker/src/Golang/shared/client/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
	logger "github.com/kishlin/MotorsportTracker/src/Golang/shared/logger/infrastructure"
)

// probes are the series walked end to end on every run. Each one is here because its classification
// payload has a shape the others do not exercise; add a line to widen the sweep.
var probes = []struct {
	name string
	why  string
}{
	{name: "FIA Formula One World Championship", why: "single seater, one driver per entry"},
	{name: "FIA World Endurance Championship", why: "sportscar, several drivers per entry"},
}

// maxSeasonsBack bounds the walk back through seasons looking for one that has results. Early in a
// calendar year the current season has none yet, and that is not a failure.
const maxSeasonsBack = 3

func main() {
	strict := flag.Bool("strict", false, "exit non-zero on warnings too, not only on failures")
	flag.Parse()

	if err := env.LoadEnv(); err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error loading environment variables: %v\n", err)
		os.Exit(1)
	}

	logger.SetupSlog()

	host := os.Getenv("REMOTE_API_HOST")
	if host == "" {
		_, _ = fmt.Fprintln(os.Stderr, "REMOTE_API_HOST environment variable is not set")
		os.Exit(1)
	}

	projectDir := os.Getenv("PROJECT_DIR")
	if projectDir == "" {
		_, _ = fmt.Fprintln(os.Stderr, "PROJECT_DIR environment variable is not set, schemas cannot be located")
		os.Exit(1)
	}

	// Deliberately not the ServicesRegistry gateway: that one is wrapped in a CachedConnector, and a
	// cache hit returns bytes without ever validating them. The canary must reach the live API.
	conn := connector.NewConnectorUsingClient(client.NewClient(host))

	checks := runCanary(context.Background(), conn, projectDir)

	fmt.Printf("motorsportstats API canary — %s\n\n", host)
	printReport(os.Stdout, checks)

	os.Exit(exitCode(checks, *strict))
}

type verdict int

const (
	verdictOK verdict = iota
	verdictWarn
	verdictFail
)

// check is one endpoint response, inspected.
type check struct {
	series   string // empty for the top-level series call, which is not scoped to a probe
	endpoint string
	detail   string
	verdict  verdict
	objects  int
	messages []string
}

// withCount records how much the check actually looked at. A payload that validates but holds nothing
// never exercised the schema, which is worth reporting — though it is not a claim about the data, whose
// contents and volume are expected to change run to run.
func (c check) withCount(objects int) check {
	c.objects = objects

	if c.verdict == verdictOK && objects == 0 {
		c.verdict = verdictWarn
		c.messages = append(c.messages, "nothing to validate: the payload held no objects")
	}

	return c
}

// runCanary walks series -> seasons -> calendar -> classification, taking every identifier from the
// previous response. The scraping use cases cannot be reused here: they resolve their identifiers out
// of the core database, which the canary deliberately does not touch.
func runCanary(ctx context.Context, conn *connector.ConnectorUsingClient, projectDir string) []check {
	payload, err := conn.GetSeries(ctx)

	allSeries, inspection := inspect[[]*motorsportstats.Series](projectDir, "series", payload, err)

	seriesCheck := check{
		endpoint: "series",
		verdict:  inspection.verdict,
		messages: inspection.messages,
	}.withCount(len(allSeries))

	checks := []check{seriesCheck}
	if seriesCheck.verdict == verdictFail {
		return checks
	}

	for _, probe := range probes {
		checks = append(checks, probeSeries(ctx, conn, projectDir, probe.name, allSeries)...)
	}

	return checks
}

func probeSeries(
	ctx context.Context,
	conn *connector.ConnectorUsingClient,
	projectDir string,
	name string,
	allSeries []*motorsportstats.Series,
) []check {
	target := findSeries(allSeries, name)
	if target == nil {
		return []check{{
			series:   name,
			endpoint: "lookup",
			verdict:  verdictFail,
			messages: []string{fmt.Sprintf("no series named %q in the series payload", name)},
		}}
	}

	payload, err := conn.GetSeasons(ctx, target.UUID)

	seasons, inspection := inspect[[]*motorsportstats.Season](projectDir, "seasons", payload, err)

	checks := []check{check{
		series:   name,
		endpoint: "seasons",
		verdict:  inspection.verdict,
		messages: inspection.messages,
	}.withCount(len(seasons))}

	if checks[0].verdict == verdictFail {
		return checks
	}

	return append(checks, probeSeasons(ctx, conn, projectDir, name, seasons)...)
}

// probeSeasons walks back from the most recent season until one yields sessions with results, then
// checks a classification for each of those sessions.
func probeSeasons(
	ctx context.Context,
	conn *connector.ConnectorUsingClient,
	projectDir string,
	name string,
	seasons []*motorsportstats.Season,
) []check {
	var checks []check

	for _, season := range mostRecentSeasons(seasons, maxSeasonsBack) {
		year := fn.Deref(season.Year, 0)

		payload, err := conn.GetCalendar(ctx, season.UUID)

		calendar, inspection := inspect[*motorsportstats.Calendar](projectDir, "calendar", payload, err)

		calendarCheck := check{
			series:   name,
			endpoint: "calendar",
			detail:   fmt.Sprintf("%d", year),
			verdict:  inspection.verdict,
			messages: inspection.messages,
		}

		if calendar == nil {
			checks = append(checks, calendarCheck.withCount(0))
			return checks
		}

		checks = append(checks, calendarCheck.withCount(len(calendar.Events)))

		if calendarCheck.verdict == verdictFail {
			return checks
		}

		sessions := pickSessions(calendar)
		if len(sessions) == 0 {
			continue
		}

		for _, session := range sessions {
			checks = append(checks, probeClassification(ctx, conn, projectDir, name, session))
		}

		return checks
	}

	return append(checks, check{
		series:   name,
		endpoint: "classification",
		verdict:  verdictWarn,
		messages: []string{fmt.Sprintf("no session with results in the last %d seasons, endpoint not checked", maxSeasonsBack)},
	})
}

func probeClassification(
	ctx context.Context,
	conn *connector.ConnectorUsingClient,
	projectDir string,
	name string,
	session sessionPick,
) check {
	payload, err := conn.GetClassification(ctx, session.uuid)

	classification, inspection := inspect[*motorsportstats.Classification](projectDir, "classification", payload, err)

	result := check{
		series:   name,
		endpoint: "classification",
		detail:   fmt.Sprintf("%s / %s", session.event, session.name),
		verdict:  inspection.verdict,
		messages: inspection.messages,
	}

	if classification == nil {
		return result.withCount(0)
	}

	return result.withCount(len(classification.Details))
}

type inspection struct {
	verdict  verdict
	messages []string
}

// inspect applies three checks to a single endpoint response. The connector has already validated the
// payload against the schema it embeds (that is what fetchErr carries); the same bytes are then parsed
// into the domain struct the scrapers use, and finally the payload's keys are diffed against the schema
// on disk to surface fields motorsportstats has added.
func inspect[T any](projectDir string, resource string, payload []byte, fetchErr error) (T, inspection) {
	var value T

	if fetchErr != nil {
		return value, inspection{verdict: verdictFail, messages: collapseValidationErrors(fetchErr.Error())}
	}

	if err := json.Unmarshal(payload, &value); err != nil {
		return value, inspection{verdict: verdictFail, messages: []string{err.Error()}}
	}

	schema, err := loadSchema(projectDir, resource)
	if err != nil {
		return value, inspection{verdict: verdictFail, messages: []string{err.Error()}}
	}

	keys, err := unknownKeys(schema, payload)
	if err != nil {
		return value, inspection{verdict: verdictFail, messages: []string{err.Error()}}
	}

	if len(keys) > 0 {
		return value, inspection{verdict: verdictWarn, messages: append([]string{"keys not in schema:"}, keys...)}
	}

	return value, inspection{verdict: verdictOK}
}

func findSeries(allSeries []*motorsportstats.Series, name string) *motorsportstats.Series {
	for _, series := range allSeries {
		if series == nil {
			continue
		}

		if fn.Deref(series.Name, "") == name {
			return series
		}
	}

	return nil
}

// mostRecentSeasons returns up to limit seasons, newest first.
func mostRecentSeasons(seasons []*motorsportstats.Season, limit int) []*motorsportstats.Season {
	ordered := make([]*motorsportstats.Season, 0, len(seasons))
	for _, season := range seasons {
		if season != nil {
			ordered = append(ordered, season)
		}
	}

	sort.SliceStable(ordered, func(i, j int) bool {
		return fn.Deref(ordered[i].Year, 0) > fn.Deref(ordered[j].Year, 0)
	})

	if len(ordered) > limit {
		ordered = ordered[:limit]
	}

	return ordered
}

type sessionPick struct {
	event string
	name  string
	uuid  string
}

// pickSessions chooses one race and one qualifying session that have results, preferring the latest
// event. Session names upstream are inconsistent enough ("Free Practice" and "Free practice" in the
// same season, a leading newline on "Super Pole 1", "Practice 3 - Cancelled") that matching is done on
// a substring of the normalised name and never on equality.
func pickSessions(calendar *motorsportstats.Calendar) []sessionPick {
	var withResults []sessionPick

	for _, event := range calendar.Events {
		if event == nil {
			continue
		}

		for _, session := range event.Sessions {
			if session == nil || fn.Deref(session.HasResults, false) == false {
				continue
			}

			withResults = append(withResults, sessionPick{
				event: strings.TrimSpace(fn.Deref(event.Name, "unnamed event")),
				name:  strings.TrimSpace(fn.Deref(session.Name, "unnamed session")),
				uuid:  session.UUID,
			})
		}
	}

	if len(withResults) == 0 {
		return nil
	}

	var picks []sessionPick

	for _, hint := range []string{"race", "qualif"} {
		for i := len(withResults) - 1; i >= 0; i-- {
			if strings.Contains(strings.ToLower(withResults[i].name), hint) {
				picks = append(picks, withResults[i])
				break
			}
		}
	}

	// Rally and one-make series need not name anything "race" or "qualifying". Check the latest
	// session with results rather than skip the endpoint.
	if len(picks) == 0 {
		picks = append(picks, withResults[len(withResults)-1])
	}

	return picks
}

// printReport leads with the verdict so the failures are scannable in one column. Endpoint labels vary
// wildly in length — "Combined Qualifying" at one event, "Race" at the next — so nothing is padded to
// their width.
func printReport(w io.Writer, checks []check) {
	currentSeries := ""

	for _, c := range checks {
		if c.series != currentSeries {
			currentSeries = c.series
			_, _ = fmt.Fprintf(w, "\n%s\n", currentSeries)
		}

		label := c.endpoint
		if c.detail != "" {
			label += " " + c.detail
		}

		if c.verdict != verdictFail {
			label += fmt.Sprintf(" (%s)", plural(c.objects, "object"))
		}

		indent := ""
		if c.series != "" {
			indent = "  "
		}

		_, _ = fmt.Fprintf(w, "%-5s %s%s\n", verdictLabel(c.verdict), indent, label)

		for _, message := range c.messages {
			for _, line := range strings.Split(strings.TrimRight(message, "\n"), "\n") {
				_, _ = fmt.Fprintf(w, "        %s%s\n", indent, line)
			}
		}
	}

	failed, warned := tally(checks)

	_, _ = fmt.Fprintln(w)

	switch {
	case failed > 0:
		_, _ = fmt.Fprintf(w, "%s, %s\n", plural(failed, "failure"), plural(warned, "warning"))
	case warned > 0:
		_, _ = fmt.Fprintf(w, "no failures, %s\n", plural(warned, "warning"))
	default:
		_, _ = fmt.Fprintf(w, "all %d checks clear\n", len(checks))
	}
}

func verdictLabel(v verdict) string {
	switch v {
	case verdictFail:
		return "FAIL"
	case verdictWarn:
		return "WARN"
	default:
		return "OK"
	}
}

func tally(checks []check) (failed int, warned int) {
	for _, c := range checks {
		switch c.verdict {
		case verdictFail:
			failed++
		case verdictWarn:
			warned++
		case verdictOK:
		}
	}

	return failed, warned
}

func exitCode(checks []check, strict bool) int {
	failed, warned := tally(checks)

	if failed > 0 {
		return 1
	}

	if strict && warned > 0 {
		return 1
	}

	return 0
}

func plural(count int, noun string) string {
	if count == 1 {
		return fmt.Sprintf("%d %s", count, noun)
	}

	return fmt.Sprintf("%d %ss", count, noun)
}
