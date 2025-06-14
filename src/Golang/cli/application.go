package cli

import (
	"fmt"
	"os"
	"strings"
)

// Application represents a CLI application that can handle commands.
type Application struct {
	// Name is the name of the application.
	Name string

	// Version is the version of the application.
	Version string

	// Description provides information about what the application does.
	Description string

	// Commands is a map of command names to their implementations.
	Commands map[string]Command
}

// NewApplication creates a new Application with the given name, version, and description.
func NewApplication(name, version, description string) *Application {
	return &Application{
		Name:        name,
		Version:     version,
		Description: description,
		Commands:    make(map[string]Command),
	}
}

// AddCommand registers a command with the application.
func (app *Application) AddCommand(cmd Command) {
	config := cmd.Configure()
	app.Commands[config.Name] = cmd
}

// Run executes the application with the provided arguments.
func (app *Application) Run(args []string) error {
	if len(args) < 2 {
		app.printHelp()
		return fmt.Errorf("no command specified")
	}

	cmdName := args[1]
	cmd, exists := app.Commands[cmdName]

	if !exists {
		app.printHelp()
		return fmt.Errorf("unknown command: %s", cmdName)
	}

	// Parse arguments into a map
	argMap := parseArgs(args[2:])

	// Validate arguments
	if err := cmd.Validate(argMap); err != nil {
		return fmt.Errorf("invalid arguments: %v", err)
	}

	// Execute the command
	return cmd.Execute(argMap)
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

// printHelp is an internal method to display help information.
func (app *Application) printHelp() {
	fmt.Printf("%s (v%s)\n", app.Name, app.Version)
	fmt.Printf("%s\n\n", app.Description)

	fmt.Println("Available commands:")
	for name, cmd := range app.Commands {
		config := cmd.Configure()
		fmt.Printf("  %s\t%s\n", name, config.Description)
	}

	fmt.Println("\nUsage:")
	fmt.Printf("  %s [command] [options]\n", os.Args[0])
	fmt.Println("\nFor more details on a specific command, run:")
	fmt.Printf("  %s [command] --help\n", os.Args[0])
}
