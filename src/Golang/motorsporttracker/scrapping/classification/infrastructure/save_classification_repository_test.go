package infrastructure

import (
	"fmt"
	"os"
	"testing"

	"github.com/jackc/pgx/v5"
	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

// saveClassificationPrefix namespaces every UUID this suite writes; see scripts/fixture-prefix-check.sh.
const saveClassificationPrefix = "dbc082d8-53c0-468b"

type SaveClassificationRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveClassificationRepository
	helper     *shared.SaveRepositoryHelper
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.sessionFixtures()))

	suite.repository = NewSaveClassificationRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) TearDownSuite() {
	cleanUps := []string{
		`DELETE FROM classification_drivers WHERE classification IN (SELECT c.id from classifications c INNER JOIN sessions s ON s.id = c.session WHERE s.uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM classification_drivers_history WHERE classification IN (SELECT c.id from classifications c INNER JOIN sessions s ON s.id = c.session WHERE s.uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM classifications WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM classifications_history WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM retirements WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM retirements_history WHERE session IN (SELECT id FROM sessions WHERE uuid::text LIKE '%[1]s-0006-%%');`,
		`DELETE FROM drivers WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM drivers_history WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM teams WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM teams_history WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM garages WHERE unique_key::text LIKE '%[1]s-%%';`,
		`DELETE FROM garages_history WHERE unique_key::text LIKE '%[1]s-%%';`,
		`DELETE FROM sessions WHERE uuid::text LIKE '%[1]s-0006-%%';`,
		`DELETE FROM sessions_history WHERE uuid::text LIKE '%[1]s-0006-%%';`,
		`DELETE FROM events WHERE uuid::text = '%[1]s-0005-000000000001';`,
		`DELETE FROM events_history WHERE uuid::text = '%[1]s-0005-000000000001';`,
		`DELETE FROM venues WHERE uuid::text = '%[1]s-0001-000000000001';`,
		`DELETE FROM venues_history WHERE uuid::text = '%[1]s-0001-000000000001';`,
		`DELETE FROM countries WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM countries_history WHERE uuid::text LIKE '%[1]s-%%';`,
		`DELETE FROM seasons WHERE uuid::text = '%[1]s-0004-000000000001';`,
		`DELETE FROM seasons_history WHERE uuid::text = '%[1]s-0004-000000000001';`,
		`DELETE FROM series WHERE uuid::text = '%[1]s-0003-000000000001';`,
		`DELETE FROM series_history WHERE uuid::text = '%[1]s-0003-000000000001';`,
	}
	for _, sql := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), fmt.Sprintf(sql, saveClassificationPrefix)))
	}

	suite.repository.db.Close()
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) TestSaveClassification() {
	suite.Run("no-op when no classifications or retirements", func() {
		emptyClassification := suite.emptyClassification()
		err := suite.repository.SaveClassification(suite.T().Context(), saveClassificationPrefix+"-0006-000000000001", emptyClassification)
		suite.NoError(err)

		suite.Equal(0, suite.count(suite.T(), countClassificationsQuery, saveClassificationPrefix+"-0006-000000000001"))
		suite.Equal(0, suite.count(suite.T(), countClassificationDriversQuery, saveClassificationPrefix+"-0006-000000000001"))
		suite.Equal(0, suite.count(suite.T(), countRetirementsQuery, saveClassificationPrefix+"-0006-000000000001"))
	})

	suite.Run("saves a very simple classification", func() {
		classification := suite.verySimpleClassification()
		err := suite.repository.SaveClassification(suite.T().Context(), saveClassificationPrefix+"-0006-000000000002", classification)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "teams", saveClassificationPrefix+"-0007-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0007-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "countries", saveClassificationPrefix+"-0007-%"))
		suite.Equal(1, suite.count(suite.T(), countClassificationsQuery, saveClassificationPrefix+"-0006-000000000002"))
		suite.Equal(1, suite.count(suite.T(), countClassificationDriversQuery, saveClassificationPrefix+"-0006-000000000002"))
		suite.Equal(1, suite.count(suite.T(), countRetirementsQuery, saveClassificationPrefix+"-0006-000000000002"))
	})

	suite.Run("saves data when there are nil values", func() {
		classification := suite.classificationWithNilValues()
		err := suite.repository.SaveClassification(suite.T().Context(), saveClassificationPrefix+"-0006-000000000003", classification)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "teams", saveClassificationPrefix+"-0008-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0008-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "countries", saveClassificationPrefix+"-0008-%"))
		suite.Equal(1, suite.count(suite.T(), countClassificationsQuery, saveClassificationPrefix+"-0006-000000000003"))
		suite.Equal(1, suite.count(suite.T(), countClassificationDriversQuery, saveClassificationPrefix+"-0006-000000000003"))
		suite.Equal(1, suite.count(suite.T(), countRetirementsQuery, saveClassificationPrefix+"-0006-000000000003"))
	})

	suite.Run("saves everything from a complex classification", func() {
		classification := suite.complexClassification()
		err := suite.repository.SaveClassification(suite.T().Context(), saveClassificationPrefix+"-0006-000000000004", classification)
		suite.NoError(err)

		suite.Equal(3, suite.helper.Count(suite.T().Context(), "teams", saveClassificationPrefix+"-0009-%"))
		suite.Equal(10, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0009-%"))
		suite.Equal(2, suite.helper.Count(suite.T().Context(), "countries", saveClassificationPrefix+"-0009-%"))
		suite.Equal(4, suite.count(suite.T(), countClassificationsQuery, saveClassificationPrefix+"-0006-000000000004"))
		suite.Equal(11, suite.count(suite.T(), countClassificationDriversQuery, saveClassificationPrefix+"-0006-000000000004"))
		suite.Equal(2, suite.count(suite.T(), countRetirementsQuery, saveClassificationPrefix+"-0006-000000000004"))
	})

	suite.Run("saves one row per driver of a shared car", func() {
		session := saveClassificationPrefix + "-0006-000000000005"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.sharedDriveClassification("0010"))
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0010-%"))
		suite.Equal(2, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(2, suite.count(suite.T(), countClassificationDriversQuery, session))
		suite.Equal(
			[]string{
				"8|1|5|" + saveClassificationPrefix + "-0010-000000000001",
				"8|2|4|" + saveClassificationPrefix + "-0010-000000000002",
			},
			suite.classificationDrivers(suite.T(), session),
		)
	})

	suite.Run("saves a car number listed twice for the same driver and team", func() {
		session := saveClassificationPrefix + "-0006-000000000006"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.sameDriverTwiceClassification())
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0011-%"))
		suite.Equal(2, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(
			[]string{
				"11|1|0|" + saveClassificationPrefix + "-0011-000000000001",
				"11|2|0|" + saveClassificationPrefix + "-0011-000000000001",
			},
			suite.classificationDrivers(suite.T(), session),
		)
	})

	suite.Run("saves every retirement of a car, one per driver", func() {
		session := saveClassificationPrefix + "-0006-000000000007"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.retirementPerDriverClassification())
		suite.NoError(err)

		suite.Equal(4, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0012-%"))
		suite.Equal(1, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(3, suite.count(suite.T(), countClassificationDriversQuery, session))
		suite.Equal(4, suite.count(suite.T(), countRetirementsQuery, session))
	})

	suite.Run("saves a retirement for a car absent from the classification", func() {
		session := saveClassificationPrefix + "-0006-000000000008"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.retirementWithoutClassificationRow())
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-0013-%"))
		suite.Equal(1, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(1, suite.count(suite.T(), countRetirementsQuery, session))
	})

	suite.Run("fails before any write when a row has no team", func() {
		session := saveClassificationPrefix + "-0006-000000000009"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.classificationWithoutTeam())
		suite.ErrorContains(err, "car number 502 has no team")

		suite.assertNothingWritten(session, "0014")
	})

	suite.Run("fails before any write when a retirement has no driver", func() {
		session := saveClassificationPrefix + "-0006-000000000010"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.retirementWithoutDriver())
		suite.ErrorContains(err, "retirement for car number 601 has no driver")

		suite.assertNothingWritten(session, "0015")
	})

	suite.Run("fails before any write when a row lists a driver twice", func() {
		session := saveClassificationPrefix + "-0006-000000000011"
		err := suite.repository.SaveClassification(suite.T().Context(), session, suite.driverTwiceOnOneRow())
		suite.ErrorContains(err, "car number 701 lists driver "+saveClassificationPrefix+"-0016-000000000001 twice")

		suite.assertNothingWritten(session, "0016")
	})

	suite.Run("updates rows in place when a shared car is saved again", func() {
		session := saveClassificationPrefix + "-0006-000000000012"
		classification := suite.sharedDriveClassification("0017")

		suite.NoError(suite.repository.SaveClassification(suite.T().Context(), session, classification))
		suite.NoError(suite.repository.SaveClassification(suite.T().Context(), session, classification))
		suite.Equal(2, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(2, suite.count(suite.T(), countClassificationsHistoryQuery, session))

		classification.Details[1].Points = fn.Ptr(3.0)
		suite.NoError(suite.repository.SaveClassification(suite.T().Context(), session, classification))
		suite.Equal(2, suite.count(suite.T(), countClassificationsQuery, session))
		suite.Equal(3, suite.count(suite.T(), countClassificationsHistoryQuery, session))
		suite.Equal(2, suite.count(suite.T(), countClassificationDriversQuery, session))
		suite.Equal(
			[]string{
				"8|1|5|" + saveClassificationPrefix + "-0017-000000000001",
				"8|2|3|" + saveClassificationPrefix + "-0017-000000000002",
			},
			suite.classificationDrivers(suite.T(), session),
		)
	})
}

func TestIntegration_SaveClassificationRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveClassificationRepositoryIntegrationTestSuite))
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) sessionFixtures() string {
	sessions := ""
	for i := 1; i <= 12; i++ {
		if i > 1 {
			sessions += ",\n"
		}
		sessions += fmt.Sprintf(`('%[1]s-0006-%012[2]d',
(SELECT id FROM events WHERE events.uuid = '%[1]s-0005-000000000001'),
'session', '%[1]s-0006-%012[2]d')`, saveClassificationPrefix, i)
	}

	return fmt.Sprintf(`
INSERT INTO venues (uuid, hash) VALUES
('%[1]s-0001-000000000001', '%[1]s-0001-000000000001')
ON CONFLICT (uuid) DO NOTHING;
INSERT INTO countries (uuid, hash) VALUES
('%[1]s-0002-000000000001', '%[1]s-0002-000000000001')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO series(uuid, name, hash) VALUES
('%[1]s-0003-000000000001', 'series', '%[1]s-0003-000000000001')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO seasons (uuid, series, year, hash) VALUES
('%[1]s-0004-000000000001',
(SELECT id FROM series WHERE series.uuid = '%[1]s-0003-000000000001'),
2025, '%[1]s-0004-000000000001')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO events (uuid, season, venue, country, name, hash) VALUES
('%[1]s-0005-000000000001',
(SELECT id FROM seasons WHERE seasons.uuid = '%[1]s-0004-000000000001'),
(SELECT id FROM venues WHERE uuid = '%[1]s-0001-000000000001'),
(SELECT id FROM countries WHERE uuid = '%[1]s-0002-000000000001'),
'event', '%[1]s-0005-000000000001')
ON CONFLICT (uuid) DO NOTHING;

INSERT INTO sessions (uuid, event, name, hash) VALUES
%[2]s
ON CONFLICT (uuid) DO NOTHING;
`, saveClassificationPrefix, sessions)
}

