package infrastructure

import (
	"context"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
)

type ExistingSeasonsRepository struct {
	db *database.PGXPoolAdapter
}

func NewExistingSeasonsRepository(db *database.PGXPoolAdapter) *ExistingSeasonsRepository {
	return &ExistingSeasonsRepository{db: db}
}

// GetExistingSeasons retrieves existing seasons for a given series from the database.
func (r *ExistingSeasonsRepository) GetExistingSeasons(ctx context.Context, series string) (map[string]bool, error) {
	rows, err := r.db.Query(ctx, query, series)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	existingSeasons := make(map[string]bool)
	for rows.Next() {
		var uuid string
		if err := rows.Scan(&uuid); err != nil {
			return nil, err
		}
		existingSeasons[uuid] = true
	}

	return existingSeasons, nil
}

const query = `SELECT uuid FROM seasons WHERE series = $1`
