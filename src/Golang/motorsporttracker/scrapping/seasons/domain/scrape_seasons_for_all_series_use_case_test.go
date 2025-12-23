package domain

import (
	"context"
	"errors"
	"testing"

	"github.com/stretchr/testify/suite"
)

type ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite struct {
	suite.Suite

	useCase                            *ScrapeSeasonsForAllSeriesUseCase
	mockAllSeriesIdentifiersRepository *mockAllSeriesIdentifiersRepository
	mockSeasonsScrapper                *mockSeasonsScrapper
}

func (s *ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite) SetupSuite() {
	s.mockAllSeriesIdentifiersRepository = &mockAllSeriesIdentifiersRepository{}
	s.mockSeasonsScrapper = &mockSeasonsScrapper{}

	s.useCase = NewScrapeSeasonsForAllSeriesUseCase(
		s.mockAllSeriesIdentifiersRepository,
		s.mockSeasonsScrapper,
	)
}

func (s *ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite) SetupSubTest() {
	s.mockSeasonsScrapper.publishedIdentifiers = 0
}

func (s *ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite) TestExecute() {
	s.Run("no-op when no series identifiers are found", func() {
		s.mockAllSeriesIdentifiersRepository.withZeroIdentifiers()

		err := s.useCase.Execute(context.Background())
		s.Require().NoError(err)
		s.Equal(0, s.mockSeasonsScrapper.publishedIdentifiers)
	})

	s.Run("it scrapes seasons for all series identifiers", func() {
		s.mockAllSeriesIdentifiersRepository.withMultipleIdentifiers()

		err := s.useCase.Execute(context.Background())
		s.Require().NoError(err)
		s.itProcessedAllPotentialSeries()
	})
}

func TestUnit_ScrapeSeasonsForAllSeriesUseCase(t *testing.T) {
	suite.Run(t, new(ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite))
}

func (s *ScrapeSeasonsForAllSeriesUseCaseUnitTestSuite) itProcessedAllPotentialSeries() {
	s.Equal(2, s.mockSeasonsScrapper.publishedIdentifiers)
}

type mockSeasonsScrapper struct {
	publishedIdentifiers int
}

func (m *mockSeasonsScrapper) ScrapeSeasonsForSeries(_ context.Context, identifier string) error {
	id := identifier[len(identifier)-1]
	if id%2 != 0 {
		m.publishedIdentifiers++
		return nil
	}

	return errors.New("mock error")
}

type mockAllSeriesIdentifiersRepository struct {
	identifiers []string
}

func (m *mockAllSeriesIdentifiersRepository) GetAllSeriesIdentifiers(_ context.Context) ([]string, error) {
	return m.identifiers, nil
}

func (m *mockAllSeriesIdentifiersRepository) withZeroIdentifiers() {
	m.identifiers = []string{}
}

func (m *mockAllSeriesIdentifiersRepository) withMultipleIdentifiers() {
	m.identifiers = []string{"series-uuid-1", "series-uuid-2", "series-uuid-3", "series-uuid-4", "series-uuid-5"}
}
