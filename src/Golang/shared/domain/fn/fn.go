package fn

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
