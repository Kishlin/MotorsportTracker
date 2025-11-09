package infrastructure

import (
	"context"
	"fmt"

	"github.com/jackc/pgx/v5"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type SaveRepositoryHelper struct {
	db *database.PGXPoolAdapter
}

func NewSaveRepositoryHelper(db *database.PGXPoolAdapter) *SaveRepositoryHelper {
	return &SaveRepositoryHelper{db: db}
}

const countQuery = "SELECT COUNT(id) FROM %s WHERE uuid::text LIKE '%s';"

func (s *SaveRepositoryHelper) Count(ctx context.Context, table string, uuidTemplate string) int {
	rows := fn.MustReturn(s.db.Query(ctx, fmt.Sprintf(countQuery, table, uuidTemplate))).(pgx.Rows)
	defer rows.Close()

	rows.Next()

	var count int
	fn.Must(rows.Scan(&count))

	return count
}
