package scrapping

import (
	"bytes"
	"context"
	"fmt"

	"github.com/qri-io/jsonschema"
)

// Validate checks if the content conforms to the expected schema.
func Validate(ctx context.Context, content []byte, expectedSchema string) error {
	rs := jsonschema.Schema{}
	if err := rs.UnmarshalJSON([]byte(expectedSchema)); err != nil {
		return fmt.Errorf("unmarshalling expectedSchema: %w", err)
	}

	errs, err := rs.ValidateBytes(ctx, content)
	if err != nil {
		return fmt.Errorf("validating content: %w", err)
	}

	if len(errs) > 0 {
		var buf bytes.Buffer
		for _, e := range errs {
			buf.WriteString(e.Error() + "\n")
		}

		return fmt.Errorf("validation errors: %s", buf.String())
	}

	return nil
}