const countClassificationsQuery = `
	SELECT count(c.id)
	FROM classifications c
	INNER JOIN sessions s ON s.id = c.session
	WHERE s.uuid::text = '%s'
`

const countClassificationsHistoryQuery = `
	SELECT count(c.history_id)
	FROM classifications_history c
	INNER JOIN sessions s ON s.id = c.session
	WHERE s.uuid::text = '%s'
`

const countRetirementsQuery = `
	SELECT count(r.id)
	FROM retirements r
	INNER JOIN sessions s ON s.id = r.session
	WHERE s.uuid::text = '%s'
`

const countClassificationDriversQuery = `
	SELECT count(cd.id)
	FROM classification_drivers cd
	INNER JOIN classifications c ON cd.classification = c.id
	INNER JOIN sessions s ON s.id = c.session
	WHERE s.uuid::text = '%s'
`

// classificationDriversQuery lists "car_number|occurrence|points|driver_uuid" per classification driver of a session.
const classificationDriversQuery = `
	SELECT c.car_number || '|' || c.occurrence || '|' || coalesce(c.points::text, 'null') || '|' || d.uuid::text
	FROM classification_drivers cd
	INNER JOIN classifications c ON cd.classification = c.id
	INNER JOIN drivers d ON d.id = cd.driver
	INNER JOIN sessions s ON s.id = c.session
	WHERE s.uuid::text = '%s'
	ORDER BY c.car_number, c.occurrence, d.uuid
`

