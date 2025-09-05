package seasons

type Season struct {
	Name    string `json:"name"`
	UUID    string `json:"uuid"` // We call it ExternalUUID because it comes from the API, in which it's called uuid
	Year    int    `json:"year"`
	EndYear int    `json:"endYear"`
	Status  string `json:"status"`
}
