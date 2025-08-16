package series

import (
	"context"
	"fmt"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

type FindSeriesRepository struct {
	db database.Database
}

// NewFindSeriesRepository creates a new repository for finding series.
func NewFindSeriesRepository(db database.Database) *FindSeriesRepository {
	return &FindSeriesRepository{db: db}
}

// FindAll retrieves all series from the database.
func (r *FindSeriesRepository) FindAll(ctx context.Context) (map[string]Series, error) {
	rows, err := r.db.Query(ctx, "SELECT name, external_uuid, short_name, short_code, category FROM series")
	if err != nil {
		return nil, fmt.Errorf("querying existing series: %w", err)
	}
	defer func(rows database.Rows) {
		err := rows.Close()
		if err != nil {
			slog.Error("Error closing rows", "err", err)
		}
	}(rows)

	seres := make(map[string]Series)

	for rows.Next() {
		var series Series
		err := rows.Scan(&series.Name, &series.ExternalUUID, &series.ShortName, &series.ShortCode, &series.Category)
		if err != nil {
			return nil, fmt.Errorf("scanning series row: %w", err)
		}

		// Debug logging to see what we actually scanned
		slog.Debug("Scanned series", "name", series.Name, "external_uuid", series.ExternalUUID)

		seres[series.ExternalUUID] = series
	}

	if err := rows.Err(); err != nil {
		return nil, fmt.Errorf("iterating series rows: %w", err)
	}

	slog.Info("Returning existing series from database", "existing_count", len(seres))
	return seres, nil
}
