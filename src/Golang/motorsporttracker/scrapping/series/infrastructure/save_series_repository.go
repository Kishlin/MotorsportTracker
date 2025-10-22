package infrastructure

import (
	"context"
	"fmt"
	"log/slog"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	crypto "github.com/kishlin/MotorsportTracker/src/Golang/shared/crypto/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

// SaveSeriesRepository handles the persistence of series data into the database.
type SaveSeriesRepository struct {
	db *database.PGXPoolAdapter
}

// NewSaveSeriesRepository creates a new instance of SaveSeriesRepository.
func NewSaveSeriesRepository(db *database.PGXPoolAdapter) *SaveSeriesRepository {
	return &SaveSeriesRepository{db: db}
}

// SaveSeries saves a list of series into the database.
func (s *SaveSeriesRepository) SaveSeries(ctx context.Context, series []*motorsportstats.Series) error {
	if len(series) == 0 {
		slog.Debug("No series to save")

		return nil
	}

	var rows [][]interface{}
	for _, ser := range series {
		shortNameForHash := ""
		if ser.ShortName != nil {
			shortNameForHash = *ser.ShortName
		}

		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s|%s|%s", ser.UUID, ser.Name, shortNameForHash, ser.ShortCode, ser.Category))
		rows = append(rows, []interface{}{ser.UUID, ser.Name, ser.ShortName, ser.ShortCode, ser.Category, hash})
	}

	cols := []string{"uuid", "name", "short_name", "short_code", "category", "hash"}

	stats, err := shared.Save(ctx, s.db, "series", cols, rows)
	if err != nil {
		return fmt.Errorf("saving series: %w", err)
	}

	slog.Info("Series saved successfully", "count", len(series), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}
