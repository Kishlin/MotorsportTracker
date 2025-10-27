package infrastructure

import (
	"context"
	"fmt"
	"strconv"
	"strings"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

const maxParamsPerQuery = 1000

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

// Save chooses single vs batched execution based on total param count.
func Save(ctx context.Context, db *database.PGXPoolAdapter, table string, conflictColumns string, columns []string, rows [][]interface{}) (UpsertStats, error) {
	if len(rows) == 0 {
		return UpsertStats{}, nil
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
