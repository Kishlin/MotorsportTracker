package main

import (
	"reflect"
	"testing"
)

func TestCommandsPublisher_ParseArgs(t *testing.T) {
	tests := []struct {
		name              string
		args              []string
		expectedArguments []string
		expectedOptions   map[string]string
	}{
		{
			name:              "empty args",
			args:              []string{},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{},
		},
		{
			name:              "only positional arguments",
			args:              []string{"command", "subcommand", "file.txt"},
			expectedArguments: []string{"command", "subcommand", "file.txt"},
			expectedOptions:   map[string]string{},
		},
		{
			name:              "only options - long form with values",
			args:              []string{"--series=f1", "--year=2023"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"series": "f1", "year": "2023"},
		},
		{
			name:              "only options - long form boolean flags",
			args:              []string{"--verbose", "--debug"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"verbose": "true", "debug": "true"},
		},
		{
			name:              "only options - short form with values",
			args:              []string{"-s=f1", "-y=2023"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"s": "f1", "y": "2023"},
		},
		{
			name:              "only options - short form boolean flags",
			args:              []string{"-v", "-d"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"v": "true", "d": "true"},
		},
		{
			name:              "mixed arguments and options",
			args:              []string{"scrap", "series", "--series=motogp", "-v", "2024"},
			expectedArguments: []string{"scrap", "series", "2024"},
			expectedOptions:   map[string]string{"series": "motogp", "v": "true"},
		},
		{
			name:              "options with special characters",
			args:              []string{"--url=https://example.com?param=value", "--path=/home/user/file.txt"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"url": "https://example.com?param=value", "path": "/home/user/file.txt"},
		},
		{
			name:              "empty option values",
			args:              []string{"--empty=", "-e="},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{"empty": "", "e": ""},
		},
		{
			name:              "double dash only",
			args:              []string{"--"},
			expectedArguments: []string{},
			expectedOptions:   map[string]string{},
		},
		{
			name:              "double dash stops option processing",
			args:              []string{"command", "--verbose", "--", "--not-an-option", "-x", "file.txt"},
			expectedArguments: []string{"command", "--not-an-option", "-x", "file.txt"},
			expectedOptions:   map[string]string{"verbose": "true"},
		},
	}

	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			args, options := parseArgs(tt.args)

			if !reflect.DeepEqual(args, tt.expectedArguments) {
				t.Errorf("parseArgs(%v) arguments = %v, want %v", tt.args, args, tt.expectedArguments)
			}

			if !reflect.DeepEqual(options, tt.expectedOptions) {
				t.Errorf("parseArgs(%v) options = %v, want %v", tt.args, options, tt.expectedOptions)
			}
		})
	}
}

// Benchmark to ensure parseArgs is performant with large argument lists
func BenchmarkCommandsPublisher_ParseArgs(b *testing.B) {
	args := []string{
		"--dry-run", "--format=json", "--timeout=30", "-q", "--verbose", "--", "-bad-file-name.txt",
		"scrap", "series", "--series=f1", "--year=2023", "-v", "--output=/tmp/results.json",
		"--config=/path/to/config.yaml", "--workers=4", "--batch-size=100", "final-arg",
	}

	b.ResetTimer()
	for i := 0; i < b.N; i++ {
		arguments, options := parseArgs(args)

		// Prevent compiler optimization by using the results
		_ = len(arguments) + len(options)
	}
}
