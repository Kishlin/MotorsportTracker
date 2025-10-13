package domain

import (
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type HandlerUnitTestSuite struct {
	suite.Suite
}

func (suite *HandlerUnitTestSuite) TestCompare() {
	fetchedSeres := []*motorsportstats.Series{
		{UUID: "exists-0", Name: "Series Exists", ShortName: fn.Ptr("Ex 1"), ShortCode: "S1", Category: "Cat 1"},
		{UUID: "name-differs", Name: "Series 2", ShortName: fn.Ptr("Ser 2"), ShortCode: "S2", Category: "Cat 2"},
		{UUID: "shortname-differs", Name: "Series 3", ShortName: fn.Ptr("Ser 3 New"), ShortCode: "S3", Category: "Cat 3"},
		{UUID: "shortname-differs-nil", Name: "Series 3b", ShortName: nil, ShortCode: "S3b", Category: "Cat 3b"},
		{UUID: "shortcode-differs", Name: "Series 4", ShortName: fn.Ptr("Ser 4"), ShortCode: "S4 New", Category: "Cat 4"},
		{UUID: "category-differs", Name: "Series 5", ShortName: fn.Ptr("Ser 5"), ShortCode: "S5", Category: "Cat 5 New"},
		{UUID: "new-0", Name: "Series New", ShortName: fn.Ptr("New 1"), ShortCode: "N1", Category: "Cat New"},
		{UUID: "new-1", Name: "Series New 2", ShortName: nil, ShortCode: "N2", Category: "Cat New"},
	}

	existingSeries := map[string]*Series{
		"exists-0":              {UUID: "exists-0", Name: "Series Exists", ShortName: fn.Ptr("Ex 1"), ShortCode: "S1", Category: "Cat 1"},
		"name-differs":          {UUID: "name-differs", Name: "Series 2 Old", ShortName: fn.Ptr("Ser 2"), ShortCode: "S2", Category: "Cat 2"},
		"shortname-differs":     {UUID: "shortname-differs", Name: "Series 3", ShortName: fn.Ptr("Ser 3 Old"), ShortCode: "S3", Category: "Cat 3"},
		"shortname-differs-nil": {UUID: "shortname-differs-nil", Name: "Series 3b", ShortName: fn.Ptr("Ser 3b Old"), ShortCode: "S3b", Category: "Cat 3b"},
		"shortcode-differs":     {UUID: "shortcode-differs", Name: "Series 4", ShortName: fn.Ptr("Ser 4"), ShortCode: "S4 Old", Category: "Cat 4"},
		"category-differs":      {UUID: "category-differs", Name: "Series 5", ShortName: fn.Ptr("Ser 5"), ShortCode: "S5", Category: "Cat 5 Old"},
	}

	toInsert, actualStats := (&ScrapSeriesHandler{}).compare(fetchedSeres, existingSeries)

	require.Len(suite.T(), toInsert, 2)
	require.Equal(suite.T(), toInsert[0].UUID, fetchedSeres[6].UUID)
	require.Equal(suite.T(), toInsert[1].UUID, fetchedSeres[7].UUID)

	expected := seriesComparisonStats{
		existingSeriesCount:  6,
		differingSeriesCount: 5,
		newSeriesCount:       2,
	}

	require.Equal(suite.T(), expected, actualStats)
}

func TestUnit_Handler(t *testing.T) {
	suite.Run(t, new(HandlerUnitTestSuite))
}
