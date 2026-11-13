package infrastructure

import (
	"context"
	"encoding/json"
	"fmt"
	"slices"
	"strconv"
	"strings"

	crypto "github.com/kishlin/MotorsportTracker/src/Golang/shared/crypto/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

const maxParamsPerQuery = 1000

const hashColumn = "hash"

const upsertQueryTemplate = `
INSERT INTO %s (%s) VALUES %s
ON CONFLICT (%s) DO UPDATE SET %s
WHERE %s.hash IS DISTINCT FROM EXCLUDED.hash
RETURNING id, xmax <> 0 AS updated;
`

// UpsertStats reports counts of inserted/updated rows from upsert calls.
type UpsertStats struct {
	Inserted int
	Updated  int
}

// UpsertResult mirrors the RETURNING row (id, updated flag).
type UpsertResult struct {
	ID      int
	Updated bool
}

// Save appends a hash of every value to each row, sorts the rows by their conflict key, then chooses
// single vs batched execution based on total param count. The hash column is Save's own: callers never
// pass one. Two saves touching the same keys in a different order can deadlock; sorting gives every
// save the same lock order.
func Save(ctx context.Context, db *database.PGXPoolAdapter, table string, conflictColumns string, columns []string, rows [][]interface{}) (UpsertStats, error) {
	if len(rows) == 0 {
		return UpsertStats{}, nil
	}

	columns, rows, err := withHashes(columns, rows)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("hashing rows of %s: %w", table, err)
	}

	rows, err = sortByConflictKey(conflictColumns, columns, rows)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("sorting rows of %s by conflict key: %w", table, err)
	}

	valuesPerRow := len(rows[0])
	if valuesPerRow <= 0 {
		return UpsertStats{}, fmt.Errorf("invalid valuesPerRow: %d", valuesPerRow)
	}

	totalParams := len(rows) * valuesPerRow
	if totalParams <= maxParamsPerQuery {
		return UpsertRows(ctx, db, table, conflictColumns, columns, rows)
	}
	return UpsertInBatches(ctx, db, table, conflictColumns, columns, rows)
}

// UpsertInBatches splits rows into batches and executes them sequentially.
func UpsertInBatches(ctx context.Context, db *database.PGXPoolAdapter, table string, conflictColumns string, columns []string, rows [][]interface{}) (UpsertStats, error) {
	if len(rows) == 0 {
		return UpsertStats{}, nil
	}

	batches, err := splitRowsIntoBatches(rows)
	if err != nil {
		return UpsertStats{}, err
	}

	aggregate := UpsertStats{}
	for _, batch := range batches {
		stats, err := UpsertRows(ctx, db, table, conflictColumns, columns, batch)
		if err != nil {
			return aggregate, err
		}

		aggregate.Inserted += stats.Inserted
		aggregate.Updated += stats.Updated
	}

	return aggregate, nil
}

// UpsertRows executes a single upsert for the provided rows (no internal batching).
func UpsertRows(ctx context.Context, db *database.PGXPoolAdapter, table string, conflictColumns string, columns []string, rows [][]interface{}) (UpsertStats, error) {
	query, flatArgs, err := prepareBatch(table, conflictColumns, columns, rows)
	if err != nil {
		return UpsertStats{}, fmt.Errorf("preparing single batch: %w", err)
	}
	return upsertData(ctx, db, query, flatArgs)
}

// withHashes returns copies of columns and rows with a hash column appended. A row's hash is the SHA-256
// of the JSON array of its values, in column order: JSON tells null from "" and from 0, and keeps every
// digit of a float, so no change between two values can go unseen.
func withHashes(columns []string, rows [][]interface{}) ([]string, [][]interface{}, error) {
	if slices.Contains(columns, hashColumn) {
		return nil, nil, fmt.Errorf("columns %v include %q, which Save computes itself", columns, hashColumn)
	}

	hashed := make([][]interface{}, len(rows))
	for i, row := range rows {
		if len(row) != len(columns) {
			return nil, nil, fmt.Errorf("row %d has %d values for %d columns", i, len(row), len(columns))
		}

		encoded, err := json.Marshal(row)
		if err != nil {
			return nil, nil, fmt.Errorf("encoding row %d: %w", i, err)
		}

		hashed[i] = append(slices.Clone(row), crypto.Hash(string(encoded)))
	}

	return append(slices.Clone(columns), hashColumn), hashed, nil
}

// sortByConflictKey returns a copy of rows ordered by the JSON encoding of their conflict columns' values.
// Any total order works, as long as every save uses the same one.
func sortByConflictKey(conflictColumns string, columns []string, rows [][]interface{}) ([][]interface{}, error) {
	var keyIndexes []int
	for _, name := range strings.Split(conflictColumns, ",") {
		name = strings.TrimSpace(name)

		index := slices.Index(columns, name)
		if index == -1 {
			return nil, fmt.Errorf("conflict column %q is not among the columns %v", name, columns)
		}

		keyIndexes = append(keyIndexes, index)
	}

	type keyedRow struct {
		key string
		row []interface{}
	}

	keyed := make([]keyedRow, len(rows))
	for i, row := range rows {
		if len(row) != len(columns) {
			return nil, fmt.Errorf("row %d has %d values for %d columns", i, len(row), len(columns))
		}

		keyValues := make([]interface{}, len(keyIndexes))
		for j, index := range keyIndexes {
			keyValues[j] = row[index]
		}

		key, err := json.Marshal(keyValues)
		if err != nil {
			return nil, fmt.Errorf("encoding the conflict key of row %d: %w", i, err)
		}

		keyed[i] = keyedRow{key: string(key), row: row}
	}

	slices.SortStableFunc(keyed, func(a, b keyedRow) int {
		return strings.Compare(a.key, b.key)
	})

	sorted := make([][]interface{}, len(keyed))
	for i, k := range keyed {
		sorted[i] = k.row
	}

	return sorted, nil
}

