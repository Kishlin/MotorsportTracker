package series

import "testing"

func TestSeries_IsEqualTo(t *testing.T) {
	reference := Series{
		Name:         "Formula 1",
		ExternalUUID: "f1-1234",
		ShortName:    "F1",
		ShortCode:    "F1",
		Category:     "Open Wheel",
	}

	t.Run("Equal Series", func(t *testing.T) {
		other := Series{
			Name:         "Formula 1",
			ExternalUUID: "f1-1234",
			ShortName:    "F1",
			ShortCode:    "F1",
			Category:     "Open Wheel",
		}

		if !reference.IsEqualTo(other) {
			t.Errorf("Expected series to be equal, but they are not")
		}
	})

	for name, other := range map[string]Series{
		"Name differs": {
			Name:         "Formula 2",
			ExternalUUID: "f1-1234",
			ShortName:    "F1",
			ShortCode:    "F1",
			Category:     "Open Wheel",
		},
		"ExternalUUID differs": {
			Name:         "Formula 1",
			ExternalUUID: "f1-5678",
			ShortName:    "F1",
			ShortCode:    "F1",
			Category:     "Open Wheel",
		},
		"ShortName differs": {
			Name:         "Formula 1",
			ExternalUUID: "f1-1234",
			ShortName:    "F2",
			ShortCode:    "F1",
			Category:     "Open Wheel",
		},
		"ShortCode differs": {
			Name:         "Formula 1",
			ExternalUUID: "f1-1234",
			ShortName:    "F1",
			ShortCode:    "F2",
			Category:     "Open Wheel",
		},
		"Category differs": {
			Name:         "Formula 1",
			ExternalUUID: "f1-1234",
			ShortName:    "F1",
			ShortCode:    "F1",
			Category:     "Closed Wheel",
		},
	} {
		t.Run(name, func(t *testing.T) {
			if reference.IsEqualTo(other) {
				t.Errorf("Expected series to be different, but they are equal")
			}
		})
	}
}
