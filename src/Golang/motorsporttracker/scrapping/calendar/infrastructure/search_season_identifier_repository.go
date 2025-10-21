package infrastructure

import (
	"context"
	"fmt"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type SearchSeasonIdentifierRepository struct {
	db *database.PGXPoolAdapter
}

// NewSearchSeasonIdentifierRepository creates a new instance of SearchSeasonIdentifierRepository.
func NewSearchSeasonIdentifierRepository(db *database.PGXPoolAdapter) *SearchSeasonIdentifierRepository {
	return &SearchSeasonIdentifierRepository{db: db}
}

// GetSeasonIdentifier retrieves the season identifier based on the provided series keyword and year.
func (s *SearchSeasonIdentifierRepository) GetSeasonIdentifier(ctx context.Context, seriesKeyword string, year int) (ref string, hit bool, err error) {
	rows, err := s.db.Query(ctx, searchSeasonQuery, seriesKeyword, "%"+seriesKeyword+"%", year)
	if err != nil {
		return "", false, fmt.Errorf("searching season identifier: %w", err)
	}
	defer rows.Close()

	if rows.Next() == false {
		return "", false, nil
	}

	if err := rows.Scan(&ref); err != nil {
		return "", true, fmt.Errorf("scanning season identifier: %w", err)
	}

	return ref, true, nil
}

const searchSeasonQuery = `
WITH matched_seasons AS (
	SELECT 
		seasons.uuid,
		CASE 
			WHEN LOWER(series.short_code) = LOWER($1) THEN 1
			WHEN LOWER(series.short_name) = LOWER($1) THEN 2
			WHEN LOWER(series.name) = LOWER($1) THEN 3
			WHEN LOWER(series.short_code) LIKE LOWER($2) THEN 4
			WHEN LOWER(series.short_name) LIKE LOWER($2) THEN 5
			WHEN LOWER(series.name) LIKE LOWER($2) THEN 6
			ELSE 7
		END AS match_priority
	FROM seasons 
	JOIN series ON seasons.series = series.id
	WHERE (LOWER(series.name) LIKE LOWER($2) 
		   OR LOWER(series.short_name) LIKE LOWER($2) 
		   OR LOWER(series.short_code) LIKE LOWER($2))
	  AND seasons.year = $3
)
SELECT uuid FROM matched_seasons
WHERE match_priority <= 6
ORDER BY match_priority ASC
LIMIT 1
`
