package main

import (
	"fmt"
	"os"

	"github.com/kishlin/MotorsportTracker/src/Golang/cli"
	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/seasons"
)

func main() {
	cmd := seasons.NewScrapSeasonsCommand()

	err := cli.Run(cmd, os.Args[1:])
	if err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	fmt.Println("Seasons scrapping intent has been successfully published.")
}
