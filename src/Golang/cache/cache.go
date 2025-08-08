package cache

type Cache interface {
	Get(namespace, key string) (value []byte, err error)

	Set(namespace, key string, value []byte) error
}
