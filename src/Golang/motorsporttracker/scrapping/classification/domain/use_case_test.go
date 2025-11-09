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

	useCase                    *ScrapeClassificationUseCase
	mockGateway                *motorsportstats.GatewayInMemory
	mockSessionIDRepo          *mockSessionIDRepository
	mockSaveClassificationRepo *mockSaveClassificationRepository
}

func (s *UseCaseUnitTestSuite) SetupSuite() {
	s.mockGateway = motorsportstats.NewGatewayInMemory()
	s.mockSessionIDRepo = &mockSessionIDRepository{}
	s.mockSaveClassificationRepo = &mockSaveClassificationRepository{}

	s.useCase = NewScrapeClassificationUseCase(
		s.mockGateway,
		s.mockSessionIDRepo,
		s.mockSaveClassificationRepo,
	)
}

func (s *UseCaseUnitTestSuite) TestExecute() {
	s.T().Run("no-op when session identifier is not found", func(t *testing.T) {
		s.withNoMatchingSession()

		err := s.useCase.Execute(context.Background(), "series", 2025, "british", "race")
		s.NoError(err)
		s.Equal(0, s.mockSaveClassificationRepo.savedClassifications)
	})

	s.T().Run("it stores classification when session identifier found", func(t *testing.T) {
		s.withAMatchingSession()
		s.withClassificationInGateway()

		err := s.useCase.Execute(context.Background(), "series", 2025, "british", "race")
		s.NoError(err)
		s.Equal("session", s.mockSaveClassificationRepo.sentSession)
		s.Equal(2, s.mockSaveClassificationRepo.savedClassifications)
	})
}

func TestUnit_UseCase(t *testing.T) {
	suite.Run(t, new(UseCaseUnitTestSuite))
}

func (s *UseCaseUnitTestSuite) withNoMatchingSession() {
	s.mockSessionIDRepo.hit = false
}

func (s *UseCaseUnitTestSuite) withAMatchingSession() {
	s.mockSessionIDRepo.identifier = "session"
	s.mockSessionIDRepo.hit = true
}

func (s *UseCaseUnitTestSuite) withClassificationInGateway() {
	s.mockGateway.SetClassification(
		&motorsportstats.Classification{
			Details: []*motorsportstats.ClassificationDetail{
				{
					CarNumber:      "33",
					FinishPosition: fn.Ptr(1),
					GridPosition:   fn.Ptr(2),
					Drivers: []*motorsportstats.Driver{
						{
							UUID: "driver-1",
							Name: fn.Ptr("Max Verstappen"),
						},
					},
					Team: &motorsportstats.Team{
						UUID: "team-1",
						Name: fn.Ptr("Red Bull"),
					},
					Nationality: &motorsportstats.Country{
						UUID: "nationality-1",
					},
					Laps:              fn.Ptr(10),
					Points:            fn.Ptr(25.0),
					Time:              fn.Ptr(180.0),
					ClassifiedStatus:  fn.Ptr("CLA"),
					AvgLapSpeed:       fn.Ptr(184.0),
					FastestLapTime:    fn.Ptr(18.0),
					ClassificationGap: motorsportstats.ClassificationGap{},
					ClassificationBest: motorsportstats.ClassificationBest{
						Lap:     fn.Ptr(5),
						Time:    fn.Ptr(18.0),
						Fastest: fn.Ptr(true),
					},
				},
				{
					CarNumber:      "16",
					FinishPosition: fn.Ptr(2),
					GridPosition:   fn.Ptr(1),
					Drivers: []*motorsportstats.Driver{
						{
							UUID: "driver-2",
							Name: fn.Ptr("Charles Leclerc"),
						},
					},
					Team: &motorsportstats.Team{
						UUID: "team-2",
						Name: fn.Ptr("Ferrari"),
					},
					Nationality: &motorsportstats.Country{
						UUID: "nationality-2",
					},
					Laps:             fn.Ptr(8),
					Points:           fn.Ptr(18.0),
					Time:             fn.Ptr(144.0),
					ClassifiedStatus: fn.Ptr("RET"),
					AvgLapSpeed:      fn.Ptr(186.0),
					FastestLapTime:   fn.Ptr(19.5),
					ClassificationGap: motorsportstats.ClassificationGap{
						LapsToLead: fn.Ptr(3),
					},
					ClassificationBest: motorsportstats.ClassificationBest{
						Lap:     fn.Ptr(2),
						Time:    fn.Ptr(19.5),
						Fastest: fn.Ptr(false),
					},
				},
			},
			Retirements: []*motorsportstats.Retirement{
				{
					CarNumber: "16",
					Driver: &motorsportstats.Driver{
						UUID: "driver-2",
						Name: fn.Ptr("Charles Leclerc"),
					},
					Reason: fn.Ptr("driver error (huehehe)"),
					DNS:    fn.Ptr(false),
					Lap:    fn.Ptr(8),
				},
			},
		},
	)
}

type mockSaveClassificationRepository struct {
	savedClassifications int
	sentSession          string
}

func (m *mockSaveClassificationRepository) SaveClassification(_ context.Context, session string, classification *motorsportstats.Classification) error {
	m.savedClassifications = len(classification.Details)
	m.sentSession = session
	return nil
}

type mockSessionIDRepository struct {
	identifier string
	hit        bool
}

func (m *mockSessionIDRepository) GetSessionIdentifier(_ context.Context, _ string, _ int, _ string, _ string) (ref string, hit bool, err error) {
	return m.identifier, m.hit, nil
}
