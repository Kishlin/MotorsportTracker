package infrastructure

import (
	"context"
	"fmt"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type SearchAllSeriesIdentifiersRepository struct {
	db *database.PGXPoolAdapter
}

func NewSearchAllSeriesIdentifiersRepository(db *database.PGXPoolAdapter) *SearchAllSeriesIdentifiersRepository {
	return &SearchAllSeriesIdentifiersRepository{db: db}
}

func (s *SearchAllSeriesIdentifiersRepository) GetAllSeriesIdentifiers(ctx context.Context) (refs []string, err error) {
	rows, err := s.db.Query(ctx, "SELECT uuid FROM series;")
	if err != nil {
		return nil, fmt.Errorf("searching all series identifiers: %w", err)
	}

	for rows.Next() {
		var ref string
		if err := rows.Scan(&ref); err != nil {
			return nil, fmt.Errorf("scanning row: %w", err)
		}
		refs = append(refs, ref)
	}

	rows.Close()

	if err := rows.Err(); err != nil {
		return nil, fmt.Errorf("after scanning rows: %w", err)
	}

	return refs, nil
}
