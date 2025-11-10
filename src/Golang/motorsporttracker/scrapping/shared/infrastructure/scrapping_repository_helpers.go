package infrastructure

import (
	"context"
	"fmt"
	"time"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

func PrepareTimestamp(timestamp *int64) (dbVal *time.Time, hashVal int64) {
	if timestamp == nil {
		return nil, 0
	}

	time := time.Unix(*timestamp, 0)
	return &time, *timestamp
}

func GetIDsForValues(ctx context.Context, db *database.PGXPoolAdapter, table string, col string, values map[string]struct{}) (map[string]int, error) {
	if len(values) == 0 {
		return make(map[string]int), nil
	}

	i := 0
	queryValues := ""
	var args []interface{}
	for uuid := range values {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i + 1
		queryValues += fmt.Sprintf(" $%d", argPosition)
		args = append(args, uuid)
		i++
	}

	finalQuery := fmt.Sprintf("SELECT %s, id FROM %s WHERE %s IN (%s);", col, table, col, queryValues)

	idPerValue := make(map[string]int)

	ret, err := db.Query(ctx, finalQuery, args...)
	if err != nil {
		return idPerValue, fmt.Errorf("executing get IDs for UUIDs query: %w", err)
	}

	for ret.Next() {
		var id int
		var value string
		if err = ret.Scan(&value, &id); err != nil {
			return idPerValue, fmt.Errorf("scanning IDs for UUIDs: %w", err)
		}
		idPerValue[value] = id
	}

	ret.Close()

	err = ret.Err()
	if err != nil {
		return idPerValue, fmt.Errorf("after iterating IDs for UUIDs results: %w", err)
	}

	return idPerValue, nil
}
