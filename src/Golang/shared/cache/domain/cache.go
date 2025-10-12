package domain

type Cache interface {
	Get(namespace, key string) (value []byte, hit bool, err error)

	Set(namespace, key string, value []byte) error
}
