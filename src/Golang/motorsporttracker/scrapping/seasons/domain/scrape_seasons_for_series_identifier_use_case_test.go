package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
)

type ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite struct {
	suite.Suite

	useCase             *ScrapeSeasonsForSeriesIdentifierUseCase
	mockGateway         *motorsportstats.GatewayInMemory
	mockSaveSeasonsRepo *mockSaveSeasonsRepository
}

func (s *ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSaveSeasonsRepo = &mockSaveSeasonsRepository{}

	s.useCase = NewScrapeSeasonsForSeriesIdentifierUseCase(
		s.mockGateway,
		s.mockSaveSeasonsRepo,
	)
}

func (s *ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite) SetupSubTest() {
	s.mockSaveSeasonsRepo.seasonsCount = 0
	s.mockSaveSeasonsRepo.sentSeries = ""
}

func (s *ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite) TestExecute() {
	s.Run("it saves seasons for a single series identifier", func() {
		s.withSeasonsInGateway()

		err := s.useCase.Execute(context.Background(), "series-uuid")
		s.Require().NoError(err)
		s.Greater(s.mockSaveSeasonsRepo.seasonsCount, 0)
		s.Equal("series-uuid", s.mockSaveSeasonsRepo.sentSeries)
	})

	s.Run("it returns error when series identifier is empty", func() {
		s.withZeroSeasonsInGateway()

		err := s.useCase.Execute(context.Background(), "")
		s.Require().Error(err)
		s.Equal(0, s.mockSaveSeasonsRepo.seasonsCount)
	})
}

func TestUnit_ScrapeSeasonsForSeriesIdentifierUseCase(t *testing.T) {
	suite.Run(t, new(ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite))
}

func (s *ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite) withSeasonsInGateway() {
	s.mockGateway.SetSeasons([]*motorsportstats.Season{
		{UUID: "season-uuid", Name: fn.Ptr("Season 2023"), Year: fn.Ptr(2023), EndYear: fn.Ptr(2023)},
		{UUID: "season-uuid-2", Name: fn.Ptr("Season 2024"), Year: fn.Ptr(2024), EndYear: fn.Ptr(2024)},
		{UUID: "season-uuid-3", Name: fn.Ptr("Season 2025"), Year: fn.Ptr(2025), EndYear: fn.Ptr(2025)},
	})
}

func (s *ScrapeSeasonsForSeriesIdentifierUseCaseTestSuite) withZeroSeasonsInGateway() {
	s.mockGateway.SetSeasons([]*motorsportstats.Season{})
}
