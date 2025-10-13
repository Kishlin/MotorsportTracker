package domain

import (
	motorsportstats "github.com/kishlin/MotorsportTracker/src/Golang/motorsportstats/gateway/domain"
)

type Series motorsportstats.Series

func (s *Series) IsEqualTo(other *Series) bool {
	shortNamesAreEqual := (s.ShortName == nil && other.ShortName == nil) || // both nil
		(s.ShortName != nil && other.ShortName != nil && *s.ShortName == *other.ShortName) // both non-nil and values are equal

	return shortNamesAreEqual &&
		s.Name == other.Name &&
		s.UUID == other.UUID &&
		s.ShortCode == other.ShortCode &&
		s.Category == other.Category
}
