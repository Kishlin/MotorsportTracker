package main

import (
	"fmt"
	"strings"
	"testing"

	"github.com/stretchr/testify/require"
	"github.com/stretchr/testify/suite"
)

type ValidationErrorsUnitTestSuite struct {
	suite.Suite
}

func TestUnit_ValidationErrors(t *testing.T) {
	suite.Run(t, new(ValidationErrorsUnitTestSuite))
}

func (suite *ValidationErrorsUnitTestSuite) TestCollapseValidationErrors() {
	suite.Run("A single error is left alone", func() {
		message := `/details/0: {"avgLapSpeed":184.0... "tyreCompound" value is required`

		collapsed := collapseValidationErrors(message)

		require.Equal(suite.T(), []string{message}, collapsed)
	})

	suite.Run("The same problem on every row folds into one line with a count", func() {
		message := strings.Join([]string{
			`/details/0: {"avgLapSpeed":184.0... "tyreCompound" value is required`,
			`/details/1: {"avgLapSpeed":183.6... "tyreCompound" value is required`,
			`/details/2: {"avgLapSpeed":183.5... "tyreCompound" value is required`,
		}, "\n")

		collapsed := collapseValidationErrors(message)

		require.Equal(
			suite.T(),
			[]string{`/details/0: {"avgLapSpeed":184.0... "tyreCompound" value is required  (3 occurrences)`},
			collapsed,
		)
	})

	suite.Run("Distinct problems on the same path stay separate", func() {
		message := strings.Join([]string{
			`/details/0: {"carNumber":"1"... "tyreCompound" value is required`,
			`/details/1: {"carNumber":"2"... "pitStops" value is required`,
		}, "\n")

		collapsed := collapseValidationErrors(message)

		require.Len(suite.T(), collapsed, 2)
		require.Contains(suite.T(), collapsed[0], "tyreCompound")
		require.Contains(suite.T(), collapsed[1], "pitStops")
	})

	suite.Run("Different paths stay separate", func() {
		message := strings.Join([]string{
			`/details/0: {"carNumber":"1"... "team" value is required`,
			`/retirements/0: {"carNumber":"4"... "team" value is required`,
		}, "\n")

		collapsed := collapseValidationErrors(message)

		require.Len(suite.T(), collapsed, 2)
	})

	suite.Run("Untruncated values still fold", func() {
		message := strings.Join([]string{
			`/details/0: {"a":1} "team" value is required`,
			`/details/1: {"a":1} "team" value is required`,
		}, "\n")

		collapsed := collapseValidationErrors(message)

		require.Equal(
			suite.T(),
			[]string{`/details/0: {"a":1} "team" value is required  (2 occurrences)`},
			collapsed,
		)
	})

	suite.Run("Distinct errors beyond the cap are counted, not printed", func() {
		lines := make([]string, 0, maxReportedErrors+3)
		for i := 0; i < maxReportedErrors+3; i++ {
			lines = append(lines, fmt.Sprintf(`/details/0: {"a":1... "field%d" value is required`, i))
		}

		collapsed := collapseValidationErrors(strings.Join(lines, "\n"))

		require.Len(suite.T(), collapsed, maxReportedErrors+1)
		require.Equal(suite.T(), "... and 3 further distinct errors", collapsed[maxReportedErrors])
	})

	suite.Run("A message with no path is passed through", func() {
		message := "getting /widgets/1.0.0/series: fetching data: 503 Service Unavailable"

		collapsed := collapseValidationErrors(message)

		require.Equal(suite.T(), []string{message}, collapsed)
	})

	suite.Run("Blank lines are dropped", func() {
		collapsed := collapseValidationErrors("first error\n\n\nsecond error\n")

		require.Equal(suite.T(), []string{"first error", "second error"}, collapsed)
	})
}
