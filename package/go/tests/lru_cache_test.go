package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestLRUCache(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		// 1. put(1, 1) - Store key 1 with value 1
		cache.Put(1, 1)

		// 2. put(2, 2) - Store key 2 with value 2
		cache.Put(2, 2)

		// 3. get(1) → Output: 1
		assert.Equal(t, 1, cache.Get(1))

		// 4. put(3, 3) - Store key 3 with value 3 (key 2 gets evicted)
		cache.Put(3, 3)

		// 5. get(2) → Output: -1
		assert.Equal(t, -1, cache.Get(2))

		// 6. put(4, 4) - Store key 4 with value 4 (key 1 gets evicted)
		cache.Put(4, 4)

		// 7. get(1) → Output: -1
		assert.Equal(t, -1, cache.Get(1))

		// 8. get(3) → Output: 3
		assert.Equal(t, 3, cache.Get(3))

		// 9. get(4) → Output: 4
		assert.Equal(t, 4, cache.Get(4))
	})

	t.Run("CapacityOne", func(t *testing.T) {
		cache := src.NewLRUCache(1)

		cache.Put(1, 10)
		assert.Equal(t, 10, cache.Get(1))

		cache.Put(2, 20)
		assert.Equal(t, -1, cache.Get(1)) // evicted
		assert.Equal(t, 20, cache.Get(2))
	})

	t.Run("UpdateExistingKey", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(1, 10)
		cache.Put(2, 20)
		cache.Put(1, 100) // Update existing key

		assert.Equal(t, 100, cache.Get(1))
		assert.Equal(t, 20, cache.Get(2))
	})

	t.Run("GetNonexistentKey", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		assert.Equal(t, -1, cache.Get(1))

		cache.Put(1, 10)
		assert.Equal(t, -1, cache.Get(2))
	})

	t.Run("LRUOrderAfterGet", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(1, 10)
		cache.Put(2, 20)

		// Access key 1, making it most recently used
		cache.Get(1)

		// Add new key, should evict key 2 (not key 1)
		cache.Put(3, 30)

		assert.Equal(t, 10, cache.Get(1)) // still there
		assert.Equal(t, -1, cache.Get(2)) // evicted
		assert.Equal(t, 30, cache.Get(3))
	})

	t.Run("LRUOrderAfterPutUpdate", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(1, 10)
		cache.Put(2, 20)

		// Update key 1, making it most recently used
		cache.Put(1, 100)

		// Add new key, should evict key 2 (not key 1)
		cache.Put(3, 30)

		assert.Equal(t, 100, cache.Get(1)) // still there, updated value
		assert.Equal(t, -1, cache.Get(2))  // evicted
		assert.Equal(t, 30, cache.Get(3))
	})

	t.Run("LargerCapacity", func(t *testing.T) {
		cache := src.NewLRUCache(3)

		cache.Put(1, 10)
		cache.Put(2, 20)
		cache.Put(3, 30)

		// All should be present
		assert.Equal(t, 10, cache.Get(1))
		assert.Equal(t, 20, cache.Get(2))
		assert.Equal(t, 30, cache.Get(3))

		// Add fourth item, should evict first
		cache.Put(4, 40)

		assert.Equal(t, -1, cache.Get(1)) // evicted
		assert.Equal(t, 20, cache.Get(2))
		assert.Equal(t, 30, cache.Get(3))
		assert.Equal(t, 40, cache.Get(4))
	})

	t.Run("MixedOperations", func(t *testing.T) {
		cache := src.NewLRUCache(3)

		cache.Put(1, 1)
		cache.Put(2, 2)
		cache.Put(3, 3)
		cache.Put(4, 4) // evicts key 1

		assert.Equal(t, -1, cache.Get(1))
		assert.Equal(t, 2, cache.Get(2))
		assert.Equal(t, 3, cache.Get(3))
		assert.Equal(t, 4, cache.Get(4))

		cache.Put(2, 22) // update key 2
		cache.Put(5, 5)  // evicts key 3

		assert.Equal(t, -1, cache.Get(3))
		assert.Equal(t, 22, cache.Get(2))
		assert.Equal(t, 4, cache.Get(4))
		assert.Equal(t, 5, cache.Get(5))
	})

	t.Run("RepeatedAccess", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(1, 10)
		cache.Put(2, 20)

		// Repeatedly access key 1
		for i := 0; i < 5; i++ {
			assert.Equal(t, 10, cache.Get(1))
		}

		// Add new key, should still evict key 2
		cache.Put(3, 30)

		assert.Equal(t, 10, cache.Get(1))
		assert.Equal(t, -1, cache.Get(2))
		assert.Equal(t, 30, cache.Get(3))
	})

	t.Run("ZeroOperations", func(t *testing.T) {
		cache := src.NewLRUCache(5)

		// No operations performed
		assert.Equal(t, -1, cache.Get(1))
	})

	t.Run("CapacityZero", func(t *testing.T) {
		cache := src.NewLRUCache(0)

		cache.Put(1, 10)
		assert.Equal(t, -1, cache.Get(1)) // Should not store anything
	})

	t.Run("NegativeKeys", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(-1, 10)
		cache.Put(-2, 20)

		assert.Equal(t, 10, cache.Get(-1))
		assert.Equal(t, 20, cache.Get(-2))
	})

	t.Run("ZeroKeys", func(t *testing.T) {
		cache := src.NewLRUCache(2)

		cache.Put(0, 100)
		assert.Equal(t, 100, cache.Get(0))

		cache.Put(1, 200)
		cache.Put(2, 300) // Should evict key 0

		assert.Equal(t, -1, cache.Get(0))
		assert.Equal(t, 200, cache.Get(1))
		assert.Equal(t, 300, cache.Get(2))
	})
}
