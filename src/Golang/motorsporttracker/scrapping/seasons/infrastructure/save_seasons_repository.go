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

type SaveSeasonsRepository struct {
	db *database.PGXPoolAdapter
}

func NewSaveSeasonsRepository(db *database.PGXPoolAdapter) *SaveSeasonsRepository {
	return &SaveSeasonsRepository{db: db}
}

// SaveSeasons saves seasons into the database.
func (s *SaveSeasonsRepository) SaveSeasons(ctx context.Context, series string, seasons []*motorsportstats.Season) error {
	if len(seasons) == 0 {
		slog.Debug("No seasons to save")

		return nil
	}

	seriesID, err := s.getSeriesID(ctx, series)
	if err != nil {
		return fmt.Errorf("getting series ID: %w", err)
	}

	var rows [][]interface{}
	for _, season := range seasons {
		hash := crypto.Hash(fmt.Sprintf("%s|%s|%s|%d|%d", season.UUID, series, season.Name, season.Year, season.EndYear))
		rows = append(rows, []interface{}{season.UUID, seriesID, season.Name, season.Year, season.EndYear, hash})
	}

	cols := []string{"uuid", "series", "name", "year", "end_year", "hash"}

	stats, err := shared.Save(ctx, s.db, "seasons", cols, rows)
	if err != nil {
		return fmt.Errorf("saving seasons: %w", err)
	}

	slog.Debug("Seasons saved successfully", "count", len(seasons), "inserted", stats.Inserted, "updated", stats.Updated)

	return nil
}

const seriesIDQuery = "SELECT id FROM series WHERE uuid = $1 LIMIT 1;"

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
