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
				Name:      "Event 1",
				ShortName: "Ev1",
				ShortCode: "E1",
				Status:    "Scheduled",
				StartDate: 1714675200,
				EndDate:   1714848000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0001-000000000002",
					Name:      "Venue 1",
					ShortName: "V1",
					ShortCode: "VN1",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0001-000000000003",
					Name: "Country 1",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0001-000000000004",
						Name:       "Session 1",
						ShortName:  "S1",
						ShortCode:  "SS1",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714682400,
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
				Name:      "Event 2",
				ShortName: "Ev2",
				ShortCode: "E2",
				Status:    "",
				StartDate: 1714675200,
				EndDate:   1714848000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0002-000000000002",
					Name:      "Venue 2",
					ShortName: "V2",
					ShortCode: "VN2",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0002-000000000003",
					Name: "Country 2",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0002-000000000004",
						Name:       "Session 2",
						ShortName:  "S2",
						ShortCode:  "SS2",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714682400,
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
				Name:      "Event 3",
				ShortName: "Ev3",
				ShortCode: "E3",
				Status:    "Scheduled",
				StartDate: 1714675200,
				EndDate:   1714848000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0003-000000000002",
					Name:      "Venue 3",
					ShortName: "V3",
					ShortCode: "VN3",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0003-000000000003",
					Name: "Country 3",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0003-000000000004",
						Name:       "Session 3",
						ShortName:  "S3",
						ShortCode:  "SS3",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714682400,
						EndTime:    fn.Ptr(int64(1714682400)),
					},
					{
						UUID:       "77dde66e-7835-4440-0003-000000000005",
						Name:       "Session 4",
						ShortName:  "S4",
						ShortCode:  "SS4",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714768800,
						EndTime:    fn.Ptr(int64(1714772400)),
					},
				},
			},
			{
				UUID:      "77dde66e-7835-4440-0003-000000000006",
				Name:      "Event 4",
				ShortName: "Ev4",
				ShortCode: "E4",
				Status:    "Scheduled",
				StartDate: 1714951200,
				EndDate:   1715124000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0003-000000000007",
					Name:      "Venue 4",
					ShortName: "V4",
					ShortCode: "VN4",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0003-000000000008",
					Name: "Country 4",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0003-000000000009",
						Name:       "Session 5",
						ShortName:  "S5",
						ShortCode:  "SS5",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714958400,
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
				Name:      "Event 6",
				ShortName: "Ev6",
				ShortCode: "E6",
				Status:    "",
				StartDate: 1714675200,
				EndDate:   1714848000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0004-000000000002",
					Name:      "Venue 7",
					ShortName: "V7",
					ShortCode: "VN7",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0004-000000000003",
					Name: "Country 7",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0004-000000000004",
						Name:       "Session 8",
						ShortName:  "S8",
						ShortCode:  "SS8",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714682400,
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
			{
				UUID:      "77dde66e-7835-4440-0004-000000000005",
				Name:      "Event 9",
				ShortName: "Ev9",
				ShortCode: "E9",
				Status:    "",
				StartDate: 1714675200,
				EndDate:   1714848000,
				Venue: &motorsportstats.Venue{
					UUID:      "77dde66e-7835-4440-0004-000000000002",
					Name:      "Venue 7",
					ShortName: "V7",
					ShortCode: "VN7",
				},
				Country: &motorsportstats.Country{
					UUID: "77dde66e-7835-4440-0004-000000000003",
					Name: "Country 7",
					Flag: "ca.svg",
				},
				Sessions: []*motorsportstats.Session{
					{
						UUID:       "77dde66e-7835-4440-0004-000000000006",
						Name:       "Session 10",
						ShortName:  "S10",
						ShortCode:  "SS10",
						Status:     "Scheduled",
						HasResults: false,
						StartTime:  1714682400,
						EndTime:    fn.Ptr(int64(1714682400)),
					},
				},
			},
		},
	}
}
