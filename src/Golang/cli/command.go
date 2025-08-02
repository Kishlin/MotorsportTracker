package cli

import (
	"errors"
	"fmt"
)

// Command represents a CLI command that can be executed.
type Command interface {
	// Validate checks if the provided arguments and options are valid for this command.
	Validate(arguments []string, options map[string]string) error

	// Execute executes the command with the provided arguments and options.
	Execute(arguments []string, options map[string]string) error
}

// CommandConfig holds the configuration for a command.
type CommandConfig struct {
	// Name is the identifier of the command (e.g., "scrap:series").
	Name string

	// Description provides information about what the command does.
	Description string

	// Arguments are positional arguments for the command.
	Arguments []Argument

	// Options are named optional arguments for the command.
	Options []Option
}

// Argument represents a positional argument for a command.
type Argument struct {
	// Name is the identifier of the argument.
	Name string

	// Description provides information about the argument.
	Description string

	// Required indicates if the argument must be provided.
	Required bool
}

// Option represents an optional named argument for a command.
type Option struct {
	// Name is the identifier of the option (e.g., "series").
	Name string

	// ShortName is a single-character alias (e.g., "s").
	ShortName string

	// Description provides information about the option.
	Description string

	// RequiresValue indicates if the option must have a value when provided.
	// If false, the option is a boolean flag.
	RequiresValue bool

	// Default is the default value for the option if not provided.
	Default string
}

// BaseCommand provides a basic implementation of Command that other commands can embed.
type BaseCommand struct {
	Config CommandConfig
}

// Validate checks if all required arguments are provided and options are properly formatted.
func (c *BaseCommand) Validate(arguments []string, options map[string]string) error {
	// Check required arguments
	requiredArgCount := 0
	for _, arg := range c.Config.Arguments {
		if arg.Required {
			requiredArgCount++
		}
	}

	if len(arguments) < requiredArgCount {
		return errors.New(fmt.Sprintf("expected at least %d arguments, got %d", requiredArgCount, len(arguments)))
	}

	// Check options that require values
	for _, opt := range c.Config.Options {
		if opt.RequiresValue {
			if value, exists := options[opt.Name]; exists && value == "true" {
				// This means the option was provided as a flag but requires a value
				return errors.New(fmt.Sprintf("option '--%s' requires a value", opt.Name))
			}
			// Check short name too
			if opt.ShortName != "" {
				if value, exists := options[opt.ShortName]; exists && value == "true" {
					return errors.New(fmt.Sprintf("option '-%s' requires a value", opt.ShortName))
				}
			}
		}
	}

	return nil
}
