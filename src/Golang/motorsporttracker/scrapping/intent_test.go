package scrapping

import (
	"testing"
)

func TestBaseIntent_ToMessage_Success_AllArgumentsAndOptions(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A comprehensive test intent",
			Arguments: []Argument{
				{Name: "series", Description: "Series name"},
				{Name: "season", Description: "Season year"},
			},
			Options: []Option{
				{Name: "verbose", Description: "Verbose output", RequiresValue: false},
				{Name: "output", Description: "Output file", RequiresValue: true},
				{Name: "quiet", Description: "Quiet mode", RequiresValue: false},
			},
		},
	}

	// Test with all arguments and options provided
	message, err := intent.ToMessage(
		[]string{"Formula 1", "2023"},
		map[string]string{
			"verbose": "true",
			"output":  "results.json",
			"quiet":   "false",
		},
	)

	if err != nil {
		t.Errorf("ToMessage should not return error: %v", err)
	}

	// Check name is correctly set
	if message.Type != "test-intent" {
		t.Errorf("Expected message type 'test-intent', got '%s'", message.Type)
	}

	// Check all required arguments are there
	if message.Metadata["series"] != "Formula 1" {
		t.Errorf("Expected metadata series 'Formula 1', got '%s'", message.Metadata["series"])
	}
	if message.Metadata["season"] != "2023" {
		t.Errorf("Expected metadata season '2023', got '%s'", message.Metadata["season"])
	}

	// Check all options are there
	if message.Metadata["output"] != "results.json" {
		t.Errorf("Expected metadata output 'results.json', got '%s'", message.Metadata["output"])
	}
	if message.Metadata["verbose"] != "true" {
		t.Errorf("Expected metadata verbose 'true', got '%s'", message.Metadata["verbose"])
	}
	if message.Metadata["quiet"] != "false" {
		t.Errorf("Expected metadata quiet 'false', got '%s'", message.Metadata["quiet"])
	}
}

func TestBaseIntent_ToMessage_Success_NoOptions(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A test intent",
			Arguments: []Argument{
				{Name: "series", Description: "Series name"},
				{Name: "season", Description: "Season year"},
			},
			Options: []Option{
				{Name: "verbose", Description: "Verbose output", RequiresValue: false},
				{Name: "output", Description: "Output file", RequiresValue: true},
			},
		},
	}

	// Test with all required arguments but no options
	message, err := intent.ToMessage(
		[]string{"Formula 1", "2023"},
		map[string]string{}, // No options
	)

	if err != nil {
		t.Errorf("ToMessage should not return error when options are omitted: %v", err)
	}

	// Required arguments should be present
	if message.Metadata["series"] != "Formula 1" {
		t.Errorf("Expected metadata series 'Formula 1', got '%s'", message.Metadata["series"])
	}
	if message.Metadata["season"] != "2023" {
		t.Errorf("Expected metadata season '2023', got '%s'", message.Metadata["season"])
	}

	// Options should not be present when not provided
	if _, exists := message.Metadata["verbose"]; exists {
		t.Error("Option 'verbose' should not be present when not provided")
	}
	if _, exists := message.Metadata["output"]; exists {
		t.Error("Option 'output' should not be present when not provided")
	}
}