func (suite *SaveClassificationRepositoryIntegrationTestSuite) count(t *testing.T, query string, session string) int {
	t.Helper()

	rows := fn.MustReturn(suite.repository.db.Query(t.Context(), fmt.Sprintf(query, session))).(pgx.Rows)
	defer rows.Close()

	rows.Next()

	var count int
	fn.Must(rows.Scan(&count))

	return count
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) classificationDrivers(t *testing.T, session string) []string {
	t.Helper()

	rows := fn.MustReturn(suite.repository.db.Query(t.Context(), fmt.Sprintf(classificationDriversQuery, session))).(pgx.Rows)
	defer rows.Close()

	links := make([]string, 0)
	for rows.Next() {
		var link string
		fn.Must(rows.Scan(&link))
		links = append(links, link)
	}

	return links
}

// assertNothingWritten checks that a rejected save left no row behind, in the session or in the case's UUID group.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) assertNothingWritten(session string, group string) {
	suite.Equal(0, suite.helper.Count(suite.T().Context(), "countries", saveClassificationPrefix+"-"+group+"-%"))
	suite.Equal(0, suite.helper.Count(suite.T().Context(), "drivers", saveClassificationPrefix+"-"+group+"-%"))
	suite.Equal(0, suite.helper.Count(suite.T().Context(), "teams", saveClassificationPrefix+"-"+group+"-%"))
	suite.Equal(0, suite.count(suite.T(), countClassificationsQuery, session))
	suite.Equal(0, suite.count(suite.T(), countRetirementsQuery, session))
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) emptyClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details:     []*motorsportstats.ClassificationDetail{},
		Retirements: []*motorsportstats.Retirement{},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) verySimpleClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:      "1",
				FinishPosition: fn.Ptr(1),
				GridPosition:   fn.Ptr(2),
				Drivers: []*motorsportstats.Driver{
					{
						UUID:      saveClassificationPrefix + "-0007-000000000001",
						Name:      fn.Ptr("Max Verstappen"),
						FirstName: fn.Ptr("Max"),
						LastName:  fn.Ptr("Verstappen"),
						ShortCode: fn.Ptr("MV"),
						Colour:    fn.Ptr("Blue"),
						Picture:   fn.Ptr("some url"),
					},
				},
				Team: &motorsportstats.Team{
					UUID:    saveClassificationPrefix + "-0007-000000000001",
					Name:    fn.Ptr("Red Bull"),
					Colour:  fn.Ptr("Blue"),
					Picture: fn.Ptr("some url"),
					CarIcon: fn.Ptr("some url"),
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0007-000000000001",
					Name: fn.Ptr("Austria"),
					Flag: fn.Ptr("at.svg"),
				},
				Laps:             fn.Ptr(10),
				Points:           fn.Ptr(25.0),
				Time:             fn.Ptr(180.0),
				ClassifiedStatus: fn.Ptr("CLA"),
				AvgLapSpeed:      fn.Ptr(184.0),
				FastestLapTime:   fn.Ptr(18.0),
				ClassificationGap: motorsportstats.ClassificationGap{
					TimeToLead: fn.Ptr(0.0),
					TimeToNext: fn.Ptr(0.0),
					LapsToLead: fn.Ptr(0),
					LapsToNext: fn.Ptr(0),
				},
				ClassificationBest: motorsportstats.ClassificationBest{
					Lap:     fn.Ptr(5),
					Time:    fn.Ptr(18.0),
					Fastest: fn.Ptr(true),
					Speed:   fn.Ptr(187.5),
				},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "1",
				Driver: &motorsportstats.Driver{
					UUID:      saveClassificationPrefix + "-0007-000000000001",
					Name:      fn.Ptr("Max Verstappen"),
					FirstName: fn.Ptr("Max"),
					LastName:  fn.Ptr("Verstappen"),
					ShortCode: fn.Ptr("MV"),
					Colour:    fn.Ptr("Blue"),
					Picture:   fn.Ptr("some url"),
				},
				Reason:  fn.Ptr("mechanical"),
				Type:    fn.Ptr("failure"),
				DNS:     fn.Ptr(false),
				Lap:     fn.Ptr(8),
				Details: fn.Ptr("bla bla bla"),
			},
		},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) classificationWithNilValues() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "11",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: saveClassificationPrefix + "-0008-000000000001",
					},
				},
				Team: &motorsportstats.Team{
					UUID: saveClassificationPrefix + "-0008-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0008-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "11",
				Driver: &motorsportstats.Driver{
					UUID: saveClassificationPrefix + "-0008-000000000001",
				},
			},
		},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) complexClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "101",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: saveClassificationPrefix + "-0009-000000000001",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000002",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000003",
					},
				},
				Team: &motorsportstats.Team{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "102",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: saveClassificationPrefix + "-0009-000000000004",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000005",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000006",
					},
				},
				Team: &motorsportstats.Team{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "103",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: saveClassificationPrefix + "-0009-000000000007",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000008",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000009",
					},
				},
				Team: &motorsportstats.Team{
					UUID: saveClassificationPrefix + "-0009-000000000002",
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
			{
				CarNumber: "104",
				Drivers: []*motorsportstats.Driver{
					{
						UUID: saveClassificationPrefix + "-0009-000000000010",
					},
					{
						UUID: saveClassificationPrefix + "-0009-000000000001",
					},
				},
				Team: &motorsportstats.Team{
					UUID: saveClassificationPrefix + "-0009-000000000003",
				},
				Nationality: &motorsportstats.Country{
					UUID: saveClassificationPrefix + "-0009-000000000002",
				},
				ClassificationGap:  motorsportstats.ClassificationGap{},
				ClassificationBest: motorsportstats.ClassificationBest{},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "101",
				Driver: &motorsportstats.Driver{
					UUID: saveClassificationPrefix + "-0009-000000000001",
				},
			},
			{
				CarNumber: "103",
				Driver: &motorsportstats.Driver{
					UUID: saveClassificationPrefix + "-0009-000000000008",
				},
			},
		},
	}
}

