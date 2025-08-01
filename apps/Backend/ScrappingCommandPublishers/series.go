package main

import (
	"fmt"
	"os"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/series"
)

func main() {
	cmd := series.NewScrapSeriesCommand()

	err := cli.Run(cmd, os.Args[1:])
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	fmt.Println("Series scrapping intent has been successfully published.")
}
