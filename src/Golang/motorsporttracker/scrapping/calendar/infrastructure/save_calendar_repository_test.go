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

// saveCalendarPrefix namespaces every UUID this suite writes; see scripts/fixture-prefix-check.sh.
const saveCalendarPrefix = "77dde66e-7835-4440"

const seasonRef = saveCalendarPrefix + "-0006-000000000001"

const otherSeasonRef = saveCalendarPrefix + "-0006-000000000002"

type SaveCalendarRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveCalendarRepository
	helper     *shared.SaveRepositoryHelper
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) SetupSuite() {
	env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seasonFixture()))

	suite.repository = NewSaveCalendarRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) TearDownSuite() {
	for _, table := range []string{"sessions", "sessions_history", "events", "events_history", "venues", "venues_history", "countries", "countries_history", "seasons", "seasons_history", "series", "series_history"} {
		query := fmt.Sprintf("DELETE FROM %s WHERE uuid::text LIKE '%s-%%';", table, saveCalendarPrefix)
		fn.Must(suite.repository.db.Exec(suite.T().Context(), query))
	}

	suite.repository.db.Close()
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) TestSaveCalendar() {
	suite.Run("no-op when no events to save", func() {
		emptyCalendar := &motorsportstats.Calendar{}
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, emptyCalendar)
		suite.NoError(err)
	})

	suite.Run("saves events, venues, countries and sessions", func() {
		calendar := suite.verySimpleCalendar()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "events", saveCalendarPrefix+"-0001-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "venues", saveCalendarPrefix+"-0001-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "sessions", saveCalendarPrefix+"-0001-%"))
	})

	suite.Run("saves data when there are nil values", func() {
		calendar := suite.calendarWithNilValues()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(suite.T().Context(), "events", saveCalendarPrefix+"-0002-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "venues", saveCalendarPrefix+"-0002-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "sessions", saveCalendarPrefix+"-0002-%"))
	})

	suite.Run("saves everything with a complex calendar", func() {
		calendar := suite.bigCalendar()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(suite.T().Context(), "events", saveCalendarPrefix+"-0003-%"))
		suite.Equal(2, suite.helper.Count(suite.T().Context(), "venues", saveCalendarPrefix+"-0003-%"))
		suite.Equal(3, suite.helper.Count(suite.T().Context(), "sessions", saveCalendarPrefix+"-0003-%"))
	})

	suite.Run("saves everything with repeated venues and countries", func() {
		calendar := suite.calendarWithRepeatedVenuesAndCountries()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(suite.T().Context(), "events", saveCalendarPrefix+"-0004-%"))
		suite.Equal(1, suite.helper.Count(suite.T().Context(), "venues", saveCalendarPrefix+"-0004-%"))
		suite.Equal(2, suite.helper.Count(suite.T().Context(), "sessions", saveCalendarPrefix+"-0004-%"))
	})

	suite.Run("updates an event renamed upstream", func() {
		eventUUID := saveCalendarPrefix + "-0007-000000000001"

		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, suite.singleEventCalendar("0007", "Event 7", "Ev7", "E7"))
		suite.NoError(err)

		err = suite.repository.SaveCalendar(suite.T().Context(), seasonRef, suite.singleEventCalendar("0007", "Renamed Event 7", "REv7", "RE7"))
		suite.NoError(err)

		name, shortName, shortCode, season := suite.storedEvent(eventUUID)
		suite.Equal("Renamed Event 7", name)
		suite.Equal("REv7", shortName)
		suite.Equal("RE7", shortCode)
		suite.Equal(seasonRef, season)
	})

	suite.Run("moves an event to another season", func() {
		eventUUID := saveCalendarPrefix + "-0008-000000000001"

		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, suite.singleEventCalendar("0008", "Event 8", "Ev8", "E8"))
		suite.NoError(err)

		err = suite.repository.SaveCalendar(suite.T().Context(), otherSeasonRef, suite.singleEventCalendar("0008", "Event 8", "Ev8", "E8"))
		suite.NoError(err)

		_, _, _, season := suite.storedEvent(eventUUID)
		suite.Equal(otherSeasonRef, season)
	})
}

