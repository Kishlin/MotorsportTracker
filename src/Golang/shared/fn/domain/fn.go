package domain

func Must(err error) {
	if err != nil {
		panic("Unwanted error: " + err.Error())
	}
}

func MustReturn(value interface{}, err error) interface{} {
	if err != nil {
		panic("Unwanted error: " + err.Error())
	}

	return value
}

func Ptr[T any](value T) *T {
	return &value
}

func Deref[T any](ptr *T, defaultVal T) T {
	if ptr == nil {
		return defaultVal
	}

	return *ptr
}
