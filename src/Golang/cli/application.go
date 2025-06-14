package cli

import (
	"errors"
	"fmt"
	"strings"
)

// Run parses the arguments, validates them, and executes the command.
func Run(command Command, args []string) error {
	argMap := parseArgs(args)

	// Validate the arguments
	if err := command.Validate(argMap); err != nil {
		return errors.New(fmt.Sprintf("invalid arguments: %s", err.Error()))
	}

	// Execute the command
	if err := command.Execute(argMap); err != nil {
		return errors.New(fmt.Sprintf("execution failed: %s", err.Error()))
	}

	return nil
}

// parseArgs converts command-line arguments into a map.
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
