package infrastructure

import (
	"fmt"
	"strconv"
)

// RequireString extracts a non-empty string from message metadata.
func RequireString(msg Message, key string) (string, error) {
	value, ok := msg.Metadata[key]
	if !ok || value == "" {
		return "", fmt.Errorf("metadata key %q is required", key)
	}

	return value, nil
}

// RequireInt extracts an integer from message metadata.
func RequireInt(msg Message, key string) (int, error) {
	value, err := RequireString(msg, key)
	if err != nil {
		return 0, err
	}

	parsed, err := strconv.Atoi(value)
	if err != nil {
		return 0, fmt.Errorf("metadata key %q: invalid integer format", key)
	}

	return parsed, nil
}
