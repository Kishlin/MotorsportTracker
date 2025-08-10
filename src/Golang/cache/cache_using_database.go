package cache

import (
	"context"
	"fmt"
	"log/slog"

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

const getQuery = "SELECT value FROM %s WHERE key = $1 LIMIT 1"

func (c *DatabaseCache) Get(namespace, key string) (value []byte, hit bool, err error) {
	logger := slog.With("namespace", namespace, "key", key)

	rows, err := c.db.Query(context.Background(), fmt.Sprintf(getQuery, namespace), key)
	if err != nil {
		return nil, false, err
	}
	defer func(rows database.Rows) {
		err := rows.Close()
		if err != nil {
			logger.Error("Failed to close rows", "error", err)
		}
	}(rows)

	if !rows.Next() {
		logger.Debug("Cache miss")
		return nil, false, nil // No value found
	}

	var val []byte
	if err := rows.Scan(&val); err != nil {
		return nil, true, err
	}

	logger.Debug("Cache hit")
	return val, true, nil
}

const setQuery = "INSERT INTO %s (key, value) VALUES ($1, $2) ON CONFLICT(key) DO UPDATE SET value = $3"

func (c *DatabaseCache) Set(namespace, key string, value []byte) error {
	err := c.db.Exec(context.Background(), fmt.Sprintf(setQuery, namespace), key, value, value)
	if err != nil {
		return fmt.Errorf("setting the value in cache: %w", err)
	}

	slog.Debug("Set value", "namespace", namespace, "key", key)

	return nil
}
