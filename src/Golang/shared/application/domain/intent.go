package domain

import (
	"fmt"

	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

// Intent represents a scrapping intent that can be validated and converted to an Intent.
type Intent interface {
	ToMessage(arguments []string, options map[string]string) (messaging.Message, error)
}

// IntentConfig holds the configuration for an Intent.
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

// Argument represents a positional argument for an Intent.
type Argument struct {
	// Name is the identifier of the argument.
	Name string

	// Description provides information about the argument.
	Description string
}

// Option represents an optional named argument for an Intent.
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

func (c *BaseIntent) ToMessage(arguments []string, options map[string]string) (messaging.Message, error) {
	err := c.validate(arguments, options)
	if err != nil {
		return messaging.Message{}, fmt.Errorf("valdating arguments and options: %w", err)
	}

	metadata := make(map[string]string)

	// Map all arguments to their configured names (validation ensures we have enough)
	for i, arg := range c.Config.Arguments {
		metadata[arg.Name] = arguments[i]
	}

	// Only include options that are defined in the config
	for _, configOption := range c.Config.Options {
		// Check both long name and short name
		if value, exists := options[configOption.Name]; exists {
			metadata[configOption.Name] = value
		} else if configOption.ShortName != "" {
			if value, exists := options[configOption.ShortName]; exists {
				metadata[configOption.Name] = value // Store using long name
			}
		}

		if _, exists := metadata[configOption.Name]; exists && !configOption.RequiresValue {
			// If the option does not require a value, it's a boolean flag
			// Then, unless "false" was explicitly provided, we set it to "true"
			if metadata[configOption.Name] != "false" {
				metadata[configOption.Name] = "true"
			}
		}
	}

	return messaging.Message{
		Type:     c.Config.Name,
		Metadata: metadata,
	}, nil
}

// validate checks if all required arguments are provided and options are properly formatted.
func (c *BaseIntent) validate(arguments []string, options map[string]string) error {
	// Check that all positional arguments are provided (all arguments are now required)
	if len(arguments) < len(c.Config.Arguments) {
		return fmt.Errorf("expected %d arguments, got %d", len(c.Config.Arguments), len(arguments))
	}

	// Check options that require values
	for _, opt := range c.Config.Options {
		if opt.RequiresValue {
			if value, exists := options[opt.Name]; exists && value == "" {
				// This means the option was provided as a flag but requires a value
				return fmt.Errorf("option '--%s' requires a value", opt.Name)
			}
			// Check short name too
			if opt.ShortName != "" {
				if value, exists := options[opt.ShortName]; exists && value == "" {
					return fmt.Errorf("option '-%s' requires a value", opt.ShortName)
				}
			}
		}
	}

	return nil
}
