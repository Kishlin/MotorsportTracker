package series

type Series struct {
	Name         string `json:"name"`
	ExternalUUID string `json:"uuid"` // We call it ExternalUUID because it comes from the API, in which it's called uuid
	ShortName    string `json:"shortName"`
	ShortCode    string `json:"shortCode"`
	Category     string `json:"category"`
}

func (s Series) IsEqualTo(other Series) bool {
	return s.Name == other.Name &&
		s.ExternalUUID == other.ExternalUUID &&
		s.ShortName == other.ShortName &&
		s.ShortCode == other.ShortCode &&
		s.Category == other.Category
}
