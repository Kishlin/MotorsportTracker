package main

import (
	"context"
	"fmt"
	"os"
	"strings"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/events"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/domain/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/env"
	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/logger"
)

func main() {
	err := env.LoadEnv()
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error loading environment variables: %v\n", err)
		os.Exit(1)
	}

	logger.SetupSlog()

	if len(os.Args) < 2 {
		printUsage()
		os.Exit(1)
	}

	subcommand := os.Args[1]
	args := os.Args[2:]

	arguments, options := parseArgs(args)

	registry := dependencyinjection.NewServicesRegistry(
		connector.NewDefaultConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewQueueFactory(),
	)
	defer registry.Close()

	ctx := context.Background()

	var intent scrapping.Intent
	var handler messaging.Handler

	switch subcommand {
	case series.ScrapeSeriesIntentName:
		intent = series.NewScrapSeriesIntent()
		handler = series.NewScrapSeriesHandler(registry.GetCoreDatabase(ctx), registry.GetCachedConnector(ctx))
	case seasons.ScrapeSeasonsIntentName:
		intent = seasons.NewScrapSeasonsIntent()
		handler = seasons.NewScrapSeasonsHandler(registry.GetCoreDatabase(ctx), registry.GetCachedConnector(ctx))
	case events.ScrapeEventsIntentName:
		intent = events.NewScrapEventsIntent()
		handler = events.NewScrapEventsHandler()
	default:
		fmt.Fprintf(os.Stderr, "Unknown subcommand: %s\n\n", subcommand)
		printUsage()
		os.Exit(1)
	}

	message, err := intent.ToMessage(arguments, options)
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error creating message for intent %s: %v\n", subcommand, err)
		os.Exit(1)
	}

	err = handler.Handle(ctx, message)

	if err != nil {
		fmt.Fprintf(os.Stderr, "handling intent %s: %v\n", subcommand, err)
		os.Exit(1)
	}

	fmt.Print("Successfully processed command")
	os.Exit(0)
}

func printUsage() {
	fmt.Println("Usage: motorsport-tracker <subcommand> [arguments...]")
	fmt.Println()
	fmt.Println("Processes the intent right away, without waiting for the queue.")
	fmt.Println()
	fmt.Println("Subcommands:")
	fmt.Println("  series                    Scrape all available series")
	fmt.Println("  seasons <series>          Scrape seasons for a specific series")
	fmt.Println("  events <series> <season>  Scrape events for a specific series and season")
	fmt.Println()
	fmt.Println("Examples:")
	fmt.Println("  motorsport-tracker series")
	fmt.Println("  motorsport-tracker seasons \"Formula One\"")
	fmt.Println("  motorsport-tracker events \"Formula One\" \"2025\"")
}

// parseArgs converts command-line arguments into separate arguments and options.
func parseArgs(args []string) (arguments []string, options map[string]string) {
	arguments = make([]string, 0) // Initialize empty slice
	options = make(map[string]string)

	optionsEnded := false

	for _, arg := range args {
		// Handle the Unix convention: -- signals end of options
		if arg == "--" {
			optionsEnded = true
			continue
		}

		if optionsEnded {
			arguments = append(arguments, arg)
			continue
		}

		if strings.HasPrefix(arg, "--") {
			parts := strings.SplitN(arg[2:], "=", 2)
			if len(parts) == 2 {
				options[parts[0]] = parts[1]
			} else {
				options[parts[0]] = "true"
			}
		} else if strings.HasPrefix(arg, "-") {
			if len(arg) > 2 && arg[2] == '=' {
				options[arg[1:2]] = arg[3:]
			} else {
				options[arg[1:]] = "true"
			}
		} else {
			// Not an option, treat as positional argument
			arguments = append(arguments, arg)
		}
	}

	return arguments, options
}
