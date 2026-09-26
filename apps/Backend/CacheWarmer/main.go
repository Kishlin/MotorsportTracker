package main

import (
	"context"
	"flag"
	"fmt"
	"io"
	"os"
	"path/filepath"
	"strings"
	"time"

	connector "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/connector/infrastructure"
	cache "github.com/kishlin/MotorsportTracker/src/Golang/shared/cache/infrastructure"
	client "github.com/kishlin/MotorsportTracker/src/Golang/shared/client/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	logger "github.com/kishlin/MotorsportTracker/src/Golang/shared/logger/infrastructure"
)

// seriesNames collects the repeatable --series flag.
type seriesNames []string

func (n *seriesNames) String() string {
	return strings.Join(*n, ", ")
}

func (n *seriesNames) Set(name string) error {
	*n = append(*n, name)

	return nil
}

func main() {
	var series seriesNames
	flag.Var(&series, "series", "exact name of a series to warm, as motorsportstats spells it; repeatable")
	from := flag.Int("from", 0, "first season year to warm; 0 for no lower bound")
	to := flag.Int("to", 0, "last season year to warm; 0 for no upper bound")
	delay := flag.Duration("delay", time.Second, "pause before each request that reaches motorsportstats")
	flag.Parse()

	if len(series) == 0 {
		_, _ = fmt.Fprintln(os.Stderr, "at least one --series is required")
		os.Exit(2)
	}

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
		_, _ = fmt.Fprintln(os.Stderr, "PROJECT_DIR environment variable is not set, the cache cannot be located")
		os.Exit(1)
	}

	// Only the filesystem cache, and not the ServicesRegistry gateway: the warmer needs no database. The
	// throttle sits below the cache so that hits are never delayed.
	network := newThrottledConnector(connector.NewConnectorUsingClient(client.NewClient(host)), *delay)
	conn := connector.NewCachedConnector(
		network,
		cache.NewFileSystemCache(filepath.Join(projectDir, "etc", "ConnectorCache"), ".json"),
	)

	r, err := warm(context.Background(), conn, scope{series: series, from: *from, to: *to})
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error warming the cache: %v\n", err)
		os.Exit(1)
	}

	printReport(os.Stdout, r, network.calls)

	if len(r.failures) > 0 {
		os.Exit(1)
	}
}

func printReport(w io.Writer, r *report, networkCalls int) {
	for _, endpoint := range r.endpoints {
		c := r.counts[endpoint]
		_, _ = fmt.Fprintf(w, "%-16s %6d walked %6d failed\n", endpoint, c.walked, c.failed)
	}

	_, _ = fmt.Fprintf(w, "\n%d requests reached motorsportstats, the rest were cache hits\n", networkCalls)

	if len(r.failures) == 0 {
		return
	}

	// A failed payload is not cached, so the next run retries it. Schema validation failures land here
	// too; make run-api-canary explains those in more detail.
	_, _ = fmt.Fprintf(w, "\n%d failures, not cached:\n", len(r.failures))

	for _, f := range r.failures {
		message, rest, multiline := strings.Cut(f.err.Error(), "\n")
		if multiline {
			message += fmt.Sprintf(" (+%d lines)", strings.Count(strings.TrimRight(rest, "\n"), "\n")+1)
		}

		_, _ = fmt.Fprintf(w, "  %s %s (%s)\n    %s\n", f.endpoint, f.target, f.uuid, message)
	}
}
