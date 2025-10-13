package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type ExistingSeriesRepository struct {
	db *database.PGXPoolAdapter
}

// NewExistingSeriesRepository creates a new instance of ExistingSeriesRepository.
func NewExistingSeriesRepository(db *database.PGXPoolAdapter) *ExistingSeriesRepository {
	return &ExistingSeriesRepository{db: db}
}

// GetExistingSeries retrieves all existing series from the database and returns them as a map keyed by series uuid.
func (e *ExistingSeriesRepository) GetExistingSeries(ctx context.Context) (map[string]*domain.Series, error) {
	query := `
		SELECT
			uuid, name, short_name, short_code, category
		FROM
			series;
	`

	rows, err := e.db.Query(ctx, query)
	if err != nil {
		return nil, fmt.Errorf("retrieving existing series: %w", err)
	}
	defer rows.Close()

	existingSeries := make(map[string]*domain.Series)
	for rows.Next() {
		var series domain.Series
		if err := rows.Scan(&series.UUID, &series.Name, &series.ShortName, &series.ShortCode, &series.Category); err != nil {
			return nil, fmt.Errorf("scanning existing series: %w", err)
		}
		existingSeries[series.UUID] = &series
	}
	if err := rows.Err(); err != nil {
		return nil, fmt.Errorf("iterating over existing series rows: %w", err)
	}

	return existingSeries, nil
}
