package infrastructure

import (
	"context"
	"fmt"
	"os"
	"testing"

	"github.com/jackc/pgx/v5"
	"github.com/stretchr/testify/suite"

	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// probeTable mirrors the shape every scraped table shares: a unique uuid, some
// payload, and a unique hash used for change detection. It is created and
// dropped by this suite so the assertions never depend on migrated schema.
const probeTable = "save_helpers_probe"

const createProbeTable = `
CREATE TABLE IF NOT EXISTS ` + probeTable + ` (
    id SERIAL PRIMARY KEY,
    uuid UUID UNIQUE NOT NULL,
    label TEXT,
    hash TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);`

type SaveRepositoryHelpersIntegrationTestSuite struct {
	suite.Suite

	db *database.PGXPoolAdapter

	resetEnv func()
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	suite.db = database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(suite.db.Connect(suite.T().Context()))

	fn.Must(suite.db.Exec(suite.T().Context(), "DROP TABLE IF EXISTS "+probeTable+";"))
	fn.Must(suite.db.Exec(suite.T().Context(), createProbeTable))
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) TearDownSuite() {
	fn.Must(suite.db.Exec(suite.T().Context(), "DROP TABLE IF EXISTS "+probeTable+";"))
	suite.db.Close()
	suite.resetEnv()
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) SetupSubTest() {
	fn.Must(suite.db.Exec(suite.T().Context(), "TRUNCATE "+probeTable+" RESTART IDENTITY;"))
}

var probeColumns = []string{"uuid", "label", "hash"}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) TestSave() {
	ctx := context.Background()

	suite.Run("no rows is a no-op", func() {
		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, nil)
		suite.NoError(err)
		suite.Equal(UpsertStats{}, stats)
		suite.Equal(0, suite.countRows())
	})

	suite.Run("inserts new rows", func() {
		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(3, "first"))
		suite.NoError(err)

		suite.Equal(3, stats.Inserted)
		suite.Equal(0, stats.Updated)
		suite.Equal(3, suite.countRows())
	})

	suite.Run("skips rows whose hash is unchanged", func() {
		_, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(3, "first"))
		suite.NoError(err)

		// Same payload, therefore the same hash: the WHERE clause on the upsert
		// excludes these rows, so they are neither inserted nor updated.
		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(3, "first"))
		suite.NoError(err)

		suite.Equal(0, stats.Inserted)
		suite.Equal(0, stats.Updated)
		suite.Equal(3, suite.countRows())
	})

	suite.Run("updates rows whose hash changed", func() {
		_, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(3, "first"))
		suite.NoError(err)

		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(3, "second"))
		suite.NoError(err)

		suite.Equal(0, stats.Inserted)
		suite.Equal(3, stats.Updated)
		suite.Equal(3, suite.countRows(), "an update must not create a new row")
		suite.Equal("second-1", suite.labelOf(1), "the payload must actually be overwritten")
	})

	suite.Run("counts inserts and updates separately in a mixed save", func() {
		_, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(2, "first"))
		suite.NoError(err)

		// Row 1 unchanged, row 2 changed, row 3 brand new.
		mixed := [][]interface{}{
			suite.row(1, "first"),
			suite.row(2, "second"),
			suite.row(3, "first"),
		}

		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, mixed)
		suite.NoError(err)

		suite.Equal(1, stats.Inserted)
		suite.Equal(1, stats.Updated)
		suite.Equal(3, suite.countRows())
	})

	suite.Run("batches beyond the parameter limit", func() {
		// 700 rows x 3 columns = 2100 parameters, over maxParamsPerQuery (1000),
		// so Save() routes through UpsertInBatches and aggregates the results.
		const rowCount = 700

		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(rowCount, "first"))
		suite.NoError(err)

		suite.Equal(rowCount, stats.Inserted, "every batch must be counted")
		suite.Equal(0, stats.Updated)
		suite.Equal(rowCount, suite.countRows())
	})

	suite.Run("updates across batches too", func() {
		const rowCount = 700

		_, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(rowCount, "first"))
		suite.NoError(err)

		stats, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, suite.rows(rowCount, "second"))
		suite.NoError(err)

		suite.Equal(0, stats.Inserted)
		suite.Equal(rowCount, stats.Updated)
		suite.Equal(rowCount, suite.countRows())
	})

	suite.Run("rejects inconsistent row widths", func() {
		malformed := [][]interface{}{
			suite.row(1, "first"),
			{"9f1c2d3e-4a5b-6c7d-8e9f-000000000002"},
		}

		_, err := Save(ctx, suite.db, probeTable, "uuid", probeColumns, malformed)
		suite.Error(err)
		suite.Equal(0, suite.countRows(), "a rejected save must not write anything")
	})
}

func TestIntegration_SaveRepositoryHelpers(t *testing.T) {
	suite.Run(t, new(SaveRepositoryHelpersIntegrationTestSuite))
}

// row builds a single row whose hash depends on the label, so reusing a label
// means an unchanged hash and changing it forces an update.
func (suite *SaveRepositoryHelpersIntegrationTestSuite) row(index int, label string) []interface{} {
	return []interface{}{
		fmt.Sprintf("9f1c2d3e-4a5b-6c7d-8e9f-%012d", index),
		fmt.Sprintf("%s-%d", label, index),
		fmt.Sprintf("hash-%d-%s", index, label),
	}
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) rows(count int, label string) [][]interface{} {
	rows := make([][]interface{}, 0, count)
	for index := 1; index <= count; index++ {
		rows = append(rows, suite.row(index, label))
	}

	return rows
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) countRows() int {
	rows := fn.MustReturn(suite.db.Query(suite.T().Context(), "SELECT COUNT(id) FROM "+probeTable+";")).(pgx.Rows)
	defer rows.Close()

	rows.Next()

	var count int
	fn.Must(rows.Scan(&count))

	return count
}

func (suite *SaveRepositoryHelpersIntegrationTestSuite) labelOf(index int) string {
	query := "SELECT label FROM " + probeTable + " WHERE uuid = $1;"
	uuid := fmt.Sprintf("9f1c2d3e-4a5b-6c7d-8e9f-%012d", index)

	rows := fn.MustReturn(suite.db.Query(suite.T().Context(), query, uuid)).(pgx.Rows)
	defer rows.Close()

	rows.Next()

	var label string
	fn.Must(rows.Scan(&label))

	return label
}
