package _func

import (
	"errors"
	"testing"
)

func TestFunc_Must(t *testing.T) {
	t.Run("it does not panic if no error", func(t *testing.T) {
		defer func() {
			if r := recover(); r != nil {
				t.Errorf("Must panic unexpectedly: %v", r)
			}
		}()
		Must(nil)
	})

	t.Run("it panics on error", func(t *testing.T) {
		defer func() {
			if r := recover(); r == nil {
				t.Errorf("Must did not panic as expected")
			}
		}()
		Must(errors.New("this should cause a panic"))
	})
}
