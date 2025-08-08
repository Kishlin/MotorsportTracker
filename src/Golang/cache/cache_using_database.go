package cache

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

type DatabaseCache struct {
	db database.Database
}

func NewDatabaseCache(db database.Database) *DatabaseCache {
	return &DatabaseCache{
		db: db,
	}
}

const getQuery = "SELECT value FROM %s WHERE key = ? LIMIT 1"

func (c *DatabaseCache) Get(namespace, key string) (value []byte, err error) {
	rows, err := c.db.Query(context.Background(), fmt.Sprintf(getQuery, namespace), key)
	if err != nil {
		return nil, err
	}
	defer func(rows database.Rows) {
		err := rows.Close()
		if err != nil {
			fmt.Printf("Error closing rows: %v\n", err)
		}
	}(rows)

	if !rows.Next() {
		return nil, nil // No value found
	}

	var val []byte
	if err := rows.Scan(&val); err != nil {
		return nil, err
	}

	return val, nil
}

const setQuery = "INSERT INTO %s (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = ?"

func (c *DatabaseCache) Set(namespace, key string, value []byte) error {
	err := c.db.Exec(context.Background(), fmt.Sprintf(setQuery, namespace), key, value, value)
	if err != nil {
		return fmt.Errorf("failed to set value in cache: %w", err)
	}

	return nil
}
