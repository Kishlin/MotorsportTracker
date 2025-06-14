package main

import (
	"fmt"
	"os"
	"strings"

	"github.com/kishlin/MotorsportTracker/src/Golang/scrapping/series"
)

// parseArgs converts command-line arguments into a map.
// This function is extracted from the cli package to make this binary independent.
func parseArgs(args []string) map[string]string {
	result := make(map[string]string)

	for _, arg := range args {
		if strings.HasPrefix(arg, "--") {
			parts := strings.SplitN(arg[2:], "=", 2)
			if len(parts) == 2 {
				result[parts[0]] = parts[1]
			} else {
				result[parts[0]] = "true"
			}
		} else if strings.HasPrefix(arg, "-") {
			if len(arg) > 2 && arg[2] == '=' {
				result[arg[1:2]] = arg[3:]
			} else {
				result[arg[1:]] = "true"
			}
		}
	}

	return result
}

func main() {
	// Create the command directly
	cmd := series.NewScrapSeriesCommand()

	// Parse arguments (skipping the program name)
	argMap := parseArgs(os.Args[1:])

	// Validate the arguments
	if err := cmd.Validate(argMap); err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	// Execute the command
	if err := cmd.Execute(argMap); err != nil {
		fmt.Fprintf(os.Stderr, "Error: %v\n", err)
		os.Exit(1)
	}

	fmt.Println("Series scrapping intent has been successfully published.")
}
