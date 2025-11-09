package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type UseCaseUnitTestSuite struct {
	suite.Suite

	useCase              *ScrapeCalendarUseCase
	mockGateway          *motorsportstats.GatewayInMemory
	mockSeasonsIDRepo    *mockSeasonsIDRepository
	mockSaveCalendarRepo *mockSaveCalendarRepository
}

func (s *UseCaseUnitTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSeasonsIDRepo = &mockSeasonsIDRepository{}
	s.mockSaveCalendarRepo = &mockSaveCalendarRepository{}

	s.useCase = NewScrapeCalendarUseCase(
		s.mockGateway,
		s.mockSaveCalendarRepo,
		s.mockSeasonsIDRepo,
	)
}

func (s *UseCaseUnitTestSuite) TestExecute() {
	s.T().Run("no-op when season identifier is not found", func(t *testing.T) {
		s.withNoMatchingSeason()

		err := s.useCase.Execute(context.Background(), "series", 2025)
		s.Require().NoError(err)
		s.Equal(0, s.mockSaveCalendarRepo.eventsCount)
	})

	s.T().Run("it stores events when season identifier is found", func(t *testing.T) {
		s.withAMatchingSeason()
		s.withEventsInGateway()

		err := s.useCase.Execute(context.Background(), "series", 2025)
		s.Require().NoError(err)
		s.Equal(2, s.mockSaveCalendarRepo.eventsCount)
		s.Equal("season", s.mockSaveCalendarRepo.sentSeason)
	})
}

func TestUnit_UseCase(t *testing.T) {
	suite.Run(t, new(UseCaseUnitTestSuite))
}

func (s *UseCaseUnitTestSuite) withNoMatchingSeason() {
	s.mockSeasonsIDRepo.hit = false
}

func (s *UseCaseUnitTestSuite) withAMatchingSeason() {
	s.mockSeasonsIDRepo.identifier = "season"
	s.mockSeasonsIDRepo.hit = true
}

func (s *UseCaseUnitTestSuite) withEventsInGateway() {
	s.mockGateway.SetCalendar(
		&motorsportstats.Calendar{
			Events: []*motorsportstats.Event{
				{
					UUID:      "event-1",
					Name:      fn.Ptr("Event 1"),
					ShortName: fn.Ptr("Event 1"),
					ShortCode: fn.Ptr("E1"),
					Status:    fn.Ptr(""),
					StartTime: fn.Ptr(int64(1704067200)),
					EndTime:   fn.Ptr(int64(1704153600)),
					Venue: &motorsportstats.Venue{
						UUID:      "venue-1",
						Name:      fn.Ptr("Venue 1"),
						ShortName: fn.Ptr("Venue 1"),
						ShortCode: fn.Ptr("V1"),
					},
					Country: &motorsportstats.Country{
						UUID: "country-1",
						Name: fn.Ptr("Country 1"),
						Flag: fn.Ptr("flag-1"),
					},
					Sessions: []*motorsportstats.Session{},
				},
				{
					UUID:      "event-2",
					Name:      fn.Ptr("Event 2"),
					ShortName: fn.Ptr("Event 2"),
					ShortCode: fn.Ptr("E2"),
					Status:    fn.Ptr(""),
					StartTime: fn.Ptr(int64(1704728400)),
					EndTime:   fn.Ptr(int64(1704814800)),
					Venue: &motorsportstats.Venue{
						UUID:      "venue-2",
						Name:      fn.Ptr("Venue 2"),
						ShortName: fn.Ptr("Venue 2"),
						ShortCode: fn.Ptr("V2"),
					},
					Country: &motorsportstats.Country{
						UUID: "country-2",
						Name: fn.Ptr("Country 2"),
						Flag: fn.Ptr("flag-2"),
					},
					Sessions: []*motorsportstats.Session{},
				},
			},
		},
	)
}

type mockSaveCalendarRepository struct {
	eventsCount int
	sentSeason  string
}

func (m *mockSaveCalendarRepository) SaveCalendar(_ context.Context, season string, calendar *motorsportstats.Calendar) error {
	m.eventsCount += len(calendar.Events)
	m.sentSeason = season
	return nil
}

type mockSeasonsIDRepository struct {
	identifier string
	hit        bool
}

func (m *mockSeasonsIDRepository) GetSeasonIdentifier(_ context.Context, _ string, _ int) (ref string, hit bool, err error) {
	return m.identifier, m.hit, nil
}
