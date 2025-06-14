package intent

// Type represents the type of scraping intent
type Type string

const (
	// ScrapAllSeries represents the intent to scrap all available series
	ScrapAllSeries Type = "scrap_all_series"

	// ScrapAllSeasons represents the intent to scrap all seasons for a series
	ScrapAllSeasons Type = "scrap_all_seasons"

	// ScrapAllEvents represents the intent to scrap all events for a season
	ScrapAllEvents Type = "scrap_all_events"

	// ScrapEventSession represents the intent to scrap results for a specific event session
	ScrapEventSession Type = "scrap_event_session"
)

// Intent represents a scrapping operation intent
type Intent struct {
	Type     Type              // The type of intent
	Series   string            // Series identifier (e.g., "f1", "f2", "wec")
	Season   string            // Season identifier (e.g., "2025")
	Event    string            // Event identifier
	Session  string            // Session identifier (e.g., "qualifying", "race")
	Metadata map[string]string // Additional metadata
}

// CreateAllSeriesIntent creates an intent to scrap all available series
func CreateAllSeriesIntent() Intent {
	return Intent{
		Type:     ScrapAllSeries,
		Metadata: make(map[string]string),
	}
}

// CreateAllSeasonsIntent creates an intent to scrap all seasons for a specific series
func CreateAllSeasonsIntent(series string) Intent {
	return Intent{
		Type:     ScrapAllSeasons,
		Series:   series,
		Metadata: make(map[string]string),
	}
}

// CreateAllEventsIntent creates an intent to scrap all events for a specific season
func CreateAllEventsIntent(series, season string) Intent {
	return Intent{
		Type:     ScrapAllEvents,
		Series:   series,
		Season:   season,
		Metadata: make(map[string]string),
	}
}

// CreateEventSessionIntent creates an intent to scrap results for a specific event session
func CreateEventSessionIntent(series, season, event, session string) Intent {
	return Intent{
		Type:     ScrapEventSession,
		Series:   series,
		Season:   season,
		Event:    event,
		Session:  session,
		Metadata: make(map[string]string),
	}
}
