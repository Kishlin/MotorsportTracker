package cache

type InMemoryCache struct {
	cache map[string]map[string][]byte
}

func NewInMemoryCache() *InMemoryCache {
	return &InMemoryCache{
		cache: make(map[string]map[string][]byte),
	}
}

func (c *InMemoryCache) Get(namespace, key string) (value []byte, hit bool, err error) {
	if ns, exists := c.cache[namespace]; exists {
		if val, found := ns[key]; found {
			return val, true, nil
		}
	}

	return nil, false, nil
}

func (c *InMemoryCache) Set(namespace, key string, value []byte) error {
	if _, exists := c.cache[namespace]; !exists {
		c.cache[namespace] = make(map[string][]byte)
	}

	c.cache[namespace][key] = value

	return nil
}

func (c *InMemoryCache) ItemsCount() int {
	count := 0
	for _, ns := range c.cache {
		count += len(ns)
	}
	return count
}
