package messaging

type Factory interface {
	NewSQSQueue(config SQSConfig) (Queue, error)
}

type SQSQueueFactory struct{}

func NewSQSQueueFactory() *SQSQueueFactory {
	return &SQSQueueFactory{}
}

func (f *SQSQueueFactory) NewSQSQueue(config SQSConfig) (Queue, error) {
	return NewSQSQueueWithConfig(config), nil
}
