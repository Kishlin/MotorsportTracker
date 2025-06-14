package cli

import (
	"errors"
	"fmt"
)

// Command represents a CLI command that can be executed.
type Command interface {
	// Configure sets up the command with its name, description, parameters, and options.
	Configure() CommandConfig

	// Execute runs the command with the provided arguments.
	Execute(args map[string]string) error

	// Validate checks if the provided arguments are valid for this command.
	Validate(args map[string]string) error
}

// CommandConfig holds the configuration for a command.
type CommandConfig struct {
	// Name is the identifier of the command (e.g., "scrap:series").
	Name string

	// Description provides information about what the command does.
	Description string

	// Parameters are required arguments for the command.
	Parameters []Parameter

	// Options are optional arguments for the command.
	Options []Option
}

// Parameter represents a required argument for a command.
type Parameter struct {
	// Name is the identifier of the parameter.
	Name string

	// Description provides information about the parameter.
	Description string

	// Required indicates if the parameter must be provided.
	Required bool
}

// Option represents an optional argument for a command.
type Option struct {
	// Name is the identifier of the option (e.g., "series").
	Name string

	// ShortName is a single-character alias (e.g., "s").
	ShortName string

	// Description provides information about the option.
	Description string

	// Required indicates if the option must be provided.
	Required bool

	// Default is the default value for the option if not provided.
	Default string
}

// BaseCommand provides a basic implementation of Command that other commands can embed.
type BaseCommand struct {
	config CommandConfig
}

// Configure returns the command configuration.
func (c *BaseCommand) Configure() CommandConfig {
	return c.config
}

// SetConfig sets the command configuration.
func (c *BaseCommand) SetConfig(config CommandConfig) {
	c.config = config
}

// Validate checks if all required parameters and options are provided.
func (c *BaseCommand) Validate(args map[string]string) error {
	config := c.Configure()

	// Check required parameters
	for _, param := range config.Parameters {
		if param.Required {
			if _, exists := args[param.Name]; !exists {
				return errors.New(fmt.Sprintf("required parameter '%s' is missing", param.Name))
			}
		}
	}

	// Check required options
	for _, opt := range config.Options {
		if opt.Required {
			if _, exists := args[opt.Name]; !exists {
				return errors.New(fmt.Sprintf("required option '--%s' is missing", opt.Name))
			}
		}
	}

	return nil
}
