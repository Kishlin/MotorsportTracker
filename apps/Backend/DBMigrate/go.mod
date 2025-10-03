module github.com/kishlin/MotorsportTracker/apps/Backend/DBMigrate

go 1.25.1

require github.com/golang-migrate/migrate/v4 v4.18.3

require (
	github.com/hashicorp/errwrap v1.1.0 // indirect
	github.com/hashicorp/go-multierror v1.1.1 // indirect
	github.com/joho/godotenv v1.5.1 // indirect
	github.com/lib/pq v1.10.9 // indirect
	github.com/stretchr/testify v1.10.0 // indirect
	go.uber.org/atomic v1.7.0 // indirect
	golang.org/x/sys v0.32.0 // indirect
)

replace github.com/kishlin/MotorsportTracker/src/Golang => ../../../src/Golang
