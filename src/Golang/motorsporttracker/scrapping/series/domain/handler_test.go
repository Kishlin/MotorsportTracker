package domain

import (
	"context"
	"testing"

	messaging "github.com/kishlin/MotorsportTracker/src/Golang/shared/messaging/domain"
	"github.com/stretchr/testify/suite"

	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
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
			Name:      "Series 1",
			ShortCode: "S1",
			Category:  "Category 1",
		},
		{
			UUID:      "series-2",
			Name:      "Series 2",
			ShortCode: "S2",
			Category:  "Category 2",
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
