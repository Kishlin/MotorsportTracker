# Golang Shared Libraries

This directory contains shared Go libraries used across multiple Go applications in the MotorsportTracker project.

## Directory Structure

- `queue/`: Queue abstraction for working with message queues (memory, SQS implementations)
- `intent/`: Models for scraping intents and operations
- `worker/`: Worker implementations for processing queue messages

## Usage

To use these libraries in your Go application, import them as:

```go
import "github.com/kishlin/MotorsportTracker/src/Golang/queue"
import "github.com/kishlin/MotorsportTracker/src/Golang/intent"
import "github.com/kishlin/MotorsportTracker/src/Golang/worker"
```
