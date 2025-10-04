package _func

func Must(err error) {
	if err != nil {
		panic("Unwanted error: " + err.Error())
	}
}
