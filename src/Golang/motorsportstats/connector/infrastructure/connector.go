package infrastructure

import "context"

// StandingKind is whose standing it is, as the standings path names it.
type StandingKind string

const (
	StandingDrivers      StandingKind = "drivers"
	StandingTeams        StandingKind = "teams"
	StandingConstructors StandingKind = "constructors"
)

type Connector interface {
	GetSeries(ctx context.Context) ([]byte, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]byte, error)

	GetCalendar(ctx context.Context, seasonUUID string) ([]byte, error)

	GetAvailableStandings(ctx context.Context, seasonUUID string) ([]byte, error)

	// GetStandings fetches one standing of a season: the overall one for an empty classUUID, else the
	// one of that class.
	GetStandings(ctx context.Context, seasonUUID string, kind StandingKind, classUUID string) ([]byte, error)

	GetClassification(ctx context.Context, sessionUUD string) ([]byte, error)
}
