package cache

type InMemoryCache struct {
	cache map[string]map[string][]byte
}

func NewInMemoryCache() *InMemoryCache {
	return &InMemoryCache{
		cache: make(map[string]map[string][]byte),
	}
}

func (c *InMemoryCache) Get(namespace, key string) (value []byte, err error) {
	if ns, exists := c.cache[namespace]; exists {
		if val, found := ns[key]; found {
			return val, nil
		}
	}

	return nil, nil
}

func (c *InMemoryCache) Set(namespace, key string, value []byte) error {
	if _, exists := c.cache[namespace]; !exists {
		c.cache[namespace] = make(map[string][]byte)
	}

	c.cache[namespace][key] = value

	return nil
}
