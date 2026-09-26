package main

import (
	"context"
	"time"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
)

// throttledConnector paces the requests that reach motorsportstats. It sits below the cache decorator,
// so a cache hit never waits, and it counts the requests it lets through for the report.
//
// A new connector method needs a line here too; the compiler says so, since the cache decorator only
// accepts a complete Connector.
type throttledConnector struct {
	inner connector.Connector
	delay time.Duration
	calls int
}

func newThrottledConnector(inner connector.Connector, delay time.Duration) *throttledConnector {
	return &throttledConnector{
		inner: inner,
		delay: delay,
	}
}

func (t *throttledConnector) GetSeries(ctx context.Context) ([]byte, error) {
	t.wait()

	return t.inner.GetSeries(ctx)
}

func (t *throttledConnector) GetSeasons(ctx context.Context, seriesUUID string) ([]byte, error) {
	t.wait()

	return t.inner.GetSeasons(ctx, seriesUUID)
}

func (t *throttledConnector) GetCalendar(ctx context.Context, seasonUUID string) ([]byte, error) {
	t.wait()

	return t.inner.GetCalendar(ctx, seasonUUID)
}

func (t *throttledConnector) GetClassification(ctx context.Context, sessionUUID string) ([]byte, error) {
	t.wait()

	return t.inner.GetClassification(ctx, sessionUUID)
}

func (t *throttledConnector) wait() {
	t.calls++

	time.Sleep(t.delay)
}
