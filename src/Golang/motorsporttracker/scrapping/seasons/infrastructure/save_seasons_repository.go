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

	seriesID, err := s.getSeriesID(ctx, series)
	if err != nil {
		return fmt.Errorf("getting series ID: %w", err)
	}

	queryValues := ""
	var args []interface{}
	for i, season := range seasons {
		if i > 0 {
			queryValues += ","
		}
		argPosition := i*5 + 1
		queryValues += fmt.Sprintf(" ($%d, $%d, $%d, $%d, $%d)", argPosition, argPosition+1, argPosition+2, argPosition+3, argPosition+4)
		args = append(args, season.UUID, seriesID, season.Name, season.Year, season.EndYear)
	}

	err = s.db.Exec(ctx, queryPrefix+queryValues, args...)
	if err != nil {
		return fmt.Errorf("inserting seasons: %w", err)
	}

	return nil
}

func (s *SaveSeasonsRepository) getSeriesID(ctx context.Context, series string) (int, error) {
	ret, err := s.db.Query(ctx, seriesIDQuery, series)
	if err != nil {
		return 0, fmt.Errorf("fetching series ID: %w", err)
	}
	defer ret.Close()

	if ret.Next() == false {
		return 0, fmt.Errorf("series with UUID %s not found", series)
	}

	var seriesID int
	err = ret.Scan(&seriesID)
	if err != nil {
		return 0, fmt.Errorf("scanning series ID: %w", err)
	}

	return seriesID, nil
}

const queryPrefix = "INSERT INTO seasons (uuid, series, name, year, end_year) VALUES"

const seriesIDQuery = "SELECT id FROM series WHERE uuid = $1 LIMIT 1;"
