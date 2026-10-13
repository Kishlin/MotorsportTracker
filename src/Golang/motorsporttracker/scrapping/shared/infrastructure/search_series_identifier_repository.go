package infrastructure

import (
	"context"
	"fmt"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type SearchSeriesIdentifierRepository struct {
	db *database.PGXPoolAdapter
}

// NewSearchSeriesIdentifierRepository creates a new instance of SearchSeriesIdentifierRepository.
func NewSearchSeriesIdentifierRepository(db *database.PGXPoolAdapter) *SearchSeriesIdentifierRepository {
	return &SearchSeriesIdentifierRepository{db: db}
}

// GetSeriesIdentifier retrieves the series identifier based on the provided keyword.
func (s *SearchSeriesIdentifierRepository) GetSeriesIdentifier(ctx context.Context, keyword string) (ref string, hit bool, err error) {
	rows, err := s.db.Query(ctx, searchSeriesQuery, keyword, "%"+keyword+"%")
	if err != nil {
		return "", false, fmt.Errorf("search series identifier: %w", err)
	}
	defer rows.Close()

	if rows.Next() == false {
		return "", false, nil
	}

	if err := rows.Scan(&ref); err != nil {
		return "", true, fmt.Errorf("scanning series identifier: %w", err)
	}

	return ref, true, nil
}

const searchSeriesQuery = `
WITH matched_series AS (
	SELECT 
		uuid,
		CASE 
			WHEN LOWER(short_code) = LOWER($1) THEN 1
			WHEN LOWER(short_name) = LOWER($1) THEN 2
			WHEN LOWER(name) = LOWER($1) THEN 3
			WHEN LOWER(short_code) LIKE LOWER($2) THEN 4
			WHEN LOWER(short_name) LIKE LOWER($2) THEN 5
			WHEN LOWER(name) LIKE LOWER($2) THEN 6
			ELSE 7
		END AS match_priority
	FROM series 
	WHERE LOWER(name) LIKE LOWER($2) 
	   OR LOWER(short_name) LIKE LOWER($2) 
	   OR LOWER(short_code) LIKE LOWER($2)
)
SELECT uuid FROM matched_series
WHERE match_priority <= 6
ORDER BY match_priority ASC
LIMIT 1`
