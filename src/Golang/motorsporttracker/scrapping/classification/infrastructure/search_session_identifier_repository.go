package infrastructure

import (
	"context"
	"fmt"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type SearchSessionIdentifierRepository struct {
	db *database.PGXPoolAdapter
}

// NewSearchSessionIdentifierRepository creates a new instance of SearchSessionIdentifierRepository
func NewSearchSessionIdentifierRepository(db *database.PGXPoolAdapter) *SearchSessionIdentifierRepository {
	return &SearchSessionIdentifierRepository{db: db}
}

func (s *SearchSessionIdentifierRepository) GetSessionIdentifier(ctx context.Context, seriesKeyword string, year int, eventKeyword string, sessionKeyword string) (ref string, hit bool, err error) {
	rows, err := s.db.Query(ctx, searchSessionQuery,
		seriesKeyword,
		"%"+seriesKeyword+"%",
		eventKeyword,
		"%"+eventKeyword+"%",
		sessionKeyword,
		"%"+sessionKeyword+"%",
		year,
	)
	if err != nil {
		return "", false, fmt.Errorf("getting session identifier: %w", err)
	}

	if rows.Next() == false {
		return "", false, nil
	}

	if err = rows.Scan(&ref); err != nil {
		return "", true, fmt.Errorf("scanning season identifier: %w", err)
	}

	rows.Close()
	err = rows.Err()
	if err != nil {
		return "", true, fmt.Errorf("closing rows: %w", err)
	}

	return ref, true, nil
}

const searchSessionQuery = `
WITH matched_sessions AS (
    SELECT 
        sessions.uuid,
        CASE 
            WHEN series.short_code ILIKE $1 THEN 1
            WHEN series.short_name ILIKE $1 THEN 2
            WHEN series.name ILIKE $1 THEN 3
            WHEN series.short_code ILIKE $2 THEN 4
            WHEN series.short_name ILIKE $2 THEN 5
            WHEN series.name ILIKE $2 THEN 6
            ELSE 999
        END AS series_priority,
        CASE 
            WHEN events.short_code ILIKE $3 THEN 1
            WHEN events.short_name ILIKE $3 THEN 2
            WHEN events.name ILIKE $3 THEN 3
            WHEN events.short_code ILIKE $4 THEN 4
            WHEN events.short_name ILIKE $4 THEN 5
            WHEN events.name ILIKE $4 THEN 6
            ELSE 999
        END AS event_priority,
        CASE 
            WHEN sessions.short_code ILIKE $5 THEN 1
            WHEN sessions.short_name ILIKE $5 THEN 2
            WHEN sessions.name ILIKE $5 THEN 3
            WHEN sessions.short_code ILIKE $6 THEN 4
            WHEN sessions.short_name ILIKE $6 THEN 5
            WHEN sessions.name ILIKE $6 THEN 6
            ELSE 999
        END AS session_priority
    FROM sessions
    JOIN events ON sessions.event = events.id
    JOIN seasons ON events.season = seasons.id
    JOIN series ON seasons.series = series.id
    WHERE (series.name ILIKE $2 
            OR series.short_name ILIKE $2 
            OR series.short_code ILIKE $2)
        AND (events.name ILIKE $4 
            OR events.short_name ILIKE $4 
            OR events.short_code ILIKE $4)
        AND (sessions.name ILIKE $6 
            OR sessions.short_name ILIKE $6 
            OR sessions.short_code ILIKE $6)
        AND seasons.year = $7
)
SELECT uuid FROM matched_sessions
WHERE series_priority < 999 
    AND event_priority < 999 
    AND session_priority < 999
ORDER BY series_priority, event_priority, session_priority
LIMIT 1;
`
