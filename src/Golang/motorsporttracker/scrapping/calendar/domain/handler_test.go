package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeCalendarHandlerUnitTestSuite struct {
	suite.Suite

	handler              *ScrapeEventsHandler
	mockGateway          *motorsportstats.GatewayInMemory
	mockSeasonsIDRepo    *mockSeasonsIDRepository
	mockSaveCalendarRepo *mockSaveCalendarRepository
}

func (s *ScrapeCalendarHandlerUnitTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSeasonsIDRepo = &mockSeasonsIDRepository{}
	s.mockSaveCalendarRepo = &mockSaveCalendarRepository{}

	s.handler = NewScrapeEventsHandler(
		s.mockGateway,
		s.mockSaveCalendarRepo,
		s.mockSeasonsIDRepo,
	)
}

func (s *ScrapeCalendarHandlerUnitTestSuite) TestHandle() {
	s.T().Run("no-op when season identifier is not found", func(t *testing.T) {
		s.withNoMatchingSeason()

		err := s.handler.Handle(context.Background(), s.message())
		s.Require().NoError(err)
		s.Equal(0, s.mockSaveCalendarRepo.eventsCount)
	})

	s.T().Run("it stores events when season identifier is found", func(t *testing.T) {
		s.withAMatchingSeason()
		s.withEventsInGateway()

		err := s.handler.Handle(context.Background(), s.message())
		s.Require().NoError(err)
		s.Equal(2, s.mockSaveCalendarRepo.eventsCount)
		s.Equal("season", s.mockSaveCalendarRepo.sentSeason)
	})
}

func TestUnit_ScrapeEventsHandler(t *testing.T) {
	suite.Run(t, new(ScrapeCalendarHandlerUnitTestSuite))
}

func (s *ScrapeCalendarHandlerUnitTestSuite) withNoMatchingSeason() {
	s.mockSeasonsIDRepo.hit = false
}

func (s *ScrapeCalendarHandlerUnitTestSuite) withAMatchingSeason() {
	s.mockSeasonsIDRepo.identifier = "season"
	s.mockSeasonsIDRepo.hit = true
}

func (s *ScrapeCalendarHandlerUnitTestSuite) message() messaging.Message {
	return messaging.Message{
		Metadata: map[string]string{
			"series": "series",
			"year":   "2025",
		},
	}
}

func (s *ScrapeCalendarHandlerUnitTestSuite) withEventsInGateway() {
	s.mockGateway.SetCalendar(
		&motorsportstats.Calendar{
			Events: []*motorsportstats.Event{
				{
					UUID:      "event-1",
					Name:      "Event 1",
					ShortName: "Event 1",
					ShortCode: "E1",
					Status:    "",
					StartDate: 1704067200,
					EndDate:   1704153600,
					Venue: &motorsportstats.Venue{
						UUID:      "venue-1",
						Name:      "Venue 1",
						ShortName: "Venue 1",
						ShortCode: "V1",
					},
					Country: &motorsportstats.Country{
						UUID: "country-1",
						Name: "Country 1",
						Flag: "flag-1",
					},
					Sessions: []*motorsportstats.Session{},
				},
				{
					UUID:      "event-2",
					Name:      "Event 2",
					ShortName: "Event 2",
					ShortCode: "E2",
					Status:    "",
					StartDate: 1704728400,
					EndDate:   1704814800,
					Venue: &motorsportstats.Venue{
						UUID:      "venue-2",
						Name:      "Venue 2",
						ShortName: "Venue 2",
						ShortCode: "V2",
					},
					Country: &motorsportstats.Country{
						UUID: "country-2",
						Name: "Country 2",
						Flag: "flag-2",
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
