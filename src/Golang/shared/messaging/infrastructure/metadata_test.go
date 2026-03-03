package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type MetadataUnitTestSuite struct {
	suite.Suite
}

func (s *MetadataUnitTestSuite) TestRequireString_Success() {
	msg := Message{Metadata: map[string]string{"series": "Formula 1"}}

	value, err := RequireString(msg, "series")

	require.NoError(s.T(), err)
	require.Equal(s.T(), "Formula 1", value)
}

func (s *MetadataUnitTestSuite) TestRequireString_MissingKey() {
	msg := Message{Metadata: map[string]string{"other": "value"}}

	_, err := RequireString(msg, "series")

	require.EqualError(s.T(), err, `metadata key "series" is required`)
}

func (s *MetadataUnitTestSuite) TestRequireString_EmptyValue() {
	msg := Message{Metadata: map[string]string{"series": ""}}

	_, err := RequireString(msg, "series")

	require.EqualError(s.T(), err, `metadata key "series" is required`)
}

func (s *MetadataUnitTestSuite) TestRequireString_NilMetadata() {
	msg := Message{Metadata: nil}

	_, err := RequireString(msg, "series")

	require.EqualError(s.T(), err, `metadata key "series" is required`)
}

func (s *MetadataUnitTestSuite) TestRequireInt_Success() {
	msg := Message{Metadata: map[string]string{"year": "2024"}}

	value, err := RequireInt(msg, "year")

	require.NoError(s.T(), err)
	require.Equal(s.T(), 2024, value)
}

func (s *MetadataUnitTestSuite) TestRequireInt_MissingKey() {
	msg := Message{Metadata: map[string]string{}}

	_, err := RequireInt(msg, "year")

	require.EqualError(s.T(), err, `metadata key "year" is required`)
}

func (s *MetadataUnitTestSuite) TestRequireInt_InvalidFormat() {
	msg := Message{Metadata: map[string]string{"year": "not-a-number"}}

	_, err := RequireInt(msg, "year")

	require.EqualError(s.T(), err, `metadata key "year": invalid integer format`)
}

func TestUnit_Metadata(t *testing.T) {
	suite.Run(t, new(MetadataUnitTestSuite))
}