const storedEventQuery = `
SELECT e.name, e.short_name, e.short_code, s.uuid::text
FROM events e
JOIN seasons s ON s.id = e.season
WHERE e.uuid = $1;`

func (suite *SaveCalendarRepositoryIntegrationTestSuite) storedEvent(eventUUID string) (name string, shortName string, shortCode string, season string) {
	rows := fn.MustReturn(suite.repository.db.Query(suite.T().Context(), storedEventQuery, eventUUID)).(pgx.Rows)
	defer rows.Close()

	suite.Require().True(rows.Next(), "event %s not found", eventUUID)
	fn.Must(rows.Scan(&name, &shortName, &shortCode, &season))

	return name, shortName, shortCode, season
}

func TestIntegration_SaveCalendarRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveCalendarRepositoryIntegrationTestSuite))
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) seasonFixture() string {
	return fmt.Sprintf(`
INSERT INTO series (uuid, name, short_name, short_code, category, hash)
VALUES ('%[1]s-0005-000000000001', 'Calendar Series', 'CalendarSeries', 'CalendarS', 'Category 1', '%[1]s-0005')
ON CONFLICT DO NOTHING;

INSERT INTO seasons (uuid, series, name, year, end_year, hash)
VALUES ('%[1]s-0006-000000000001', 
(SELECT id FROM series WHERE uuid::text = '%[1]s-0005-000000000001'),
'2024', 2024, 2025, '%[1]s-0006')
ON CONFLICT DO NOTHING;

INSERT INTO seasons (uuid, series, name, year, end_year, hash)
VALUES ('%[1]s-0006-000000000002',
(SELECT id FROM series WHERE uuid::text = '%[1]s-0005-000000000001'),
'2025', 2025, 2026, '%[1]s-0006-000000000002')
ON CONFLICT DO NOTHING;
`, saveCalendarPrefix)
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) verySimpleCalendar() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      saveCalendarPrefix + "-0001-000000000001",
				Name:      fn.Ptr("Event 1"),
				ShortName: fn.Ptr("Ev1"),
				ShortCode: fn.Ptr("E1"),
				Status:    fn.Ptr("Scheduled"),
				StartTime: fn.Ptr(int64(1714675200)),
				EndTime:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0001-000000000002",
					Name:      fn.Ptr("Venue 1"),
					ShortName: fn.Ptr("V1"),
					ShortCode: fn.Ptr("VN1"),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0001-000000000003",
					Name: fn.Ptr("Country 1"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0001-000000000004",
						Name:       fn.Ptr("Session 1"),
						ShortName:  fn.Ptr("S1"),
						ShortCode:  fn.Ptr("SS1"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
		},
	}
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) calendarWithNilValues() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      saveCalendarPrefix + "-0002-000000000001",
				Name:      nil,
				ShortName: nil,
				ShortCode: nil,
				Status:    nil,
				StartTime: nil,
				EndTime:   nil,
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0002-000000000002",
					Name:      nil,
					ShortName: nil,
					ShortCode: nil,
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0002-000000000003",
					Name: nil,
					Flag: nil,
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0002-000000000004",
						Name:       nil,
						ShortName:  nil,
						ShortCode:  nil,
						Status:     nil,
						HasResults: nil,
						StartTime:  nil,
						EndTime:    nil,
					},
				},
			},
		},
	}
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) bigCalendar() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      saveCalendarPrefix + "-0003-000000000001",
				Name:      fn.Ptr("Event 3"),
				ShortName: fn.Ptr("Ev3"),
				ShortCode: fn.Ptr("E3"),
				Status:    fn.Ptr("Scheduled"),
				StartTime: fn.Ptr(int64(1714675200)),
				EndTime:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0003-000000000002",
					Name:      fn.Ptr("Venue 3"),
					ShortName: fn.Ptr("V3"),
					ShortCode: fn.Ptr("VN3"),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0003-000000000003",
					Name: fn.Ptr("Country 3"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0003-000000000004",
						Name:       fn.Ptr("Session 3"),
						ShortName:  fn.Ptr("S3"),
						ShortCode:  fn.Ptr("SS3"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
					{
						UUID:       saveCalendarPrefix + "-0003-000000000005",
						Name:       fn.Ptr("Session 4"),
						ShortName:  fn.Ptr("S4"),
						ShortCode:  fn.Ptr("SS4"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714768800)),
						EndTime:    fn.Ptr(int64(1714772400)),
					},
				},
			},
			{
				UUID:      saveCalendarPrefix + "-0003-000000000006",
				Name:      fn.Ptr("Event 4"),
				ShortName: fn.Ptr("Ev4"),
				ShortCode: fn.Ptr("E4"),
				Status:    fn.Ptr("Scheduled"),
				StartTime: fn.Ptr(int64(1714951200)),
				EndTime:   fn.Ptr(int64(1715124000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0003-000000000007",
					Name:      fn.Ptr("Venue 4"),
					ShortName: fn.Ptr("V4"),
					ShortCode: fn.Ptr("VN4"),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0003-000000000008",
					Name: fn.Ptr("Country 4"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0003-000000000009",
						Name:       fn.Ptr("Session 5"),
						ShortName:  fn.Ptr("S5"),
						ShortCode:  fn.Ptr("SS5"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714958400)),
						EndTime:    fn.Ptr(int64(1714962000)),
					},
				},
			},
		},
	}
}

