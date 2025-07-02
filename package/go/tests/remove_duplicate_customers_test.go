package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestRemoveDuplicateCustomers(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		customerIDs := []int{101, 202, 303, 101, 404, 202, 505}
		expected := []int{101, 202, 303, 404, 505}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		customerIDs := []int{1, 2, 3, 4, 5, 6, 7, 8, 9}
		expected := []int{1, 2, 3, 4, 5, 6, 7, 8, 9}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample3", func(t *testing.T) {
		customerIDs := []int{42, 42, 42, 42, 42}
		expected := []int{42}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample4", func(t *testing.T) {
		customerIDs := []int{}
		expected := []int{}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("Sample5", func(t *testing.T) {
		customerIDs := []int{500, -1, 500, -1, 200, 300, 200, -100}
		expected := []int{500, -1, 200, 300, -100}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("SingleElement", func(t *testing.T) {
		customerIDs := []int{0}
		expected := []int{0}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("AllUniqueElements", func(t *testing.T) {
		customerIDs := []int{10, 20, 30, 40, 50}
		expected := []int{10, 20, 30, 40, 50}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("AllDuplicateNegative", func(t *testing.T) {
		customerIDs := []int{-5, -5, -5, -5}
		expected := []int{-5}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("BoundaryValues", func(t *testing.T) {
		customerIDs := []int{-1000000, 1000000, -1000000, 1000000, 0}
		expected := []int{-1000000, 1000000, 0}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("InterleavedDuplicates", func(t *testing.T) {
		customerIDs := []int{3, 1, 2, 3, 2, 1, 4, 5, 4}
		expected := []int{3, 1, 2, 4, 5}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("LargeInput", func(t *testing.T) {
		// Create a large list with 0-9999 twice
		var customerIDs []int
		for i := 0; i < 10000; i++ {
			customerIDs = append(customerIDs, i)
		}
		for i := 0; i < 10000; i++ {
			customerIDs = append(customerIDs, i)
		}

		var expected []int
		for i := 0; i < 10000; i++ {
			expected = append(expected, i)
		}

		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("ZeroValues", func(t *testing.T) {
		customerIDs := []int{0, 0, 1, 0, 2}
		expected := []int{0, 1, 2}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("MixedPositiveNegative", func(t *testing.T) {
		customerIDs := []int{-100, 50, -100, 25, 50, -25, 25}
		expected := []int{-100, 50, 25, -25}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})

	t.Run("OrderPreservation", func(t *testing.T) {
		// Test that first occurrence order is preserved
		customerIDs := []int{5, 1, 3, 5, 2, 1, 4, 3}
		expected := []int{5, 1, 3, 2, 4}
		result := src.RemoveDuplicateCustomers(customerIDs)
		assert.Equal(t, expected, result)
	})
}
