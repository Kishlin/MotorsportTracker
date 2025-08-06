package messaging

import (
	"testing"
)

func TestMemoryQueue_SendAndReceive(t *testing.T) {
	q := NewMemoryQueue()
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "foo"}}

	err := q.Send(msg)
	if err != nil {
		t.Fatalf("Send failed: %v", err)
	}

	received, err := q.Receive(1)
	if err != nil {
		t.Fatalf("Receive failed: %v", err)
	}
	if len(received) != 1 {
		t.Errorf("Expected 1 message, got %d", len(received))
	}
	for _, m := range received {
		if m.Type != msg.Type || m.Metadata["payload"] != msg.Metadata["payload"] {
			t.Errorf("Expected message %v, got %v", msg, m)
		}
	}
}

func TestMemoryQueue_ReceiveLimit(t *testing.T) {
	q := NewMemoryQueue()
	for i := 0; i < 5; i++ {
		msg := Message{Type: "test", Metadata: map[string]string{"payload": string(rune('a' + i))}}
		_ = q.Send(msg)
	}

	received, err := q.Receive(3)
	if err != nil {
		t.Fatalf("Receive failed: %v", err)
	}
	if len(received) > 3 {
		t.Errorf("Expected at most 3 messages, got %d", len(received))
	}
}

func TestMemoryQueue_ReceiveEmpty(t *testing.T) {
	q := NewMemoryQueue()
	received, err := q.Receive(1)
	if err != nil {
		t.Fatalf("Receive failed: %v", err)
	}
	if len(received) != 0 {
		t.Errorf("Expected 0 messages, got %d", len(received))
	}
}

func TestMemoryQueue_HiddenMessages(t *testing.T) {
	q := NewMemoryQueue()
	msg := Message{Type: "test", Metadata: map[string]string{"payload": "bar"}}
	_ = q.Send(msg)

	received, _ := q.Receive(1)
	if len(received) != 1 {
		t.Fatalf("Expected 1 message, got %d", len(received))
	}
	// Try to receive again, should get 0 since message is hidden
	received2, _ := q.Receive(1)
	if len(received2) != 0 {
		t.Errorf("Expected 0 messages after hiding, got %d", len(received2))
	}
}
