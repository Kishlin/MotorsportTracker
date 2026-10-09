package main

import (
	"context"
	"flag"
	"fmt"
	"io"
	"os"
	"strings"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/dependencyinjection/infrastructure"
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

	registry := motorsportstats.NewServicesRegistry()

	// Without the filesystem layer the walk would fill the client-cache database alone, and
	// etc/ConnectorCache/ is the whole point of the run.
	useFSCache := strings.ToLower(os.Getenv("USE_FS_CACHE")) == "true"
	if useFSCache == false {
		_, _ = fmt.Fprintln(os.Stderr, "USE_FS_CACHE is not true, the warmer would leave etc/ConnectorCache/ untouched")
		os.Exit(1)
	}

	ctx := context.Background()

	r, err := warm(ctx, registry.GetConnector(ctx), scope{series: series, from: *from, to: *to})

	// Closed by hand rather than deferred: the os.Exit calls below would skip a deferred Close.
	registry.Close()

	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error warming the cache: %v\n", err)
		os.Exit(1)
	}

	printReport(os.Stdout, r)

	if len(r.failures) > 0 {
		os.Exit(1)
	}
}

func printReport(w io.Writer, r *report) {
	for _, endpoint := range r.endpoints {
		c := r.counts[endpoint]
		_, _ = fmt.Fprintf(w, "%-16s %6d walked %6d failed\n", endpoint, c.walked, c.failed)
	}

	if len(r.failures) == 0 {
		return
	}

	// A failed payload is not cached, so the next run retries it. Schema validation failures land here
	// too; make go-run APP=ApiCanary explains those in more detail.
	_, _ = fmt.Fprintf(w, "\n%d failures, not cached:\n", len(r.failures))

	for _, f := range r.failures {
		// The connector ends every validation error with a newline, so a lone error must not count the
		// empty remainder as a further line.
		message, rest, multiline := strings.Cut(strings.TrimRight(f.err.Error(), "\n"), "\n")
		if multiline {
			message += fmt.Sprintf(" (+%d lines)", strings.Count(rest, "\n")+1)
		}

		_, _ = fmt.Fprintf(w, "  %s %s (%s)\n    %s\n", f.endpoint, f.target, f.uuid, message)
	}
}
