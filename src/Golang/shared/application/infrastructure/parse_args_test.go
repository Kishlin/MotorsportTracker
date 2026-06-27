package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type ParseArgsUnitTestSuite struct {
	suite.Suite
}

func (s *ParseArgsUnitTestSuite) TestEmptyArgs() {
	args, options := ParseArgs([]string{})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{}, options)
}

func (s *ParseArgsUnitTestSuite) TestOnlyPositionalArguments() {
	args, options := ParseArgs([]string{"command", "subcommand", "file.txt"})
	require.Equal(s.T(), []string{"command", "subcommand", "file.txt"}, args)
	require.Equal(s.T(), map[string]string{}, options)
}

func (s *ParseArgsUnitTestSuite) TestLongFormOptionsWithValues() {
	args, options := ParseArgs([]string{"--series=f1", "--year=2023"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"series": "f1", "year": "2023"}, options)
}

func (s *ParseArgsUnitTestSuite) TestLongFormBooleanFlags() {
	args, options := ParseArgs([]string{"--verbose", "--debug"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"verbose": "true", "debug": "true"}, options)
}

func (s *ParseArgsUnitTestSuite) TestShortFormOptionsWithValues() {
	args, options := ParseArgs([]string{"-s=f1", "-y=2023"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"s": "f1", "y": "2023"}, options)
}

func (s *ParseArgsUnitTestSuite) TestShortFormBooleanFlags() {
	args, options := ParseArgs([]string{"-v", "-d"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"v": "true", "d": "true"}, options)
}

func (s *ParseArgsUnitTestSuite) TestMixedArgumentsAndOptions() {
	args, options := ParseArgs([]string{"scrap", "series", "--series=motogp", "-v", "2024"})
	require.Equal(s.T(), []string{"scrap", "series", "2024"}, args)
	require.Equal(s.T(), map[string]string{"series": "motogp", "v": "true"}, options)
}

func (s *ParseArgsUnitTestSuite) TestOptionsWithSpecialCharacters() {
	args, options := ParseArgs([]string{"--url=https://example.com?param=value", "--path=/home/user/file.txt"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"url": "https://example.com?param=value", "path": "/home/user/file.txt"}, options)
}

func (s *ParseArgsUnitTestSuite) TestEmptyOptionValues() {
	args, options := ParseArgs([]string{"--empty=", "-e="})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{"empty": "", "e": ""}, options)
}

func (s *ParseArgsUnitTestSuite) TestDoubleDashOnly() {
	args, options := ParseArgs([]string{"--"})
	require.Equal(s.T(), []string{}, args)
	require.Equal(s.T(), map[string]string{}, options)
}

func (s *ParseArgsUnitTestSuite) TestDoubleDashStopsOptionProcessing() {
	args, options := ParseArgs([]string{"command", "--verbose", "--", "--not-an-option", "-x", "file.txt"})
	require.Equal(s.T(), []string{"command", "--not-an-option", "-x", "file.txt"}, args)
	require.Equal(s.T(), map[string]string{"verbose": "true"}, options)
}

func TestUnit_ParseArgs(t *testing.T) {
	suite.Run(t, new(ParseArgsUnitTestSuite))
}
