package domain

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SeriesUnitTestSuite struct {
	suite.Suite
}

func (suite *SeriesUnitTestSuite) TestsEqualTo() {
	reference := Series{
		Name:      "Formula 1",
		UUID:      "f1-1234",
		ShortName: fn.Ptr("F1"),
		ShortCode: "F1",
		Category:  "Open Wheel",
	}

	suite.T().Run("Equal Series", func(t *testing.T) {
		other := Series{
			Name:      "Formula 1",
			UUID:      "f1-1234",
			ShortName: fn.Ptr("F1"),
			ShortCode: "F1",
			Category:  "Open Wheel",
		}

		require.True(suite.T(), reference.IsEqualTo(&other))
	})

	for name, other := range map[string]Series{
		"Name differs": {
			Name:      "Formula 2",
			UUID:      "f1-1234",
			ShortName: fn.Ptr("F1"),
			ShortCode: "F1",
			Category:  "Open Wheel",
		},
		"UUID differs": {
			Name:      "Formula 1",
			UUID:      "f1-5678",
			ShortName: fn.Ptr("F1"),
			ShortCode: "F1",
			Category:  "Open Wheel",
		},
		"ShortName differs": {
			Name:      "Formula 1",
			UUID:      "f1-1234",
			ShortName: fn.Ptr("F2"),
			ShortCode: "F1",
			Category:  "Open Wheel",
		},
		"ShortName is nil": {
			Name:      "Formula 1",
			UUID:      "f1-1234",
			ShortName: nil,
			ShortCode: "F1",
			Category:  "Open Wheel",
		},
		"ShortCode differs": {
			Name:      "Formula 1",
			UUID:      "f1-1234",
			ShortName: fn.Ptr("F1"),
			ShortCode: "F2",
			Category:  "Open Wheel",
		},
		"Category differs": {
			Name:      "Formula 1",
			UUID:      "f1-1234",
			ShortName: fn.Ptr("F1"),
			ShortCode: "F1",
			Category:  "Closed Wheel",
		},
	} {
		suite.T().Run(name, func(t *testing.T) {
			require.False(suite.T(), reference.IsEqualTo(&other))
		})
	}
}

func TestUnit_Series(t *testing.T) {
	suite.Run(t, new(SeriesUnitTestSuite))
}
