package scrapping

import (
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/queue"
)

// Intent represents a scrapping intent that can be validated and converted to a Intent.
type Intent interface {
	Validate(arguments []string, options map[string]string) error

	ToMessage() queue.Message
}

// IntentConfig holds the configuration for a Intent.
type IntentConfig struct {
	// Name is the identifier of the Intent (e.g., "scrap:series").
	Name string

	// Description provides information about what the Intent does.
	Description string

	// Arguments are positional arguments for the Intent.
	Arguments []Argument

	// Options are named optional arguments for the Intent.
	Options []Option
}

// Argument represents a positional argument for a Intent.
type Argument struct {
	// Name is the identifier of the argument.
	Name string

	// Description provides information about the argument.
	Description string

	// Required indicates if the argument must be provided.
	Required bool
}

// Option represents an optional named argument for a Intent.
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

// BaseIntent provides a basic implementation of Intent that other Intents can embed.
type BaseIntent struct {
	Config IntentConfig
}

// Validate checks if all required arguments are provided and options are properly formatted.
func (c *BaseIntent) Validate(arguments []string, options map[string]string) error {
	// Check required arguments
	requiredArgCount := 0
	for _, arg := range c.Config.Arguments {
		if arg.Required {
			requiredArgCount++
		}
	}

	if len(arguments) < requiredArgCount {
		return fmt.Errorf("expected at least %d arguments, got %d", requiredArgCount, len(arguments))
	}

	// Check options that require values
	for _, opt := range c.Config.Options {
		if opt.RequiresValue {
			if value, exists := options[opt.Name]; exists && value == "true" {
				// This means the option was provided as a flag but requires a value
				return fmt.Errorf("option '--%s' requires a value", opt.Name)
			}
			// Check short name too
			if opt.ShortName != "" {
				if value, exists := options[opt.ShortName]; exists && value == "true" {
					return fmt.Errorf("option '-%s' requires a value", opt.ShortName)
				}
			}
		}
	}

	return nil
}