// groupUUID builds the nth UUID of a case's group, e.g. groupUUID("0010", 1) = "<prefix>-0010-000000000001".
func groupUUID(group string, n int) string {
	return fmt.Sprintf("%s-%s-%012d", saveClassificationPrefix, group, n)
}

// sharedDriveClassification lists two drivers who shared car #8, each classified 1st on their own row,
// with the points split between them.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) sharedDriveClassification(group string) *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:      "8",
				FinishPosition: fn.Ptr(1),
				Points:         fn.Ptr(5.0),
				Drivers:        []*motorsportstats.Driver{{UUID: groupUUID(group, 1)}},
				Team:           &motorsportstats.Team{UUID: groupUUID(group, 1)},
				Nationality:    &motorsportstats.Country{UUID: groupUUID(group, 1)},
			},
			{
				CarNumber:      "8",
				FinishPosition: fn.Ptr(1),
				Points:         fn.Ptr(4.0),
				Drivers:        []*motorsportstats.Driver{{UUID: groupUUID(group, 2)}},
				Team:           &motorsportstats.Team{UUID: groupUUID(group, 1)},
				Nationality:    &motorsportstats.Country{UUID: groupUUID(group, 1)},
			},
		},
		Retirements: []*motorsportstats.Retirement{},
	}
}