// splitRowsIntoBatches splits rows into batches that won't exceed maxParamsPerQuery when flattened.
func splitRowsIntoBatches(rows [][]interface{}) ([][][]interface{}, error) {
	if len(rows) == 0 {
		return nil, nil
	}

	valuesPerRow := len(rows[0])
	if valuesPerRow <= 0 {
		return nil, fmt.Errorf("invalid valuesPerRow: %d", valuesPerRow)
	}

	for _, row := range rows {
		if len(row) != valuesPerRow {
			return nil, fmt.Errorf("inconsistent row width: expected %d, got %d", valuesPerRow, len(row))
		}
	}

	maxRows := maxParamsPerQuery / valuesPerRow
	if maxRows < 1 {
		maxRows = 1
	}

	var batches [][][]interface{}
	for offset := 0; offset < len(rows); offset += maxRows {
		end := offset + maxRows
		if end > len(rows) {
			end = len(rows)
		}
		batches = append(batches, rows[offset:end])
	}

	return batches, nil
}

// prepareBatch builds VALUES placeholders and flattens batch rows into args.
func prepareBatch(table string, conflictColumns string, columns []string, batchRows [][]interface{}) (string, []interface{}, error) {
	if len(batchRows) == 0 {
		return "", nil, fmt.Errorf("empty batch")
	}

	valuesPerRow := len(batchRows[0])
	if valuesPerRow != len(columns) {
		return "", nil, fmt.Errorf("row width does not match columns count: expected %d, got %d", len(columns), valuesPerRow)
	}
	for _, row := range batchRows {
		if len(row) != valuesPerRow {
			return "", nil, fmt.Errorf("inconsistent row width in batch: expected %d, got %d", valuesPerRow, len(row))
		}
	}

	query := buildQuery(table, conflictColumns, columns, len(batchRows))

	flatArgs := make([]interface{}, 0, len(batchRows)*valuesPerRow)
	for _, row := range batchRows {
		flatArgs = append(flatArgs, row...)
	}

	return query, flatArgs, nil
}

func buildQuery(table string, conflictColumns string, columns []string, rowsCount int) string {
	columnsList := strings.Join(columns, ", ")
	placeholders := buildValuesPlaceholders(rowsCount, len(columns))
	onConflictUpdates := buildOnConflictUpdates(columns)

	return fmt.Sprintf(
		upsertQueryTemplate,
		table,
		columnsList,
		placeholders,
		conflictColumns,
		onConflictUpdates,
		table,
	)
}

// buildValuesPlaceholders returns a string like "($1,$2,$3),($4,$5,$6)" for the given batch.
func buildValuesPlaceholders(rows int, valuesPerRow int) string {
	estimated := rows * (valuesPerRow*3 + 2)
	var b strings.Builder
	b.Grow(estimated)
	idx := 1
	for r := 0; r < rows; r++ {
		if r > 0 {
			b.WriteByte(',')
		}
		b.WriteByte('(')
		for v := 0; v < valuesPerRow; v++ {
			if v > 0 {
				b.WriteByte(',')
			}
			b.WriteByte('$')
			b.WriteString(strconv.Itoa(idx))
			idx++
		}
		b.WriteByte(')')
	}
	return b.String()
}

// buildOnConflictUpdates returns a string like "col1 = EXCLUDED.col1, col2 = EXCLUDED.col2, ..."
func buildOnConflictUpdates(columns []string) string {
	var b strings.Builder
	b.Grow((len(columns) - 1) * 40)

	// conflict detection is based on UUIDs so they can't differ
	if columns[0] == "uuid" {
		columns = columns[1:]
	}

	for i, col := range columns {
		if i > 0 {
			b.WriteString(", ")
		}
		b.WriteString(col)
		b.WriteString(" = EXCLUDED.")
		b.WriteString(col)
	}

	return b.String()
}

// upsertData executes a single upsert SQL and parses the RETURNING rows into UpsertStats.
func upsertData(ctx context.Context, db *database.PGXPoolAdapter, query string, args []interface{}) (UpsertStats, error) {
	stats := UpsertStats{Inserted: 0, Updated: 0}

	ret, err := db.Query(ctx, query, args...)
	if err != nil {
		return stats, fmt.Errorf("executing save data query: %w", err)
	}

	for ret.Next() {
		var res UpsertResult
		if err = ret.Scan(&res.ID, &res.Updated); err != nil {
			return stats, fmt.Errorf("scanning upsert result: %w", err)
		}

		if res.Updated {
			stats.Updated++
		} else {
			stats.Inserted++
		}
	}

	ret.Close()

	if err = ret.Err(); err != nil {
		return stats, fmt.Errorf("after iterating upsert results: %w", err)
	}

	return stats, nil
}
