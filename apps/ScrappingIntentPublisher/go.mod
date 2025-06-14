module github.com/kishlin/MotorsportTracker/apps/ScrappingIntentPublisher

go 1.24.4

require github.com/kishlin/MotorsportTracker/src/Golang v0.0.0

require (
	github.com/aws/aws-sdk-go v1.55.7 // indirect
	github.com/google/uuid v1.6.0 // indirect
	github.com/jmespath/go-jmespath v0.4.0 // indirect
)

replace github.com/kishlin/MotorsportTracker/src/Golang => ../../src/Golang