// singleEventCalendar builds a one-event calendar whose UUIDs sit in the given group. Only the event's names vary, so
// saving it twice with different names changes nothing else.
func (suite *SaveCalendarRepositoryIntegrationTestSuite) singleEventCalendar(group string, name string, shortName string, shortCode string) *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      saveCalendarPrefix + "-" + group + "-000000000001",
				Name:      fn.Ptr(name),
				ShortName: fn.Ptr(shortName),
				ShortCode: fn.Ptr(shortCode),
				Status:    fn.Ptr("Scheduled"),
				StartTime: fn.Ptr(int64(1714675200)),
				EndTime:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-" + group + "-000000000002",
					Name:      fn.Ptr("Venue " + group),
					ShortName: fn.Ptr("V" + group),
					ShortCode: fn.Ptr("VN" + group),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-" + group + "-000000000003",
					Name: fn.Ptr("Country " + group),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-" + group + "-000000000004",
						Name:       fn.Ptr("Session " + group),
						ShortName:  fn.Ptr("S" + group),
						ShortCode:  fn.Ptr("SS" + group),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
		},
	}
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) calendarWithRepeatedVenuesAndCountries() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      saveCalendarPrefix + "-0004-000000000001",
				Name:      fn.Ptr("Event 6"),
				ShortName: fn.Ptr("Ev6"),
				ShortCode: fn.Ptr("E6"),
				Status:    fn.Ptr(""),
				StartTime: fn.Ptr(int64(1714675200)),
				EndTime:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0004-000000000002",
					Name:      fn.Ptr("Venue 7"),
					ShortName: fn.Ptr("V7"),
					ShortCode: fn.Ptr("VN7"),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0004-000000000003",
					Name: fn.Ptr("Country 7"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0004-000000000004",
						Name:       fn.Ptr("Session 8"),
						ShortName:  fn.Ptr("S8"),
						ShortCode:  fn.Ptr("SS8"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
			{
				UUID:      saveCalendarPrefix + "-0004-000000000005",
				Name:      fn.Ptr("Event 9"),
				ShortName: fn.Ptr("Ev9"),
				ShortCode: fn.Ptr("E9"),
				Status:    fn.Ptr(""),
				StartTime: fn.Ptr(int64(1714675200)),
				EndTime:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      saveCalendarPrefix + "-0004-000000000002",
					Name:      fn.Ptr("Venue 7"),
					ShortName: fn.Ptr("V7"),
					ShortCode: fn.Ptr("VN7"),
				},
				Country: &motorsportstats.Country{
					UUID: saveCalendarPrefix + "-0004-000000000003",
					Name: fn.Ptr("Country 7"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       saveCalendarPrefix + "-0004-000000000006",
						Name:       fn.Ptr("Session 10"),
						ShortName:  fn.Ptr("S10"),
						ShortCode:  fn.Ptr("SS10"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
		},
	}
}
