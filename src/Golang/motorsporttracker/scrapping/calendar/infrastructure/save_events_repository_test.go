package infrastructure

import (
	"os"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	shared "github.com/kishlin/MotorsportTracker/src/Golang/motorsporttracker/scrapping/shared/infrastructure"
	database "github.com/kishlin/MotorsportTracker/src/Golang/shared/database/infrastructure"
	env "github.com/kishlin/MotorsportTracker/src/Golang/shared/env/infrastructure"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

const seasonRef = "eeefdfaa-69b8-4226-86d2-000000000001"

type SaveCalendarRepositoryIntegrationTestSuite struct {
	suite.Suite

	repository *SaveCalendarRepository
	helper     *shared.SaveRepositoryHelper

	resetEnv func()
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) SetupSuite() {
	suite.resetEnv = env.OverrideAppEnv("tests")
	fn.Must(env.LoadEnv())

	db := database.NewDatabaseUsingPGXPool(os.Getenv("POSTGRES_CORE_URL"))
	fn.Must(db.Connect(suite.T().Context()))
	fn.Must(db.Exec(suite.T().Context(), suite.seasonFixture()))

	suite.repository = NewSaveCalendarRepository(db)
	suite.helper = shared.NewSaveRepositoryHelper(db)
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) TearDownSuite() {
	cleanUps := []string{
		`DELETE FROM sessions WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM sessions_history WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM events WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM events_history WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM venues WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM venues_history WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM countries WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM countries_history WHERE uuid::text LIKE '77dde66e-7835-4440-%';`,
		`DELETE FROM seasons WHERE uuid::text = 'eeefdfaa-69b8-4226-86d2-000000000001';`,
		`DELETE FROM series WHERE uuid::text = '70d5b480-8935-4fe5-a8e6-000000000001';`,
	}
	for _, sql := range cleanUps {
		fn.Must(suite.repository.db.Exec(suite.T().Context(), sql))
	}

	suite.repository.db.Close()
	suite.resetEnv()
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) TestSaveCalendar() {
	suite.T().Run("no-op when no events to save", func(t *testing.T) {
		emptyCalendar := &motorsportstats.Calendar{}
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, emptyCalendar)
		suite.NoError(err)
	})

	suite.T().Run("saves events, venues, countries and sessions", func(t *testing.T) {
		calendar := suite.verySimpleCalendar()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "events", "77dde66e-7835-4440-0001-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "venues", "77dde66e-7835-4440-0001-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "sessions", "77dde66e-7835-4440-0001-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "countries", "77dde66e-7835-4440-0001-%"))
	})

	suite.T().Run("saves data when there are nil values", func(t *testing.T) {
		calendar := suite.calendarWithNilValues()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(1, suite.helper.Count(t.Context(), "events", "77dde66e-7835-4440-0002-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "venues", "77dde66e-7835-4440-0002-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "sessions", "77dde66e-7835-4440-0002-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "countries", "77dde66e-7835-4440-0002-%"))
	})

	suite.T().Run("saves everything with a complex calendar", func(t *testing.T) {
		calendar := suite.bigCalendar()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(t.Context(), "events", "77dde66e-7835-4440-0003-%"))
		suite.Equal(2, suite.helper.Count(t.Context(), "venues", "77dde66e-7835-4440-0003-%"))
		suite.Equal(3, suite.helper.Count(t.Context(), "sessions", "77dde66e-7835-4440-0003-%"))
		suite.Equal(2, suite.helper.Count(t.Context(), "countries", "77dde66e-7835-4440-0003-%"))
	})

	suite.T().Run("saves everything with repeated venues and countries", func(t *testing.T) {
		calendar := suite.calendarWithRepeatedVenuesAndCountries()
		err := suite.repository.SaveCalendar(suite.T().Context(), seasonRef, calendar)
		suite.NoError(err)

		suite.Equal(2, suite.helper.Count(t.Context(), "events", "77dde66e-7835-4440-0004-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "venues", "77dde66e-7835-4440-0004-%"))
		suite.Equal(2, suite.helper.Count(t.Context(), "sessions", "77dde66e-7835-4440-0004-%"))
		suite.Equal(1, suite.helper.Count(t.Context(), "countries", "77dde66e-7835-4440-0004-%"))
	})
}

func TestIntegration_SaveCalendarRepository(t *testing.T) {
	t.Parallel()

	suite.Run(t, new(SaveCalendarRepositoryIntegrationTestSuite))
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) seasonFixture() string {
	return `
INSERT INTO series (uuid, name, short_name, short_code, category, hash)
VALUES ('70d5b480-8935-4fe5-a8e6-000000000001', 'Calendar Series', 'CalendarSeries', 'CalendarS', 'Category 1', '70d5b480-8935-4fe5-a8e6');

INSERT INTO seasons (uuid, series, name, year, end_year, hash)
VALUES ('eeefdfaa-69b8-4226-86d2-000000000001', 
(SELECT id FROM series WHERE uuid::text = '70d5b480-8935-4fe5-a8e6-000000000001'),
'2024', 2024, 2025, 'eeefdfaa-69b8-4226-86d2');
`
}

func (suite *SaveCalendarRepositoryIntegrationTestSuite) verySimpleCalendar() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      "77dde66e-7835-4440-0001-000000000001",
				Name:      fn.Ptr("Event 1"),
				ShortName: fn.Ptr("Ev1"),
				ShortCode: fn.Ptr("E1"),
				Status:    fn.Ptr("Scheduled"),
				StartDate: fn.Ptr(int64(1714675200)),
				EndDate:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0001-000000000002",
					Name:      fn.Ptr("Venue 1"),
					ShortName: fn.Ptr("V1"),
					ShortCode: fn.Ptr("VN1"),
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0001-000000000003",
					Name: fn.Ptr("Country 1"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0001-000000000004",
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
				UUID:      "77dde66e-7835-4440-0002-000000000001",
				Name:      nil,
				ShortName: nil,
				ShortCode: nil,
				Status:    nil,
				StartDate: nil,
				EndDate:   nil,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0002-000000000002",
					Name:      nil,
					ShortName: nil,
					ShortCode: nil,
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0002-000000000003",
					Name: nil,
					Flag: nil,
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0002-000000000004",
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
				UUID:      "77dde66e-7835-4440-0003-000000000001",
				Name:      fn.Ptr("Event 3"),
				ShortName: fn.Ptr("Ev3"),
				ShortCode: fn.Ptr("E3"),
				Status:    fn.Ptr("Scheduled"),
				StartDate: fn.Ptr(int64(1714675200)),
				EndDate:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0003-000000000002",
					Name:      fn.Ptr("Venue 3"),
					ShortName: fn.Ptr("V3"),
					ShortCode: fn.Ptr("VN3"),
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0003-000000000003",
					Name: fn.Ptr("Country 3"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0003-000000000004",
						Name:       fn.Ptr("Session 3"),
						ShortName:  fn.Ptr("S3"),
						ShortCode:  fn.Ptr("SS3"),
						Status:     fn.Ptr("Scheduled"),
						HasResults: fn.Ptr(false),
						StartTime:  fn.Ptr(int64(1714682400)),
						EndTime:    fn.Ptr(int64(1714682400)),
					},
					{
						UUID:       "77dde66e-7835-4440-0003-000000000005",
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
				UUID:      "77dde66e-7835-4440-0003-000000000006",
				Name:      fn.Ptr("Event 4"),
				ShortName: fn.Ptr("Ev4"),
				ShortCode: fn.Ptr("E4"),
				Status:    fn.Ptr("Scheduled"),
				StartDate: fn.Ptr(int64(1714951200)),
				EndDate:   fn.Ptr(int64(1715124000)),
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0003-000000000007",
					Name:      fn.Ptr("Venue 4"),
					ShortName: fn.Ptr("V4"),
					ShortCode: fn.Ptr("VN4"),
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0003-000000000008",
					Name: fn.Ptr("Country 4"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0003-000000000009",
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

func (suite *SaveCalendarRepositoryIntegrationTestSuite) calendarWithRepeatedVenuesAndCountries() *motorsportstats.Calendar {
	return &motorsportstats.Calendar{
		Events: []*motorsportstats.Event{
			{
				UUID:      "77dde66e-7835-4440-0004-000000000001",
				Name:      fn.Ptr("Event 6"),
				ShortName: fn.Ptr("Ev6"),
				ShortCode: fn.Ptr("E6"),
				Status:    fn.Ptr(""),
				StartDate: fn.Ptr(int64(1714675200)),
				EndDate:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0004-000000000002",
					Name:      fn.Ptr("Venue 7"),
					ShortName: fn.Ptr("V7"),
					ShortCode: fn.Ptr("VN7"),
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0004-000000000003",
					Name: fn.Ptr("Country 7"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0004-000000000004",
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
				UUID:      "77dde66e-7835-4440-0004-000000000005",
				Name:      fn.Ptr("Event 9"),
				ShortName: fn.Ptr("Ev9"),
				ShortCode: fn.Ptr("E9"),
				Status:    fn.Ptr(""),
				StartDate: fn.Ptr(int64(1714675200)),
				EndDate:   fn.Ptr(int64(1714848000)),
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0004-000000000002",
					Name:      fn.Ptr("Venue 7"),
					ShortName: fn.Ptr("V7"),
					ShortCode: fn.Ptr("VN7"),
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0004-000000000003",
					Name: fn.Ptr("Country 7"),
					Flag: fn.Ptr("ca.svg"),
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0004-000000000006",
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
