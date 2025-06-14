package intent

// Command represents the type of scraping intent
type Command string

const (
	// ScrapSeries represents the intent to scrap all available series
	ScrapSeries Command = "scrap_series"

	// ScrapSeasons represents the intent to scrap all seasons for a series
	ScrapSeasons Command = "scrap_seasons"

	// ScrapEvents represents the intent to scrap all events for a season
	ScrapEvents Command = "scrap_events"
)

// Intent represents a scrapping operation intent
type Intent struct {
	Type   Command // The type of intent
	Series string  // Series identifier (e.g., "f1", "f2", "wec")
	Season string  // Season identifier (e.g., "2025")
}

// CreateSeriesIntent creates an intent to scrap all available series
func CreateSeriesIntent() Intent {
	return Intent{
		Type: ScrapSeries,
	}
}

// CreateSeasonsIntent creates an intent to scrap all seasons for a specific series
func CreateSeasonsIntent(series string) Intent {
	return Intent{
		Type:   ScrapSeasons,
		Series: series,
	}
}

// CreateEventsIntent creates an intent to scrap all events for a specific season
func CreateEventsIntent(series, season string) Intent {
	return Intent{
		Type:   ScrapEvents,
		Series: series,
		Season: season,
	}
}
