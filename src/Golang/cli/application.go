package cli

import (
	"fmt"
	"strings"
)

// Run parses the arguments, validates them, and executes the command.
func Run(command Command, args []string) error {
	arguments, options := parseArgs(args)

	// Validate the arguments and options
	if err := command.Validate(arguments, options); err != nil {
		return fmt.Errorf("invalid arguments: %s", err.Error())
	}

	// Execute the command
	if err := command.Execute(arguments, options); err != nil {
		return fmt.Errorf("execution failed: %s", err.Error())
	}

	return nil
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
