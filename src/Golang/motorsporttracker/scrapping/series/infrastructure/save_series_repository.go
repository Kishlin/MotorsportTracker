package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/series/domain"
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
func (s *SaveSeriesRepository) SaveSeries(ctx context.Context, series []*domain.Series) error {
	if len(series) == 0 {
		return nil
	}

	queryPrefix := "INSERT INTO series (uuid, name, short_name, short_code, category) VALUES"

	queryValues := ""
	var args []interface{}
	for i, s := range series {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*5 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4)
		args = append(args, s.UUID, s.Name, s.ShortName, s.ShortCode, s.Category)
	}

	err := s.db.Exec(ctx, queryPrefix+queryValues, args...)
	if err != nil {
		return fmt.Errorf("inserting series: %w", err)
	}

	return nil
}
