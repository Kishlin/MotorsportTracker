package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type ScrapeSeriesHandlerUnitTestSuite struct {
	suite.Suite

	handler             *ScrapeSeasonsHandler
	mockGateway         *motorsportstats.GatewayInMemory
	mockSeriesRepo      *mockSeriesRepository
	mockSaveSeasonsRepo *mockSaveSeasonsRepository
}

func (s *ScrapeSeriesHandlerUnitTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSeriesRepo = &mockSeriesRepository{}
	s.mockSaveSeasonsRepo = &mockSaveSeasonsRepository{}

	s.handler = NewScrapeSeasonsHandler(
		s.mockGateway,
		s.mockSaveSeasonsRepo,
		s.mockSeriesRepo,
	)
}

func (s *ScrapeSeriesHandlerUnitTestSuite) TestHandle() {
	s.T().Run("no-op when series is not found", func(t *testing.T) {
		s.withNoMatchingSeries()

		err := s.handler.Handle(context.Background(), s.message())
		s.Require().NoError(err)
		s.Equal(0, s.mockSaveSeasonsRepo.seasonsCount)
	})

	s.T().Run("it stores seasons when series is found", func(t *testing.T) {
		s.withAMatchingSeries("series")
		s.withSeasonsInGateway()

		err := s.handler.Handle(context.Background(), s.message())
		s.Require().NoError(err)
		s.Greater(s.mockSaveSeasonsRepo.seasonsCount, 0)
		s.Equal("series", s.mockSaveSeasonsRepo.sentSeries)
	})
}

func TestUnit_ScrapeSeasonsHandler(t *testing.T) {
	suite.Run(t, new(ScrapeSeriesHandlerUnitTestSuite))
}

func (s *ScrapeSeriesHandlerUnitTestSuite) withAMatchingSeries(identifier string) {
	s.mockSeriesRepo.identifier = identifier
	s.mockSeriesRepo.hit = true
}

func (s *ScrapeSeriesHandlerUnitTestSuite) withNoMatchingSeries() {
	s.mockSeriesRepo.identifier = ""
	s.mockSeriesRepo.hit = false
}

func (s *ScrapeSeriesHandlerUnitTestSuite) message() messaging.Message {
	return messaging.Message{
		Metadata: map[string]string{
			"series": "series",
		},
	}
}

func (s *ScrapeSeriesHandlerUnitTestSuite) withSeasonsInGateway() {
	s.mockGateway.SetSeasons([]*motorsportstats.Season{
		{UUID: "season-uuid", Name: "Season 2023", Year: 2023, EndYear: 2023},
		{UUID: "season-uuid-2", Name: "Season 2024", Year: 2024, EndYear: 2024},
		{UUID: "season-uuid-3", Name: "Season 2025", Year: 2025, EndYear: 2025},
	})
}

type mockSeriesRepository struct {
	identifier string
	hit        bool
}

func (m *mockSeriesRepository) GetSeriesIdentifier(_ context.Context, _ string) (string, bool, error) {
	return m.identifier, m.hit, nil
}

type mockSaveSeasonsRepository struct {
	seasonsCount int
	sentSeries   string
}

func (m *mockSaveSeasonsRepository) SaveSeasons(_ context.Context, series string, seasons []*motorsportstats.Season) error {
	m.seasonsCount = len(seasons)
	m.sentSeries = series
	return nil
}
