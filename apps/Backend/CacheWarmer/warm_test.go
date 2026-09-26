package main

import (
	"bytes"
	"context"
	"errors"
	"fmt"
	"path/filepath"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	cache "github.com/kishlin/MotorsportTracker/src/Golang/shared/cache/infrastructure"
)

// fakeConnector stands in for motorsportstats. An identifier missing from its maps fails the call, as a
// 404 would.
type fakeConnector struct {
	series          string
	seasons         map[string]string
	calendars       map[string]string
	classifications map[string]string
}

func (f fakeConnector) GetSeries(_ context.Context) ([]byte, error) {
	return []byte(f.series), nil
}

func (f fakeConnector) GetSeasons(_ context.Context, seriesUUID string) ([]byte, error) {
	return lookup(f.seasons, "seasons", seriesUUID)
}

func (f fakeConnector) GetCalendar(_ context.Context, seasonUUID string) ([]byte, error) {
	return lookup(f.calendars, "calendar", seasonUUID)
}

func (f fakeConnector) GetClassification(_ context.Context, sessionUUID string) ([]byte, error) {
	return lookup(f.classifications, "classification", sessionUUID)
}

func lookup(payloads map[string]string, resource string, uuid string) ([]byte, error) {
	payload, exists := payloads[uuid]
	if exists == false {
		return nil, fmt.Errorf("fetching data: 404 Not Found (%s %s)", resource, uuid)
	}

	return []byte(payload), nil
}

// upstream holds F1 with three seasons. 1950 has a race with results, a practice without, and a
// qualifying whose classification fails; 1951 has one race; 2025's calendar fails.
var upstream = fakeConnector{
	series: `[{"uuid":"series-f1","name":"FIA Formula One World Championship"},{"uuid":"series-wec","name":"FIA World Endurance Championship"}]`,
	seasons: map[string]string{
		"series-f1": `[{"uuid":"season-1950","year":1950},{"uuid":"season-1951","year":1951},{"uuid":"season-2025","year":2025}]`,
	},
	calendars: map[string]string{
		"season-1950": `{"events":[{"uuid":"event-1950","name":"British Grand Prix","sessions":[
			{"uuid":"session-1950-race","name":"Race","hasResults":true},
			{"uuid":"session-1950-practice","name":"Practice","hasResults":false},
			{"uuid":"session-1950-qualifying","name":"Qualifying","hasResults":true}
		]}]}`,
		"season-1951": `{"events":[{"uuid":"event-1951","name":"Swiss Grand Prix","sessions":[
			{"uuid":"session-1951-race","name":"Race","hasResults":true}
		]}]}`,
	},
	classifications: map[string]string{
		"session-1950-race":     `{"details":[]}`,
		"session-1950-practice": `{"details":[]}`,
		"session-1951-race":     `{"details":[]}`,
	},
}

type WarmUnitTestSuite struct {
	suite.Suite

	cacheDir string
	network  *throttledConnector
	conn     connector.Connector
}

func TestUnit_Warm(t *testing.T) {
	suite.Run(t, new(WarmUnitTestSuite))
}

// SetupSubTest gives every case a cold cache.
func (suite *WarmUnitTestSuite) SetupSubTest() {
	suite.cacheDir = suite.T().TempDir()
	suite.network = newThrottledConnector(upstream, 0)
	suite.conn = connector.NewCachedConnector(suite.network, cache.NewFileSystemCache(suite.cacheDir, ".json"))
}

func (suite *WarmUnitTestSuite) cached(namespace string, key string) string {
	return filepath.Join(suite.cacheDir, namespace, key+".json")
}

