package domain

import (
	"context"
)

type Series struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	ShortName *string `json:"shortName"`
	ShortCode *string `json:"shortCode"`
	Category  *string `json:"category"`
}

type Season struct {
	UUID    string  `json:"uuid"`
	Name    *string `json:"name"`
	Year    *int    `json:"year"`
	EndYear *int    `json:"endYear"`
	Status  *string `json:"status"`
}

type Venue struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	ShortName *string `json:"shortName"`
	ShortCode *string `json:"shortCode"`
}

type Country struct {
	UUID string  `json:"uuid"`
	Name *string `json:"name"`
	Flag *string `json:"picture"`
}

type Session struct {
	UUID       string  `json:"uuid"`
	Name       *string `json:"name"`
	ShortName  *string `json:"shortName"`
	ShortCode  *string `json:"shortCode"`
	Status     *string `json:"status"`
	HasResults *bool   `json:"hasResults"`
	StartTime  *int64  `json:"startTimeUtc"`
	EndTime    *int64  `json:"endTimeUtc"`
}

type Event struct {
	UUID      string     `json:"uuid"`
	Name      *string    `json:"name"`
	ShortName *string    `json:"shortName"`
	ShortCode *string    `json:"shortCode"`
	Status    *string    `json:"status"`
	StartTime *int64     `json:"startTimeUtc"`
	EndTime   *int64     `json:"endTimeUtc"`
	Venue     *Venue     `json:"venue"`
	Country   *Country   `json:"country"`
	Sessions  []*Session `json:"sessions"`
}

type Calendar struct {
	Events []*Event `json:"events"`
}

// SeriesClass is a class within a series, such as LMP2. Classes race together, so no calendar or
// classification names them: standings are the only place they appear.
type SeriesClass struct {
	UUID string  `json:"uuid"`
	Name *string `json:"name"`
}

// AvailableStandings lists each kind's standings for a season. A nil entry is the overall
// standing, and only ever comes first; every other entry is a class standing.
type AvailableStandings struct {
	Drivers      []*SeriesClass `json:"driverStandings"`
	Teams        []*SeriesClass `json:"teamStandings"`
	Constructors []*SeriesClass `json:"constructorStandings"`
}

type Driver struct {
	UUID      string  `json:"uuid"`
	Name      *string `json:"name"`
	FirstName *string `json:"firstName"`
	LastName  *string `json:"lastName"`
	ShortCode *string `json:"shortCode"`
	Colour    *string `json:"colour"`
	Picture   *string `json:"picture"`
}

type Team struct {
	UUID    string  `json:"uuid"`
	Name    *string `json:"name"`
	Colour  *string `json:"colour"`
	Picture *string `json:"picture"`
	CarIcon *string `json:"carIcon"`
}

type Retirement struct {
	Driver    *Driver `json:"driver"`
	CarNumber string  `json:"carNumber"`
	Reason    *string `json:"reason"`
	Type      *string `json:"type"`
	DNS       *bool   `json:"dns"`
	Lap       *int    `json:"lap"`
	Details   *string `json:"details"`
}

type ClassificationGap struct {
	TimeToLead *float64 `json:"timeToLead"`
	TimeToNext *float64 `json:"timeToNext"`
	LapsToLead *int     `json:"lapsToLead"`
	LapsToNext *int     `json:"lapsToNext"`
}

type ClassificationBest struct {
	Lap     *int     `json:"lap"`
	Time    *float64 `json:"time"`
	Fastest *bool    `json:"fastest"`
	Speed   *float64 `json:"speed"`
}

type ClassificationDetail struct {
	CarNumber          string             `json:"carNumber"`
	FinishPosition     *int               `json:"finishPosition"`
	GridPosition       *int               `json:"gridPosition"`
	Drivers            []*Driver          `json:"drivers"`
	Team               *Team              `json:"team"`
	Nationality        *Country           `json:"nationality"`
	Laps               *int               `json:"laps"`
	Points             *float64           `json:"points"`
	Time               *float64           `json:"time"`
	ClassifiedStatus   *string            `json:"classifiedStatus"`
	AvgLapSpeed        *float64           `json:"avgLapSpeed"`
	FastestLapTime     *float64           `json:"fastestLapTime"`
	ClassificationGap  ClassificationGap  `json:"gap"`
	ClassificationBest ClassificationBest `json:"best"`
}

type Classification struct {
	Details     []*ClassificationDetail `json:"details"`
	Retirements []*Retirement           `json:"retirements"`
}

