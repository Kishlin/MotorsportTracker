package main

import (
	"fmt"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping"
	"os"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
)

const (
	appName        = "ScrappingIntentPublisher"
	appVersion     = "1.0.0"
	appDescription = "Application for publishing scrapping intents to a queue"
)

func main() {
	// Create a new application
	app := cli.NewApplication(appName, appVersion, appDescription)

	scrapping.PopulateCommands(app)

	// Run the application
	if err := app.Run(os.Args); err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}
}