func (suite *WarmUnitTestSuite) TestWarm() {
	suite.Run("Walks the seasons in range down to the sessions with results", func() {
		r, err := warm(context.Background(), suite.conn, scope{series: []string{"FIA Formula One World Championship"}, from: 1950, to: 1951})

		require.NoError(suite.T(), err)
		require.FileExists(suite.T(), suite.cached("series", "all"))
		require.FileExists(suite.T(), suite.cached("seasons", "series-f1"))
		require.FileExists(suite.T(), suite.cached("calendar", "season-1950"))
		require.FileExists(suite.T(), suite.cached("calendar", "season-1951"))
		require.FileExists(suite.T(), suite.cached("classification", "session-1950-race"))
		require.FileExists(suite.T(), suite.cached("classification", "session-1951-race"))
		require.NoFileExists(suite.T(), suite.cached("calendar", "season-2025"), "outside the year range")
		require.NoFileExists(suite.T(), suite.cached("classification", "session-1950-practice"), "session without results")
		require.NoFileExists(suite.T(), suite.cached("seasons", "series-wec"), "series not asked for")
		require.Equal(suite.T(), []string{"series", "seasons", "calendar", "classification"}, r.endpoints)
		require.Equal(suite.T(), count{walked: 3, failed: 1}, *r.counts["classification"])
		require.Equal(suite.T(), 7, suite.network.calls)
	})

	suite.Run("A failed session is recorded and not cached, and its siblings are still warmed", func() {
		r, err := warm(context.Background(), suite.conn, scope{series: []string{"FIA Formula One World Championship"}, from: 1950, to: 1950})

		require.NoError(suite.T(), err)
		require.Len(suite.T(), r.failures, 1)
		require.Equal(suite.T(), "classification", r.failures[0].endpoint)
		require.Equal(suite.T(), "session-1950-qualifying", r.failures[0].uuid)
		require.Equal(suite.T(), "FIA Formula One World Championship 1950 / British Grand Prix / Qualifying", r.failures[0].target)
		require.NoFileExists(suite.T(), suite.cached("classification", "session-1950-qualifying"))
		require.FileExists(suite.T(), suite.cached("classification", "session-1950-race"))
	})

	suite.Run("A failed calendar skips its season and not the others", func() {
		r, err := warm(context.Background(), suite.conn, scope{series: []string{"FIA Formula One World Championship"}, from: 1951})

		require.NoError(suite.T(), err)
		require.Len(suite.T(), r.failures, 1)
		require.Equal(suite.T(), "season-2025", r.failures[0].uuid)
		require.FileExists(suite.T(), suite.cached("classification", "session-1951-race"))
	})

	suite.Run("A second run is served from the cache, bar the calls that failed", func() {
		warmed := scope{series: []string{"FIA Formula One World Championship"}, from: 1950, to: 1951}

		_, err := warm(context.Background(), suite.conn, warmed)
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), 7, suite.network.calls)

		r, err := warm(context.Background(), suite.conn, warmed)
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), 8, suite.network.calls, "only the failed qualifying is fetched again")
		require.Equal(suite.T(), count{walked: 3, failed: 1}, *r.counts["classification"])
	})

	suite.Run("An unknown series name fails the run before anything below the series", func() {
		_, err := warm(context.Background(), suite.conn, scope{series: []string{"FIA Formula One World Championship", "Formula 1"}})

		require.ErrorContains(suite.T(), err, `no series named "Formula 1"`)
		require.Equal(suite.T(), 1, suite.network.calls)
		require.NoDirExists(suite.T(), filepath.Join(suite.cacheDir, "seasons"))
	})
}

func (suite *WarmUnitTestSuite) TestPrintReport() {
	failed := func(message string) *report {
		r := newReport()
		r.count("classification").walked++
		r.failures = append(r.failures, failure{endpoint: "classification", target: "a session", uuid: "session", err: errors.New(message)})

		return r
	}

	suite.Run("A lone error ending in a newline is one line", func() {
		var out bytes.Buffer

		printReport(&out, failed("validation errors: /details/9/nationality: type should be object, got null\n"), 0)

		require.Contains(suite.T(), out.String(), "got null\n")
		require.NotContains(suite.T(), out.String(), "lines)")
	})

	suite.Run("Further errors are counted, not printed", func() {
		var out bytes.Buffer

		printReport(&out, failed("validation errors: first\nsecond\nthird\n"), 0)

		require.Contains(suite.T(), out.String(), "validation errors: first (+2 lines)\n")
		require.NotContains(suite.T(), out.String(), "second")
	})
}

func (suite *WarmUnitTestSuite) TestScopeIncludes() {
	suite.Run("Zero bounds include every year", func() {
		require.True(suite.T(), scope{}.includes(1950))
	})

	suite.Run("Both bounds are inclusive", func() {
		s := scope{from: 1950, to: 1951}

		require.True(suite.T(), s.includes(1950))
		require.True(suite.T(), s.includes(1951))
		require.False(suite.T(), s.includes(1949))
		require.False(suite.T(), s.includes(1952))
	})
}
