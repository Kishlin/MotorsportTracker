package infrastructure

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

	suite.Run("All arguments and options", func() {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"o": "results.json",
				"q": "false",
				"v": "true",
			},
		)
		require.NoError(suite.T(), err)

		require.Equal(suite.T(), "test-intent", message.Type)

		require.Equal(suite.T(), "Formula 1", message.Metadata["series"])
		require.Equal(suite.T(), "2023", message.Metadata["season"])
		require.Equal(suite.T(), "results.json", message.Metadata["output"])
		require.Equal(suite.T(), "true", message.Metadata["verbose"])
		require.Equal(suite.T(), "false", message.Metadata["quiet"])
	})

	suite.Run("Missing options are not set", func() {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "true",
			},
		)
		require.NoError(suite.T(), err)

		// Options not provided should not be in metadata
		_, outputExists := message.Metadata["output"]
		require.False(suite.T(), outputExists, "Output option should not be present")

		_, quietExists := message.Metadata["quiet"]
		require.False(suite.T(), quietExists, "Quiet option should not be present")
	})

	suite.Run("Extra arguments and options are ignored", func() {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023", "extra-arg", "another-extra"},
			map[string]string{
				"extra-opt": "should-be-ignored",
				"verbose":   "true",
			},
		)
		require.NoError(suite.T(), err)

		require.Len(suite.T(), message.Metadata, 3) // 2 arguments + 1 option

		// Extra argument should be ignored
		require.NotContains(suite.T(), message.Metadata, "extra-arg")
		require.NotContains(suite.T(), message.Metadata, "another-extra")

		// Extra option should be ignored
		require.NotContains(suite.T(), message.Metadata, "extra-opt")
	})

	suite.Run("Empty config", func() {
		emptyIntent := &BaseIntent{
			Config: IntentConfig{
				Name:        "empty-intent",
				Description: "An intent with no args or options",
				Arguments:   []Argument{},
				Options:     []Option{},
			},
		}

		message, err := emptyIntent.ToMessage([]string{}, map[string]string{})
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), "empty-intent", message.Type)
		require.Empty(suite.T(), message.Metadata)
	})

	suite.Run("Fails when arguments are missing", func() {
		_, err := intent.ToMessage([]string{"OnlyOneArg"}, map[string]string{})
		require.Error(suite.T(), err)
	})

	suite.Run("Fails when options that require a value are not given one", func() {
		_, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"output": "", // Empty value
			},
		)
		require.Error(suite.T(), err)
	})

	suite.Run("Boolean flags default to true", func() {
		message, err := intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "", // No value provided, should default to "true"
			},
		)
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), "true", message.Metadata["verbose"])

		message, err = intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "some-value", // Should still default to "true"
			},
		)
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), "true", message.Metadata["verbose"])

		message, err = intent.ToMessage(
			[]string{"Formula 1", "2023"},
			map[string]string{
				"verbose": "false", // Should stay "false"
			},
		)
		require.NoError(suite.T(), err)
		require.Equal(suite.T(), "false", message.Metadata["verbose"])
	})
}

func TestUnit_Intent(t *testing.T) {
	suite.Run(t, new(IntentUnitTestSuite))
}
