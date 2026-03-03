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

	intent, err := registration.GetIntent(subcommand)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "%v\n\n", err)
		printUsage()
		os.Exit(1)
	}

	arguments, options := application.ParseArgs(args)

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
