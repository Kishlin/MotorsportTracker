package main

import (
	"fmt"
	"log/slog"
	"os"

	dependencyinjection "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection/infrastructure"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/registration"
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	logger "github.com/kishlin/MotorsportTracker/src/Golang/shared/logger/infrastructure"
)

func main() {
	os.Exit(run())
}

// run holds what main would, returning the exit code instead of calling os.Exit, which would skip the
// deferred registry.Close().
func run() int {
	err := env.LoadEnv()
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error loading environment variables: %v\n", err)
		return 1
	}

	logger.SetupSlog()

	if len(os.Args) < 2 {
		printUsage()
		return 1
	}

	subcommand := os.Args[1]
	args := os.Args[2:]

	intent, err := registration.GetIntent(subcommand)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "%v\n\n", err)
		printUsage()
		return 1
	}

	arguments, options := application.ParseArgs(args)

	message, err := intent.ToMessage(arguments, options)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error creating message for intent %s: %v\n", subcommand, err) //nolint:gosec // G705: CLI stderr output, not a web context
		return 1
	}

	registry := dependencyinjection.NewServicesRegistry()
	defer registry.Close()

	err = registry.GetIntentsQueue().Send(message)

	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		return 1
	}

	slog.Info("Intent sent to the queue", "intent", subcommand) //nolint:gosec // G706: slog structured logging safely isolates the value in a field
	return 0
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
