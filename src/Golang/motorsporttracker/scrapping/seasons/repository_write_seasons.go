package seasons

import (
	"context"
	"fmt"
	"strings"

	"github.com/kishlin/MotorsportTracker/src/Golang/database"
)

type RepositoryWriteSeasons struct {
	db database.Database
}

func NewRepositoryWriteSeasons(db database.Database) *RepositoryWriteSeasons {
	return &RepositoryWriteSeasons{
		db: db,
	}
}

func (r *RepositoryWriteSeasons) Write(ctx context.Context, seasons []Season) error {
	parameters := make([]any, len(seasons)*3)
	queryParts := make([]string, len(seasons))

	for i, season := range seasons {
		parameters[i*3] = season.UUID
		parameters[i*3+1] = season.Year
		parameters[i*3+2] = season.EndYear
		queryParts[i] = fmt.Sprintf("($%d, $%d, $%d)", i*3+1, i*3+2, i*3+3)
	}

	err := r.db.Exec(
		ctx,
		writeSeasonsQueryPrefix+strings.Join(queryParts, ", "),
		parameters...,
	)
	if err != nil {
		return fmt.Errorf("inserting seasons: %w", err)
	}

	return nil
}

const writeSeasonsQueryPrefix = `	INSERT INTO seasons (external_uuid, year, end_year) VALUES `
