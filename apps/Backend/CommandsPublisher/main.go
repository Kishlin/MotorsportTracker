package main

import (
	"fmt"
	"log/slog"
	"os"
	"strings"

	dependencyinjection "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection/infrastructure"
	calendar "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/calendar/infrastructure"
	classification "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/classification/infrastructure"
	seasons "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/infrastructure"
	series "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/infrastructure"
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	logger "github.com/kishlin/MotorsportTracker/src/Golang/shared/logger/infrastructure"
)

func main() {
	err := env.LoadEnv()
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error loading environment variables: %v\n", err)
		os.Exit(1)
	}

	logger.SetupSlog()

	if len(os.Args) < 2 {
		printUsage()
		os.Exit(1)
	}

	subcommand := os.Args[1]
	args := os.Args[2:]

	var intent application.Intent

	switch subcommand {
	case series.ScrapeSeriesIntentName:
		intent = series.NewScrapSeriesIntent()
	case seasons.ScrapeSeasonsForSeriesKeywordIntentName:
		intent = seasons.NewScrapeSeasonsForSeriesKeywordIntent()
	case seasons.ScrapeSeasonsForSeriesIDIntentName:
		intent = seasons.NewScrapeSeasonsForSeriesIDIntent()
	case seasons.ScrapeSeasonsForAllSeriesIntentName:
		intent = seasons.NewScrapeSeasonsForAllSeriesIntent()
	case calendar.ScrapeCalendarIntentName:
		intent = calendar.NewScrapCalendarIntent()
	case classification.ScrapeClassificationIntentName:
		intent = classification.NewScrapClassificationIntent()
	default:
		_, _ = fmt.Fprintf(os.Stderr, "Unknown subcommand: %s\n\n", subcommand)
		printUsage()
		os.Exit(1)
	}

	arguments, options := parseArgs(args)

	message, err := intent.ToMessage(arguments, options)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error creating message for intent %s: %v\n", subcommand, err)
		os.Exit(1)
	}

	registry := dependencyinjection.NewServicesRegistry()
	defer registry.Close()

	err = registry.GetIntentsQueue().Send(message)

	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	slog.Info("Intent sent to the queue", "intent", subcommand)
	os.Exit(0)
}

func printUsage() {
	fmt.Println("Usage: scrapping-publisher <subcommand> [arguments...]")
	fmt.Println()
	fmt.Println("Subcommands:")
	fmt.Println("  series                    Scrape all available series")
	fmt.Println("  seasons <series>          Scrape seasons for a specific series")
	fmt.Println("  events <series> <season>  Scrape events for a specific series and season")
	fmt.Println()
	fmt.Println("Examples:")
	fmt.Println("  scrapping-publisher series")
	fmt.Println("  scrapping-publisher seasons \"Formula One\"")
	fmt.Println("  scrapping-publisher events \"Formula One\" \"2025\"")
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
