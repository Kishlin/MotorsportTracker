package domain

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type IntentUnitTestSuite struct {
	suite.Suite
}

func (suite *IntentUnitTestSuite) TestToMessage() {
	intent := &BaseIntent{
		Config: IntentConfig{
			Name:        "test-intent",
			Description: "A comprehensive test intent",
			Arguments: []Argument{
				{Name: "series", Description: "Series name"},
				{Name: "season", Description: "Season year"},
			},
			Options: []Option{
				{Name: "verbose", ShortName: "v", Description: "Verbose output", RequiresValue: false},
				{Name: "output", ShortName: "o", Description: "Output file", RequiresValue: true},
				{Name: "quiet", ShortName: "q", Description: "Quiet mode", RequiresValue: false},
			},
		},
	}

	suite.T().Run("All arguments and options", func(t *testing.T) {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"o": "results.json",
				"q": "false",
				"v": "true",
			},
		)
		require.NoError(t, err)

		require.Equal(t, "test-intent", message.Type)

		require.Equal(t, "Formula 1", message.Metadata["series"])
		require.Equal(t, "2023", message.Metadata["season"])
		require.Equal(t, "results.json", message.Metadata["output"])
		require.Equal(t, "true", message.Metadata["verbose"])
		require.Equal(t, "false", message.Metadata["quiet"])
	})

	suite.T().Run("Missing options are not set", func(t *testing.T) {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "true",
			},
		)
		require.NoError(t, err)

		// Options not provided should not be in metadata
		_, outputExists := message.Metadata["output"]
		require.False(t, outputExists, "Output option should not be present")

		_, quietExists := message.Metadata["quiet"]
		require.False(t, quietExists, "Quiet option should not be present")
	})

	suite.T().Run("Extra arguments and options are ignored", func(t *testing.T) {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023", "extra-arg", "another-extra"},
			map[string]string{
				"extra-opt": "should-be-ignored",
				"verbose":   "true",
			},
		)
		require.NoError(t, err)

		require.Len(t, message.Metadata, 3) // 2 arguments + 1 option

		// Extra argument should be ignored
		require.NotContains(t, message.Metadata, "extra-arg")
		require.NotContains(t, message.Metadata, "another-extra")

		// Extra option should be ignored
		require.NotContains(t, message.Metadata, "extra-opt")
	})

	suite.T().Run("Empty config", func(t *testing.T) {
		emptyIntent := &BaseIntent{
			Config: IntentConfig{
				Name:        "empty-intent",
				Description: "An intent with no args or options",
				Arguments:   []Argument{},
				Options:     []Option{},
			},
		}

		message, err := emptyIntent.ToMessage([]string{}, map[string]string{})
		require.NoError(t, err)
		require.Equal(t, "empty-intent", message.Type)
		require.Empty(t, message.Metadata)
	})

	suite.T().Run("Fails when arguments are missing", func(t *testing.T) {
		_, err := intent.ToMessage([]string{"OnlyOneArg"}, map[string]string{})
		require.Error(t, err)
	})

	suite.T().Run("Fails when options that require a value are not given one", func(t *testing.T) {
		_, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"output": "", // Empty value
			},
		)
		require.Error(t, err)
	})

	suite.T().Run("Boolean flags default to true", func(t *testing.T) {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "", // No value provided, should default to "true"
			},
		)
		require.NoError(t, err)
		require.Equal(t, "true", message.Metadata["verbose"])

		message, err = intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "some-value", // Should still default to "true"
			},
		)
		require.NoError(t, err)
		require.Equal(t, "true", message.Metadata["verbose"])

		message, err = intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "false", // Should stay "false"
			},
		)
		require.NoError(t, err)
		require.Equal(t, "false", message.Metadata["verbose"])
	})
}

func TestUnit_Intent(t *testing.T) {
	suite.Run(t, new(IntentUnitTestSuite))
}
