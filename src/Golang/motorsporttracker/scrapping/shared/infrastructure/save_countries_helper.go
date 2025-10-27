package infrastructure

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	crypto "github.com/kishlin/MotorsportTracker/src/Golang/shared/crypto/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

func SaveCountries(ctx context.Context, db *database.PGXPoolAdapter, countries []*motorsportstats.Country) error {
	if len(countries) == 0 {
		slog.Debug("No countries to save")

		return nil
	}

	var rows [][]interface{}
	for _, country := range countries {
		nameVal := fn.Deref(country.Name, "")
		flagVal := fn.Deref(country.Flag, "")

		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s", country.UUID, nameVal, flagVal))
		rows = append(rows, []interface{}{country.UUID, country.Name, country.Flag, hash})
	}

	cols := []string{"uuid", "name", "flag", "hash"}

	stats, err := Save(ctx, db, "countries", "uuid", cols, rows)
	if err != nil {
		return fmt.Errorf("saving countries: %w", err)
	}

	slog.Info("Countries saved successfully", "count", len(countries), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}
