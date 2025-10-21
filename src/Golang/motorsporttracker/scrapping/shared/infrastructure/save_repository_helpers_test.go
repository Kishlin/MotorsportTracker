package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/suite"
)

type SaveRepositoryHelpersUnitTestSuite struct {
	suite.Suite
}

func (suite *SaveRepositoryHelpersUnitTestSuite) TestSplitRowsIntoBatches() {
	suite.T().Run("one batch when under limit", func(t *testing.T) {
		rows := make([][]interface{}, 10) // 10 rows
		for i := range rows {
			rows[i] = make([]interface{}, 5) // 5 columns
		}
		batches, err := splitRowsIntoBatches(rows)
		suite.NoError(err)
		suite.Len(batches, 1)
		suite.Len(batches[0], 10)
	})

	suite.T().Run("multiple batches when over limit", func(t *testing.T) {
		const colsPerRow = 5
		const expectedBatches = 7
		const rowsCount = (6.5 * maxParamsPerQuery) / colsPerRow

		rows := make([][]interface{}, rowsCount)
		for i := range rows {
			rows[i] = make([]interface{}, colsPerRow)
		}
		batches, err := splitRowsIntoBatches(rows)
		suite.NoError(err)
		suite.Len(batches, expectedBatches)
		for i := 0; i < expectedBatches-1; i++ {
			suite.Len(batches[i], maxParamsPerQuery/colsPerRow)
		}
	})
}

func (suite *SaveRepositoryHelpersUnitTestSuite) TestBuildValuesPlaceholders() {
	for name, tc := range map[string]struct {
		numRows             int
		numColumns          int
		expectedPlaceholder string
	}{
		"works with one row and one column": {
			numRows:             1,
			numColumns:          1,
			expectedPlaceholder: "($1)",
		},
		"works with one row and multiple columns": {
			numRows:             1,
			numColumns:          3,
			expectedPlaceholder: "($1,$2,$3)",
		},
		"works with multiple rows and one column": {
			numRows:             3,
			numColumns:          1,
			expectedPlaceholder: "($1),($2),($3)",
		},
		"works with multiple rows and multiple columns": {
			numRows:             2,
			numColumns:          3,
			expectedPlaceholder: "($1,$2,$3),($4,$5,$6)",
		},
	} {
		suite.T().Run(name, func(t *testing.T) {
			actual := buildValuesPlaceholders(tc.numRows, tc.numColumns)
			suite.Equal(tc.expectedPlaceholder, actual)
		})
	}
}

func (suite *SaveRepositoryHelpersUnitTestSuite) TestBuildOnConflictUpdates() {
	for name, tc := range map[string]struct {
		columns  []string
		expected string
	}{
		"works with one column": {
			columns:  []string{"column1"},
			expected: "column1 = EXCLUDED.column1",
		},
		"works with multiple columns": {
			columns:  []string{"col1", "col2", "col3"},
			expected: "col1 = EXCLUDED.col1, col2 = EXCLUDED.col2, col3 = EXCLUDED.col3",
		},
		"ignores uuid column": {
			columns:  []string{"uuid", "col1", "col2"},
			expected: "col1 = EXCLUDED.col1, col2 = EXCLUDED.col2",
		},
	} {
		suite.T().Run(name, func(t *testing.T) {
			actual := buildOnConflictUpdates(tc.columns)
			suite.Equal(tc.expected, actual)
		})
	}
}

func TestUnit_SaveRepositoryHelpers(t *testing.T) {
	suite.Run(t, new(SaveRepositoryHelpersUnitTestSuite))
}
