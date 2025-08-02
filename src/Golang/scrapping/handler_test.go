package scrapping

import (
	"context"
	"testing"
)

func TestBaseScrappingHandler_Validate(t *testing.T) {
	handler := &BaseScrappingHandler{}
	ctx := context.Background()

	t.Run("Returns No Error When Content Validates Schema", func(t *testing.T) {
		schema := `{
			"type": "object",
			"properties": {
				"name": {"type": "string"}
			},
			"required": ["name"]
		}`
		content := []byte(`{"name": "test"}`)

		err := handler.Validate(ctx, content, schema)

		if err != nil {
			t.Errorf("Expected no error when content validates schema, got: %v", err)
		}
	})

	t.Run("Returns Error When Content Fails Validation", func(t *testing.T) {
		schema := `{
			"type": "object",
			"properties": {
				"name": {"type": "string"}
			},
			"required": ["name"]
		}`
		content := []byte(`{}`) // missing required "name" field

		err := handler.Validate(ctx, content, schema)

		if err == nil {
			t.Error("Expected validation error when content doesn't match schema")
		}
	})

	t.Run("Returns Error When Multiple Validation Errors Occur", func(t *testing.T) {
		schema := `{
			"type": "object",
			"properties": {
				"name": {"type": "string"},
				"age": {"type": "number"}
			},
			"required": ["name", "age"]
		}`
		content := []byte(`{}`) // missing both required fields

		err := handler.Validate(ctx, content, schema)

		if err == nil {
			t.Error("Expected validation error when content has multiple validation failures")
		}

		// Just verify that multiple errors are somehow aggregated (should contain multiple pieces of info)
		errorMessage := err.Error()
		if len(errorMessage) < 10 {
			t.Error("Expected a substantial error message for multiple validation failures")
		}
	})

	t.Run("Returns Error For Invalid Schema", func(t *testing.T) {
		schema := `{"type": "invalid-schema"` // malformed JSON
		content := []byte(`{"name": "test"}`)

		err := handler.Validate(ctx, content, schema)

		if err == nil {
			t.Error("Expected error when schema is malformed")
		}
	})

	t.Run("Returns Error For Invalid Content", func(t *testing.T) {
		schema := `{"type": "object"}`
		content := []byte(`{"name": "test"`) // malformed JSON content

		err := handler.Validate(ctx, content, schema)

		if err == nil {
			t.Error("Expected error when content is malformed JSON")
		}
	})
}