func TestBaseIntent_ToMessage_Success_ExtraArgumentsAndOptionsFiltered(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A test intent",
			Arguments: []Argument{
				{Name: "series", Description: "Series name"},
			},
			Options: []Option{
				{Name: "verbose", Description: "Verbose output", RequiresValue: false},
			},
		},
	}

	// Test with extra arguments and options that are not in config
	message, err := intent.ToMessage(
		[]string{"Formula 1", "extra-arg", "another-extra"},
		map[string]string{
			"verbose":     "true",
			"extra-opt":   "should-be-filtered",
			"another-opt": "also-filtered",
		},
	)

	if err != nil {
		t.Errorf("ToMessage should not return error: %v", err)
	}

	// Configured arguments should be present
	if message.Metadata["series"] != "Formula 1" {
		t.Errorf("Expected metadata series 'Formula 1', got '%s'", message.Metadata["series"])
	}

	// Configured options should be present
	if message.Metadata["verbose"] != "true" {
		t.Errorf("Expected metadata verbose 'true', got '%s'", message.Metadata["verbose"])
	}

	// Extra options should not be present
	if _, exists := message.Metadata["extra-opt"]; exists {
		t.Error("Extra option 'extra-opt' should not be present in metadata")
	}
	if _, exists := message.Metadata["another-opt"]; exists {
		t.Error("Extra option 'another-opt' should not be present in metadata")
	}

	// Should only have metadata for configured items
	if len(message.Metadata) != 2 { // Only series and verbose
		t.Errorf("Expected 2 metadata items, got %d", len(message.Metadata))
	}
}

func TestBaseIntent_ToMessage_Success_EmptyConfig(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A test intent",
			Arguments:   []Argument{},
			Options:     []Option{},
		},
	}

	message, err := intent.ToMessage([]string{}, map[string]string{})

	if err != nil {
		t.Errorf("ToMessage should not return error for empty config: %v", err)
	}
	if message.Type != "test-intent" {
		t.Errorf("Expected message type 'test-intent', got '%s'", message.Type)
	}
	if len(message.Metadata) != 0 {
		t.Errorf("Expected empty metadata, got %d items", len(message.Metadata))
	}
}

func TestBaseIntent_ToMessage_ValidationError_MissingArguments(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A test intent",
			Arguments: []Argument{
				{Name: "series", Description: "Series name"},
				{Name: "season", Description: "Season year"},
			},
			Options: []Option{},
		},
	}

	_, err := intent.ToMessage([]string{"Formula 1"}, map[string]string{}) // Missing second argument

	if err == nil {
		t.Error("ToMessage should return error for missing required arguments")
	}
}

func TestBaseIntent_ToMessage_ValidationError_OptionRequiresValue(t *testing.T) {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A test intent",
			Arguments:   []Argument{},
			Options: []Option{
				{Name: "output", Description: "Output file", RequiresValue: true},
				{Name: "verbose", Description: "Verbose mode", RequiresValue: false},
			},
		},
	}

	// Test case 1: RequiresValue option provided as flag (should fail)
	_, err := intent.ToMessage([]string{}, map[string]string{"output": "true"})
	if err == nil {
		t.Error("ToMessage should return error when RequiresValue option is provided as flag")
	}

	// Test case 2: RequiresValue option with valid value (should pass)
	message, err := intent.ToMessage([]string{}, map[string]string{"output": "file.json"})
	if err != nil {
		t.Errorf("ToMessage should not return error for RequiresValue option with valid value: %v", err)
	}
	if message.Metadata["output"] != "file.json" {
		t.Errorf("Expected output 'file.json', got '%s'", message.Metadata["output"])
	}

	// Test case 3: Boolean flag without value (should pass, defaults to "true")
	message, err = intent.ToMessage([]string{}, map[string]string{"verbose": "true"})
	if err != nil {
		t.Errorf("ToMessage should not return error for boolean flag: %v", err)
	}
	if message.Metadata["verbose"] != "true" {
		t.Errorf("Expected verbose 'true', got '%s'", message.Metadata["verbose"])
	}

	// Test case 4: Boolean flag with explicit false (should pass)
	message, err = intent.ToMessage([]string{}, map[string]string{"verbose": "false"})
	if err != nil {
		t.Errorf("ToMessage should not return error for boolean flag with false: %v", err)
	}
	if message.Metadata["verbose"] != "false" {
		t.Errorf("Expected verbose 'false', got '%s'", message.Metadata["verbose"])
	}

	// Test case 5: RequiresValue option with empty string (should this fail?)
	message, err = intent.ToMessage([]string{}, map[string]string{"output": ""})
	if err != nil {
		t.Errorf("ToMessage returned error for empty string value: %v", err)
	}
	// Note: This currently passes, but should we allow empty strings for RequiresValue options?
}
