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

	useCase            *ScrapeSeriesUseCase
	mockGateway        *motorsportstats.GatewayInMemory
	mockSaveSeriesRepo *mockSaveSeriesRepository
}

func (suite *UseCaseUnitTestSuite) SetupSuite() {
	suite.mockGateway = motorsportstats.NewGatewayInMemory()
	suite.mockSaveSeriesRepo = &mockSaveSeriesRepository{}

	suite.useCase = NewScrapeSeriesUseCase(
		suite.mockGateway,
		suite.mockSaveSeriesRepo,
	)
}

func (suite *UseCaseUnitTestSuite) TestExecute() {
	suite.T().Run("it stores series from the gateway", func(t *testing.T) {
		suite.withSeriesInGateway()

		err := suite.useCase.Execute(context.Background())
		suite.Require().NoError(err)
		suite.Equal(2, suite.mockSaveSeriesRepo.seriesCount)
	})
}

func TestUnit_UseCase(t *testing.T) {
	suite.Run(t, new(UseCaseUnitTestSuite))
}

func (suite *UseCaseUnitTestSuite) withSeriesInGateway() {
	suite.mockGateway.SetSeries([]*motorsportstats.Series{
		{
			UUID:      "series-1",
			Name:      fn.Ptr("Series 1"),
			ShortCode: fn.Ptr("S1"),
			Category:  fn.Ptr("Category 1"),
		},
		{
			UUID:      "series-2",
			Name:      fn.Ptr("Series 2"),
			ShortCode: fn.Ptr("S2"),
			Category:  fn.Ptr("Category 2"),
		},
	})
}

type mockSaveSeriesRepository struct {
	seriesCount int
}

func (m *mockSaveSeriesRepository) SaveSeries(_ context.Context, series []*motorsportstats.Series) error {
	m.seriesCount = len(series)
	return nil
}
