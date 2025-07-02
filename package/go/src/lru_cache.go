package src

// LRUCache represents an LRU (Least Recently Used) Cache implementation
type LRUCache struct {
	capacity int
	cache    map[int]int
	order    []int // tracks the order of key usage (most recent at end)
}

// NewLRUCache creates a new LRU cache with the given capacity
func NewLRUCache(capacity int) *LRUCache {
	return &LRUCache{
		capacity: capacity,
		cache:    make(map[int]int),
		order:    make([]int, 0),
	}
}

// Get retrieves the value for the key. If key exists, marks it as recently used.
func (lru *LRUCache) Get(key int) int {
	if value, exists := lru.cache[key]; exists {
		// Move key to end (most recently used)
		lru.removeFromOrder(key)
		lru.order = append(lru.order, key)
		return value
	}
	return -1
}

// Put stores a key-value pair into cache. If capacity is exceeded, removes LRU item.
func (lru *LRUCache) Put(key, value int) {
	// Handle zero capacity case
	if lru.capacity <= 0 {
		return
	}

	if _, exists := lru.cache[key]; exists {
		// Update existing key
		lru.cache[key] = value
		// Move to end (most recently used)
		lru.removeFromOrder(key)
		lru.order = append(lru.order, key)
	} else {
		// Add new key
		if len(lru.cache) >= lru.capacity {
			// Remove least recently used (first in order)
			if len(lru.order) > 0 {
				lruKey := lru.order[0]
				lru.order = lru.order[1:]
				delete(lru.cache, lruKey)
			}
		}

		lru.cache[key] = value
		lru.order = append(lru.order, key)
	}
}

// removeFromOrder removes a key from the order slice
func (lru *LRUCache) removeFromOrder(key int) {
	for i, k := range lru.order {
		if k == key {
			lru.order = append(lru.order[:i], lru.order[i+1:]...)
			break
		}
	}
}
