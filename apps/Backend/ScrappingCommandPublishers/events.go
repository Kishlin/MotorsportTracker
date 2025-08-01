package main

import (
	"fmt"
	"os"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/events"
)

func main() {
	cmd := events.NewScrapEventsCommand()

	err := cli.Run(cmd, os.Args[1:])
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	fmt.Println("Events scrapping intent has been successfully published.")
}
