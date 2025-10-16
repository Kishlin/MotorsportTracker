package infrastructure

import (
	"context"
	"fmt"

	"github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/seasons/domain"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type SaveSeasonsRepository struct {
	db *database.PGXPoolAdapter
}

func NewSaveSeasonsRepository(db *database.PGXPoolAdapter) *SaveSeasonsRepository {
	return &SaveSeasonsRepository{db: db}
}

// SaveSeasons saves seasons into the database.
func (s *SaveSeasonsRepository) SaveSeasons(ctx context.Context, series string, seasons []*domain.Season) error {
	if len(seasons) == 0 {
		return nil
	}

	queryValues := ""
	var args []interface{}
	for i, season := range seasons {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*5 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4)
		args = append(args, season.UUID, series, season.Name, season.Year, season.EndYear)
	}

	err := s.db.Exec(ctx, queryPrefix+queryValues, args...)
	if err != nil {
		return fmt.Errorf("inserting seasons: %w", err)
	}

	return nil
}

const queryPrefix = "INSERT INTO seasons (uuid, series, name, year, end_year) VALUES"
