package infrastructure

import (
	"context"
	"fmt"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

func GetIDsForUUIDs(ctx context.Context, db *database.PGXPoolAdapter, table string, uuids map[string]struct{}) (map[string]int, error) {
	if len(uuids) == 0 {
		return make(map[string]int), nil
	}

	i := 0
	queryValues := ""
	var args []interface{}
	for uuid := range uuids {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i + 1
		queryValues += fmt.Sprintf(" $%d", argPosition)
		args = append(args, uuid)
		i++
	}

	finalQuery := fmt.Sprintf("SELECT uuid, id FROM %s WHERE uuid IN (%s);", table, queryValues)

	idPerUUID := make(map[string]int)

	ret, err := db.Query(ctx, finalQuery, args...)
	if err != nil {
		return idPerUUID, fmt.Errorf("executing get IDs for UUIDs query: %w", err)
	}

	for ret.Next() {
		var id int
		var uuid string
		if err = ret.Scan(&uuid, &id); err != nil {
			return idPerUUID, fmt.Errorf("scanning IDs for UUIDs: %w", err)
		}
		idPerUUID[uuid] = id
	}

	ret.Close()

	err = ret.Err()
	if err != nil {
		return idPerUUID, fmt.Errorf("after iterating IDs for UUIDs results: %w", err)
	}

	return idPerUUID, nil
}
