package scrapping

import (
	"bytes"
	"context"
	"errors"

	"github.com/qri-io/jsonschema"
)

// Validate checks if the content conforms to the expected schema.
func Validate(ctx context.Context, content []byte, expectedSchema string) error {
	rs := jsonschema.Schema{}
	if err := rs.UnmarshalJSON([]byte(expectedSchema)); err != nil {
		return errors.New("unmarshalling expectedSchema: " + err.Error())
	}

	errs, err := rs.ValidateBytes(ctx, content)
	if err != nil {
		return errors.New("validating content: " + err.Error())
	}

	if len(errs) > 0 {
		var buf bytes.Buffer
		for _, e := range errs {
			buf.WriteString(e.Error() + "\n")
		}

		return errors.New("validation errors: " + buf.String())
	}

	return nil
}
