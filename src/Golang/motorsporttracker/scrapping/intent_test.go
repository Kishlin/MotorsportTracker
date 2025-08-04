package scrapping

import (
	"testing"
)

func TestScrapping_BaseIntentValidate(t *testing.T) {
	tests := []struct {
		name        string
		config      IntentConfig
		arguments   []string
		options     map[string]string
		expectError bool
		errorMsg    string
	}{
		{
			name: "valid Intent with no requirements",
			config: IntentConfig{
				Name:        "test",
				Description: "Test Intent",
				Arguments:   []Argument{},
				Options:     []Option{},
			},
			arguments:   []string{"arg1", "arg2"},
			options:     map[string]string{"verbose": "true"},
			expectError: false,
		},
		{
			name: "valid Intent with required arguments satisfied",
			config: IntentConfig{
				Name: "scrap",
				Arguments: []Argument{
					{Name: "Intent", Required: true},
					{Name: "subIntent", Required: true},
					{Name: "year", Required: false},
				},
			},
			arguments:   []string{"scrap", "series", "2023"},
			options:     map[string]string{},
			expectError: false,
		},
		{
			name: "no arguments when required",
			config: IntentConfig{
				Name: "scrap",
				Arguments: []Argument{
					{Name: "Intent", Required: true},
				},
			},
			arguments:   []string{},
			options:     map[string]string{},
			expectError: true,
			errorMsg:    "expected at least 1 arguments, got 0",
		},
		{
			name: "missing required arguments",
			config: IntentConfig{
				Name: "scrap",
				Arguments: []Argument{
					{Name: "Intent", Required: true},
					{Name: "subIntent", Required: true},
					{Name: "year", Required: false},
				},
			},
			arguments:   []string{"scrap"}, // Missing subIntent
			options:     map[string]string{},
			expectError: true,
			errorMsg:    "expected at least 2 arguments, got 1",
		},
		{
			name: "multiple required arguments with some optional",
			config: IntentConfig{
				Name: "process",
				Arguments: []Argument{
					{Name: "action", Required: true},
					{Name: "target", Required: true},
					{Name: "source", Required: false},
					{Name: "destination", Required: false},
				},
			},
			arguments:   []string{"process", "convert"}, // Only 2 args, but only 2 required
			options:     map[string]string{},
			expectError: false,
		},
		{
			name: "excess arguments with required arguments satisfied",
			config: IntentConfig{
				Name: "flexible",
				Arguments: []Argument{
					{Name: "Intent", Required: true},
				},
			},
			arguments:   []string{"Intent", "extra1", "extra2", "extra3"}, // More args than required
			options:     map[string]string{},
			expectError: false, // Should be valid - we only check minimum required
		},
		{
			name: "valid options with values",
			config: IntentConfig{
				Name: "scrap",
				Options: []Option{
					{Name: "series", RequiresValue: true},
					{Name: "verbose", RequiresValue: false},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"series": "f1", "verbose": "true"},
			expectError: false,
		},
		{
			name: "option requires value but provided as flag (long form)",
			config: IntentConfig{
				Name: "scrap",
				Options: []Option{
					{Name: "series", RequiresValue: true},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"series": "true"}, // Provided as flag
			expectError: true,
			errorMsg:    "option '--series' requires a value",
		},
		{
			name: "option requires value but provided as flag (short form)",
			config: IntentConfig{
				Name: "scrap",
				Options: []Option{
					{Name: "series", ShortName: "s", RequiresValue: true},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"s": "true"}, // Provided as short flag
			expectError: true,
			errorMsg:    "option '-s' requires a value",
		},
		{
			name: "boolean flags are allowed to be true",
			config: IntentConfig{
				Name: "test",
				Options: []Option{
					{Name: "verbose", RequiresValue: false},
					{Name: "debug", RequiresValue: false},
					{Name: "quiet", ShortName: "q", RequiresValue: false},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"verbose": "true", "debug": "true", "q": "true"},
			expectError: false,
		},
		{
			name: "option with value provided correctly",
			config: IntentConfig{
				Name: "deploy",
				Options: []Option{
					{Name: "environment", ShortName: "e", RequiresValue: true},
					{Name: "config", RequiresValue: true},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"environment": "production", "config": "/path/to/config.yml"},
			expectError: false,
		},
		{
			name: "option with empty value is valid (different from flag)",
			config: IntentConfig{
				Name: "test",
				Options: []Option{
					{Name: "message", RequiresValue: true},
				},
			},
			arguments:   []string{},
			options:     map[string]string{"message": ""}, // Empty string value
			expectError: false,
		},
		{
			name: "mixed valid scenario",
			config: IntentConfig{
				Name: "scrap",
				Arguments: []Argument{
					{Name: "Intent", Required: true},
					{Name: "subIntent", Required: true},
					{Name: "year", Required: false},
				},
				Options: []Option{
					{Name: "series", ShortName: "s", RequiresValue: true},
					{Name: "verbose", ShortName: "v", RequiresValue: false},
					{Name: "output", RequiresValue: true},
				},
			},
			arguments:   []string{"scrap", "series", "2023"},
			options:     map[string]string{"s": "f1", "verbose": "true", "output": "/tmp/result.json"},
			expectError: false,
		},
	}

	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			cmd := &BaseIntent{Config: tt.config}
			err := cmd.Validate(tt.arguments, tt.options)

			if tt.expectError {
				if err == nil {
					t.Errorf("expected error but got none")
					return
				}
				if err.Error() != tt.errorMsg {
					t.Errorf("expected error message %q, got %q", tt.errorMsg, err.Error())
				}
			} else {
				if err != nil {
					t.Errorf("expected no error but got: %v", err)
				}
			}
		})
	}
}

// Benchmark to ensure Validate is performant with Intents that simulate real-world usage
func BenchmarkScrapping_BaseIntentValidate(b *testing.B) {
	IntentConfig := IntentConfig{
		Name: "scrap",
		Arguments: []Argument{
			{Name: "Intent", Required: true},
			{Name: "subIntent", Required: true},
			{Name: "year", Required: false},
		},
		Options: []Option{
			{Name: "series", ShortName: "s", RequiresValue: true},
			{Name: "verbose", ShortName: "v", RequiresValue: false},
			{Name: "output", RequiresValue: true},
		},
	}

	arguments := []string{"scrap", "series", "2023"}
	options := map[string]string{"s": "f1", "verbose": "true", "output": "/tmp/result.json"}

	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		cmd := &BaseIntent{Config: IntentConfig}
		_ = cmd.Validate(arguments, options)
	}
}

// Benchmark to ensure Validate is performant when there are errors
func BenchmarkScrapping_BaseIntentValidateOnMissingKeys(b *testing.B) {
	IntentConfig := IntentConfig{
		Name: "scrap",
		Arguments: []Argument{
			{Name: "Intent", Required: true},
			{Name: "subIntent", Required: true},
			{Name: "year", Required: false},
		},
		Options: []Option{
			{Name: "series", ShortName: "s", RequiresValue: true},
			{Name: "verbose", ShortName: "v", RequiresValue: false},
			{Name: "output", RequiresValue: true},
		},
	}

	arguments := []string{"scrap"}
	options := map[string]string{"s": "f1", "verbose": "true", "output": "/tmp/result.json"}

	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		cmd := &BaseIntent{Config: IntentConfig}
		_ = cmd.Validate(arguments, options)
	}
}
