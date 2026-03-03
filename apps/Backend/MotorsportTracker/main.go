package main

import (
	"context"
	"fmt"
	"os"

	dependencyinjection "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/dependencyinjection/infrastructure"
	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/registration"
	application "github.com/kishlin/MotorsportTracker/src/Golang/shared/application/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	logger "github.com/kishlin/MotorsportTracker/src/Golang/shared/logger/infrastructure"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/infrastructure"
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

	arguments, options := application.ParseArgs(args)

	registry := dependencyinjection.NewServicesRegistry()
	defer registry.Close()

	ctx := context.Background()

	intent, err := registration.GetIntent(subcommand)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "%v\n\n", err)
		printUsage()
		os.Exit(1)
	}

	message, err := intent.ToMessage(arguments, options)
	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "Error creating message for intent %s: %v\n", subcommand, err)
		os.Exit(1)
	}

	handlersList := messaging.NewHandlersList()
	registration.RegisterAllHandlers(ctx, handlersList, registry)

	err = handlersList.HandleMessage(ctx, message)

	if err != nil {
		_, _ = fmt.Fprintf(os.Stderr, "handling intent %s: %v\n", subcommand, err)
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
