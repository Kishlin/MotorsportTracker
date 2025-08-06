package series

import (
	"testing"
)

func TestScrapSeriesIntent_ToMessage(t *testing.T) {
	intent := NewScrapSeriesIntent()

	message := intent.ToMessage()

	if message.Type != ScrapeSeriesMessageType {
		t.Errorf("Expected message type '%s', got '%s'", ScrapeSeriesMessageType, message.Type)
	}
}
