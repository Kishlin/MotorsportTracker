package infrastructure

import (
	"testing"

	"github.com/stretchr/testify/suite"
)

type SaveRepositoryHelpersUnitTestSuite struct {
	suite.Suite
}

func (suite *SaveRepositoryHelpersUnitTestSuite) TestSplitRowsIntoBatches() {
	suite.Run("one batch when under limit", func() {
		rows := make([][]interface{}, 10) // 10 rows
		for i := range rows {
			rows[i] = make([]interface{}, 5) // 5 columns
		}
		batches, err := splitRowsIntoBatches(rows)
		suite.NoError(err)
		suite.Len(batches, 1)
		suite.Len(batches[0], 10)
	})

	suite.Run("multiple batches when over limit", func() {
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
		suite.Run(name, func() {
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
		suite.Run(name, func() {
			actual := buildOnConflictUpdates(tc.columns)
			suite.Equal(tc.expected, actual)
		})
	}
}

func (suite *SaveRepositoryHelpersUnitTestSuite) TestSortByConflictKey() {
	columns := []string{"session", "car_number", "label", "hash"}

	suite.Run("orders rows the same whatever their input order", func() {
		a := []interface{}{2, "7", "a", "hash-a"}
		b := []interface{}{1, "33", "b", "hash-b"}
		c := []interface{}{1, "4", "c", "hash-c"}
		d := []interface{}{nil, "1", "d", "hash-d"}

		first, err := sortByConflictKey("session, car_number", columns, [][]interface{}{a, b, c, d})
		suite.NoError(err)
		second, err := sortByConflictKey("session, car_number", columns, [][]interface{}{d, c, b, a})
		suite.NoError(err)

		suite.Equal(first, second)
		suite.Len(first, 4)
	})

	suite.Run("sorts on the conflict columns only", func() {
		rows := [][]interface{}{
			{1, "2", "z", "hash-1"},
			{1, "1", "y", "hash-2"},
		}

		sorted, err := sortByConflictKey("car_number", columns, rows)
		suite.NoError(err)
		suite.Equal("y", sorted[0][2])
		suite.Equal("z", sorted[1][2])
	})

	suite.Run("leaves the caller's rows in their order", func() {
		rows := [][]interface{}{
			{2, "1", "first", "hash-1"},
			{1, "1", "second", "hash-2"},
		}

		_, err := sortByConflictKey("session", columns, rows)
		suite.NoError(err)
		suite.Equal("first", rows[0][2])
		suite.Equal("second", rows[1][2])
	})

	suite.Run("fails on a conflict column missing from the columns", func() {
		_, err := sortByConflictKey("session, occurrence", columns, [][]interface{}{{1, "1", "a", "hash"}})
		suite.ErrorContains(err, `conflict column "occurrence" is not among the columns`)
	})

	suite.Run("fails on a row narrower than the columns", func() {
		_, err := sortByConflictKey("session", columns, [][]interface{}{{1, "1"}})
		suite.ErrorContains(err, "row 0 has 2 values for 4 columns")
	})
}

func TestUnit_SaveRepositoryHelpers(t *testing.T) {
	suite.Run(t, new(SaveRepositoryHelpersUnitTestSuite))
}
