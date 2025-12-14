package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite struct {
	suite.Suite

	useCase                                 *ScrapeSeasonsForSeriesKeywordUseCase
	scrapeSeasonsForSeriesIdentifierUseCase *ScrapeSeasonsForSeriesIdentifierUseCase
	mockGateway                             *motorsportstats.GatewayInMemory
	mockSeriesRepo                          *mockSeriesRepository
	mockSaveSeasonsRepo                     *mockSaveSeasonsRepository
}

func (s *ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSeriesRepo = &mockSeriesRepository{}
	s.mockSaveSeasonsRepo = &mockSaveSeasonsRepository{}

	s.scrapeSeasonsForSeriesIdentifierUseCase = NewScrapeSeasonsForSeriesIdentifierUseCase(
		s.mockGateway,
		s.mockSaveSeasonsRepo,
	)

	s.useCase = NewScrapeSeasonsForSeriesKeywordUseCase(
		s.scrapeSeasonsForSeriesIdentifierUseCase,
		s.mockSeriesRepo,
	)
}

func (s *ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite) TestExecute() {
	s.T().Run("no-op when series is not found", func(t *testing.T) {
		s.withNoMatchingSeries()

		err := s.useCase.Execute(context.Background(), "series")
		s.Require().NoError(err)
		s.Equal(0, s.mockSaveSeasonsRepo.seasonsCount)
	})

	s.T().Run("it stores seasons when series is found", func(t *testing.T) {
		s.withAMatchingSeries("series")
		s.withSeasonsInGateway()

		err := s.useCase.Execute(context.Background(), "series")
		s.Require().NoError(err)
		s.Greater(s.mockSaveSeasonsRepo.seasonsCount, 0)
		s.Equal("series", s.mockSaveSeasonsRepo.sentSeries)
	})
}

func TestUnit_ScrapeSeasonsForSeriesKeywordUseCase(t *testing.T) {
	suite.Run(t, new(ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite))
}

func (s *ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite) withAMatchingSeries(identifier string) {
	s.mockSeriesRepo.identifier = identifier
	s.mockSeriesRepo.hit = true
}

func (s *ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite) withNoMatchingSeries() {
	s.mockSeriesRepo.identifier = ""
	s.mockSeriesRepo.hit = false
}

func (s *ScrapeSeasonsForSeriesKeywordUseCaseUnitTestSuite) withSeasonsInGateway() {
	s.mockGateway.SetSeasons([]*motorsportstats.Season{
		{UUID: "season-uuid", Name: fn.Ptr("Season 2023"), Year: fn.Ptr(2023), EndYear: fn.Ptr(2023)},
		{UUID: "season-uuid-2", Name: fn.Ptr("Season 2024"), Year: fn.Ptr(2024), EndYear: fn.Ptr(2024)},
		{UUID: "season-uuid-3", Name: fn.Ptr("Season 2025"), Year: fn.Ptr(2025), EndYear: fn.Ptr(2025)},
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
