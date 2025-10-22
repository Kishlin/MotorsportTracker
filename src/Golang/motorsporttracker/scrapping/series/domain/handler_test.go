package domain

import (
	"context"
	"testing"

	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
	fn "github.com/kishlin/MotorsportTracker/src/Golang/shared/fn/domain"
	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
)

type HandlerUnitTestSuite struct {
	suite.Suite

	handler            *ScrapeSeriesHandler
	mockGateway        *motorsportstats.GatewayInMemory
	mockSaveSeriesRepo *mockSaveSeriesRepository
}

func (suite *HandlerUnitTestSuite) SetupSuite() {
	suite.mockGateway = motorsportstats.NewGatewayInMemory()
	suite.mockSaveSeriesRepo = &mockSaveSeriesRepository{}

	suite.handler = NewScrapeSeriesHandler(
		suite.mockGateway,
		suite.mockSaveSeriesRepo,
	)
}

func (suite *HandlerUnitTestSuite) TestHandle() {
	suite.T().Run("it stores series from the gateway", func(t *testing.T) {
		suite.withSeriesInGateway()

		err := suite.handler.Handle(context.Background(), suite.message())
		suite.Require().NoError(err)
		suite.Equal(2, suite.mockSaveSeriesRepo.seriesCount)
	})
}

func TestUnit_Handler(t *testing.T) {
	suite.Run(t, new(HandlerUnitTestSuite))
}

func (suite *HandlerUnitTestSuite) message() messaging.Message {
	return messaging.Message{
		Metadata: map[string]string{},
	}
}

func (suite *HandlerUnitTestSuite) withSeriesInGateway() {
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