// sameDriverTwiceClassification lists one driver twice on car #11 for the same team, once disqualified
// and once as a practice-only entry, as motorsportstats does for de Angelis at the 1983 Brazilian GP.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) sameDriverTwiceClassification() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:        "11",
				Points:           fn.Ptr(0.0),
				ClassifiedStatus: fn.Ptr("DSQ"),
				Drivers:          []*motorsportstats.Driver{{UUID: groupUUID("0011", 1)}},
				Team:             &motorsportstats.Team{UUID: groupUUID("0011", 1)},
			},
			{
				CarNumber:        "11",
				Points:           fn.Ptr(0.0),
				ClassifiedStatus: fn.Ptr("PRA"),
				Drivers:          []*motorsportstats.Driver{{UUID: groupUUID("0011", 1)}},
				Team:             &motorsportstats.Team{UUID: groupUUID("0011", 1)},
			},
		},
		Retirements: []*motorsportstats.Retirement{},
	}
}

// retirementPerDriverClassification retires car #301 once per crew driver, plus once for the placeholder
// driver motorsportstats adds to WEC crews.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) retirementPerDriverClassification() *motorsportstats.Classification {
	retirements := []*motorsportstats.Retirement{
		{CarNumber: "301", Driver: &motorsportstats.Driver{UUID: groupUUID("0012", 4), Name: fn.Ptr("Shared Driver")}, Reason: fn.Ptr("Accident")},
	}
	for n := 1; n <= 3; n++ {
		retirements = append(retirements, &motorsportstats.Retirement{
			CarNumber: "301",
			Driver:    &motorsportstats.Driver{UUID: groupUUID("0012", n)},
			Reason:    fn.Ptr("Accident"),
		})
	}

	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "301",
				Drivers: []*motorsportstats.Driver{
					{UUID: groupUUID("0012", 1)},
					{UUID: groupUUID("0012", 2)},
					{UUID: groupUUID("0012", 3)},
				},
				Team: &motorsportstats.Team{UUID: groupUUID("0012", 1)},
			},
		},
		Retirements: retirements,
	}
}

