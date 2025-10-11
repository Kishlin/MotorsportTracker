package seasons

import (
	"context"
	"fmt"
	"log/slog"

	"github.com/kishlin/MotorsportTracker/src/Golang/shared/infrastructure/database"
)

type SearchSeriesRefRepository struct {
	db database.Database
}

func NewSearchSeriesRefRepository(db database.Database) *SearchSeriesRefRepository {
	return &SearchSeriesRefRepository{
		db: db,
	}
}

func (r *SearchSeriesRefRepository) Search(ctx context.Context, keyword string) (ref string, err error) {
	rows, err := r.db.Query(ctx, searchSeriesQuery, keyword, "%"+keyword+"%")
	if err != nil {
		return "", fmt.Errorf("searching keyword: %w", err)
	}
	defer func(rows database.Rows) {
		err := rows.Close()
		if err != nil {
			slog.Error("Failed closing rows", "err", err)
		}
	}(rows)

	if rows.Next() == false {
		return "", fmt.Errorf("no keyword found for: %s", keyword)
	}

	if err := rows.Scan(&ref); err != nil {
		return "", fmt.Errorf("scanning keyword ref: %w", err)
	}

	return ref, nil
}

const searchSeriesQuery = `
	WITH matched_series AS (
		SELECT 
			external_uuid,
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
	SELECT external_uuid FROM matched_series
	WHERE match_priority <= 6
	ORDER BY match_priority ASC
	LIMIT 1`
