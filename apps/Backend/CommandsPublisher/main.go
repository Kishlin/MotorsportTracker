package main

import (
	"fmt"
	"os"
	"strings"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
	"github.com/kishlin/MotorsportTracker/src/Golang/messaging"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/connector"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/events"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series"
)

func main() {
	if len(os.Args) < 2 {
		printUsage()
		os.Exit(1)
	}

	subcommand := os.Args[1]
	args := os.Args[2:]

	var intent scrapping.Intent
	var err error

	switch subcommand {
	case "series":
		intent = series.NewScrapSeriesIntent()
	case "seasons":
		intent = seasons.NewScrapSeasonsIntent()
	case "events":
		intent = events.NewScrapEventsIntent()
	default:
		fmt.Fprintf(os.Stderr, "Unknown subcommand: %s\n\n", subcommand)
		printUsage()
		os.Exit(1)
	}

	arguments, options := parseArgs(args)

	err = intent.Validate(arguments, options)
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error validating arguments: %v\n", err)
		os.Exit(1)
	}

	registry := dependencyinjection.NewServicesRegistry(
		connector.NewDefaultConnectorFactory(),
		database.NewDatabaseFactory(),
		messaging.NewSQSQueueFactory(),
	)
	defer registry.Close()

	err = registry.GetIntentsQueue().Send(
		intent.ToMessage(),
	)
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	fmt.Printf("Intent %s successfully sent to the queue.\n", subcommand)
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