// Constructor is a chassis and engine pairing, such as McLaren-Mercedes. Only constructors
// standings name them.
type Constructor struct {
	UUID    string  `json:"uuid"`
	Name    *string `json:"name"`
	Picture *string `json:"picture"`
}

type DriverAnalytics struct {
	AvgFinishPosition    *float64 `json:"avgFinishPosition"`
	ClassWins            *int     `json:"classWins"`
	FastestLaps          *int     `json:"fastestLaps"`
	FinalAppearances     *int     `json:"finalAppearances"`
	HatTricks            *int     `json:"hatTricks"`
	Podiums              *int     `json:"podiums"`
	Poles                *int     `json:"poles"`
	RacesLed             *int     `json:"racesLed"`
	RalliesLed           *int     `json:"ralliesLed"`
	Retirements          *int     `json:"retirements"`
	SemiFinalAppearances *int     `json:"semiFinalAppearances"`
	StageWins            *int     `json:"stageWins"`
	Starts               *int     `json:"starts"`
	Top10s               *int     `json:"top10s"`
	Top5s                *int     `json:"top5s"`
	Wins                 *int     `json:"wins"`
	WinsPercentage       *float64 `json:"winsPercentage"`
}

type TeamAnalytics struct {
	ClassWins            *int     `json:"classWins"`
	FastestLaps          *int     `json:"fastestLaps"`
	FinalAppearances     *int     `json:"finalAppearances"`
	Finishes1And2        *int     `json:"finishes1And2"`
	Podiums              *int     `json:"podiums"`
	Poles                *int     `json:"poles"`
	Qualifies1And2       *int     `json:"qualifies1And2"`
	RacesLed             *int     `json:"racesLed"`
	RalliesLed           *int     `json:"ralliesLed"`
	Retirements          *int     `json:"retirements"`
	SemiFinalAppearances *int     `json:"semiFinalAppearances"`
	StageWins            *int     `json:"stageWins"`
	Starts               *int     `json:"starts"`
	Top10s               *int     `json:"top10s"`
	Top5s                *int     `json:"top5s"`
	Wins                 *int     `json:"wins"`
	WinsPercentage       *float64 `json:"winsPercentage"`
}

type ConstructorAnalytics struct {
	Wins *int `json:"wins"`
}

// Position 0 is an unranked entry: upstream sends those last, after the ranked ones. Ties share a
// position. The order of the standings slices is the order to show them in, and the only one that
// survives both.

type DriverStanding struct {
	Driver      *Driver          `json:"driver"`
	Team        *Team            `json:"team"`
	Nationality *Country         `json:"nationality"`
	Position    *int             `json:"position"`
	Points      *float64         `json:"points"`
	Analytics   *DriverAnalytics `json:"analytics"`
}

type DriverStandings struct {
	Standings []*DriverStanding `json:"standings"`
}

// TeamStanding is one row of a teams standing. In endurance series a row is a car, not a team: a
// team running two cars appears twice, and no car number tells the rows apart.
type TeamStanding struct {
	Team                *Team          `json:"team"`
	CountryRepresenting *Country       `json:"countryRepresenting"`
	Icon                *string        `json:"icon"`
	Position            *int           `json:"position"`
	Points              *float64       `json:"points"`
	Analytics           *TeamAnalytics `json:"analytics"`
}

type TeamStandings struct {
	Standings []*TeamStanding `json:"standings"`
}

type ConstructorStanding struct {
	Constructor         *Constructor          `json:"constructor"`
	Team                *Team                 `json:"team"`
	CountryRepresenting *Country              `json:"countryRepresenting"`
	Position            *int                  `json:"position"`
	Points              *float64              `json:"points"`
	Analytics           *ConstructorAnalytics `json:"analytics"`
}

type ConstructorStandings struct {
	Standings []*ConstructorStanding `json:"standings"`
}

type Gateway interface {
	GetSeries(ctx context.Context) ([]*Series, error)

	GetSeasons(ctx context.Context, seriesUUID string) ([]*Season, error)

	GetCalendar(ctx context.Context, seasonUUID string) (*Calendar, error)

	GetAvailableStandings(ctx context.Context, seasonUUID string) (*AvailableStandings, error)

	// The standings getters take an empty classUUID for the overall standing, the one
	// AvailableStandings lists as a nil entry.

	GetDriverStandings(ctx context.Context, seasonUUID string, classUUID string) (*DriverStandings, error)

	GetTeamStandings(ctx context.Context, seasonUUID string, classUUID string) (*TeamStandings, error)

	GetConstructorStandings(ctx context.Context, seasonUUID string, classUUID string) (*ConstructorStandings, error)

	GetClassification(ctx context.Context, sessionUUID string) (*Classification, error)
}