// retirementWithoutClassificationRow retires car #402, whose driver never started and has no classification row.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) retirementWithoutClassificationRow() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "401",
				Drivers:   []*motorsportstats.Driver{{UUID: groupUUID("0013", 1)}},
				Team:      &motorsportstats.Team{UUID: groupUUID("0013", 1)},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{
				CarNumber: "402",
				Driver:    &motorsportstats.Driver{UUID: groupUUID("0013", 2)},
				Reason:    fn.Ptr("Fuel System"),
				DNS:       fn.Ptr(true),
			},
		},
	}
}

// classificationWithoutTeam has a valid first row, so nothing written proves validation runs before any write.
func (suite *SaveClassificationRepositoryIntegrationTestSuite) classificationWithoutTeam() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:   "501",
				Drivers:     []*motorsportstats.Driver{{UUID: groupUUID("0014", 1)}},
				Team:        &motorsportstats.Team{UUID: groupUUID("0014", 1)},
				Nationality: &motorsportstats.Country{UUID: groupUUID("0014", 1)},
			},
			{
				CarNumber: "502",
				Drivers:   []*motorsportstats.Driver{{UUID: groupUUID("0014", 2)}},
			},
		},
		Retirements: []*motorsportstats.Retirement{},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) retirementWithoutDriver() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber:   "601",
				Drivers:     []*motorsportstats.Driver{{UUID: groupUUID("0015", 1)}},
				Team:        &motorsportstats.Team{UUID: groupUUID("0015", 1)},
				Nationality: &motorsportstats.Country{UUID: groupUUID("0015", 1)},
			},
		},
		Retirements: []*motorsportstats.Retirement{
			{CarNumber: "601", Reason: fn.Ptr("Engine")},
		},
	}
}

func (suite *SaveClassificationRepositoryIntegrationTestSuite) driverTwiceOnOneRow() *motorsportstats.Classification {
	return &motorsportstats.Classification{
		Details: []*motorsportstats.ClassificationDetail{
			{
				CarNumber: "701",
				Drivers: []*motorsportstats.Driver{
					{UUID: groupUUID("0016", 1)},
					{UUID: groupUUID("0016", 1)},
				},
				Team:        &motorsportstats.Team{UUID: groupUUID("0016", 1)},
				Nationality: &motorsportstats.Country{UUID: groupUUID("0016", 1)},
			},
		},
		Retirements: []*motorsportstats.Retirement{},
	}
}
