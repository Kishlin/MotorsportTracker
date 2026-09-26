package main

import (
	"context"
	"encoding/json"
	"fmt"
	"log/slog"
	"strings"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// endpoint is one connector call keyed by an upstream UUID.
type endpoint struct {
	name  string
	fetch func(ctx context.Context, uuid string) ([]byte, error)
}

// sessionEndpoints are fetched once for every session with results. Warming a new session-level
// endpoint is one line here once the connector has the method.
func sessionEndpoints(conn connector.Connector) []endpoint {
	return []endpoint{
		{name: "classification", fetch: conn.GetClassification},
	}
}

// scope is what a run warms: the named series, and within them the seasons whose year falls in
// [from, to]. A zero bound is no bound.
type scope struct {
	series []string
	from   int
	to     int
}

func (s scope) includes(year int) bool {
	return (s.from == 0 || year >= s.from) && (s.to == 0 || year <= s.to)
}

// warm walks series -> seasons -> calendar -> sessions, taking every identifier from the previous
// payload. Caching is the connector's business: warm only fetches. The scraping use cases cannot be
// reused here, as they resolve their identifiers out of the core database.
//
// A failed call is recorded and its subtree skipped, and the walk carries on. The returned error is
// kept for a run that cannot start: no series payload, or a series name it does not hold.
func warm(ctx context.Context, conn connector.Connector, s scope) (*report, error) {
	r := newReport()

	allSeries, ok := fetchAndParse[[]*motorsportstats.Series](r, "series", "all series", "all", func() ([]byte, error) {
		return conn.GetSeries(ctx)
	})
	if ok == false {
		return r, fmt.Errorf("getting series: %w", r.failures[0].err)
	}

	targets := make([]*motorsportstats.Series, 0, len(s.series))
	for _, name := range s.series {
		target := findSeries(allSeries, name)
		if target == nil {
			return r, fmt.Errorf("no series named %q in the series payload", name)
		}

		targets = append(targets, target)
	}

	endpoints := sessionEndpoints(conn)

	for _, series := range targets {
		warmSeries(ctx, conn, s, r, endpoints, series)
	}

	return r, nil
}

func warmSeries(
	ctx context.Context,
	conn connector.Connector,
	s scope,
	r *report,
	endpoints []endpoint,
	series *motorsportstats.Series,
) {
	name := fn.Deref(series.Name, series.UUID)

	seasons, ok := fetchAndParse[[]*motorsportstats.Season](r, "seasons", name, series.UUID, func() ([]byte, error) {
		return conn.GetSeasons(ctx, series.UUID)
	})
	if ok == false {
		return
	}

	for _, season := range seasons {
		if season == nil || s.includes(fn.Deref(season.Year, 0)) == false {
			continue
		}

		warmSeason(ctx, conn, r, endpoints, fmt.Sprintf("%s %d", name, fn.Deref(season.Year, 0)), season)
	}
}

// warmSeason fetches the calendar, then every session endpoint for each session with results. A
// session without results has nothing to fetch yet, and caching its empty payload would freeze it.
func warmSeason(
	ctx context.Context,
	conn connector.Connector,
	r *report,
	endpoints []endpoint,
	label string,
	season *motorsportstats.Season,
) {
	calendar, ok := fetchAndParse[*motorsportstats.Calendar](r, "calendar", label, season.UUID, func() ([]byte, error) {
		return conn.GetCalendar(ctx, season.UUID)
	})
	if ok == false || calendar == nil {
		return
	}

	sessions := 0

	for _, event := range calendar.Events {
		if event == nil {
			continue
		}

		for _, session := range event.Sessions {
			if session == nil || fn.Deref(session.HasResults, false) == false {
				continue
			}

			sessions++

			target := fmt.Sprintf(
				"%s / %s / %s",
				label,
				strings.TrimSpace(fn.Deref(event.Name, "unnamed event")),
				strings.TrimSpace(fn.Deref(session.Name, "unnamed session")),
			)

			for _, e := range endpoints {
				r.fetch(e.name, target, session.UUID, func() ([]byte, error) {
					return e.fetch(ctx, session.UUID)
				})
			}
		}
	}

	slog.Info("Warmed season", "season", label, "sessions", sessions)
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

// report counts, per endpoint, the calls the walk made and those that failed.
type report struct {
	endpoints []string
	counts    map[string]*count
	failures  []failure
}

type count struct {
	walked int
	failed int
}

type failure struct {
	endpoint string
	target   string
	uuid     string
	err      error
}

func newReport() *report {
	return &report{counts: map[string]*count{}}
}

func (r *report) count(endpoint string) *count {
	c, seen := r.counts[endpoint]
	if seen == false {
		c = &count{}
		r.counts[endpoint] = c
		r.endpoints = append(r.endpoints, endpoint)
	}

	return c
}

// fetch makes one call and records its outcome. The payload is returned for the levels that discover
// what to walk next; the leaves discard it, having warmed the cache by fetching.
func (r *report) fetch(endpoint string, target string, uuid string, get func() ([]byte, error)) ([]byte, bool) {
	r.count(endpoint).walked++

	payload, err := get()
	if err != nil {
		r.fail(endpoint, target, uuid, err)
		return nil, false
	}

	return payload, true
}

func (r *report) fail(endpoint string, target string, uuid string, err error) {
	r.count(endpoint).failed++
	r.failures = append(r.failures, failure{endpoint: endpoint, target: target, uuid: uuid, err: err})

	slog.Error("Warming failed", "endpoint", endpoint, "target", target, "uuid", uuid, "error", err)
}

// fetchAndParse fetches a payload the walk descends into. One that does not parse is a failure too,
// though the connector has cached its bytes by then.
func fetchAndParse[T any](r *report, endpoint string, target string, uuid string, get func() ([]byte, error)) (T, bool) {
	var value T

	payload, ok := r.fetch(endpoint, target, uuid, get)
	if ok == false {
		return value, false
	}

	if err := json.Unmarshal(payload, &value); err != nil {
		r.fail(endpoint, target, uuid, fmt.Errorf("unmarshalling %s: %w", endpoint, err))
		return value, false
	}

	return value, true
}
