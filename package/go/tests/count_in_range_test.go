package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCountInRange(t *testing.T) {
	t.Run("BasicCase", func(t *testing.T) {
		arr := []int{1, 3, 5, 7, 9, 11}
		left, right := 4, 8
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 2, result) // 5, 7
	})

	t.Run("NegativeNumbers", func(t *testing.T) {
		arr := []int{-5, -3, -1, 2, 4, 6, 8, 10}
		left, right := -2, 5
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 3, result) // -1, 2, 4
	})

	t.Run("OutOfRange", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 6, 10
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 0, result) // No elements in range
	})

	t.Run("PartialRange", func(t *testing.T) {
		arr := []int{10, 20, 30, 40, 50}
		left, right := 15, 45
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 3, result) // 20, 30, 40
	})

	t.Run("ExactBoundaries", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 2, 4
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 3, result) // 2, 3, 4
	})

	t.Run("SingleElementMatch", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 3, 3
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 1, result) // 3
	})

	t.Run("SingleElementNoMatch", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 6, 6
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 0, result) // None
	})

	t.Run("AllElements", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 0, 10
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 5, result) // All elements
	})

	t.Run("EmptyRangeBefore", func(t *testing.T) {
		arr := []int{5, 6, 7, 8, 9}
		left, right := 1, 3
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 0, result) // None
	})

	t.Run("EmptyRangeAfter", func(t *testing.T) {
		arr := []int{1, 2, 3, 4, 5}
		left, right := 10, 15
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 0, result) // None
	})

	t.Run("Duplicates", func(t *testing.T) {
		arr := []int{1, 2, 2, 2, 3, 4, 4, 5}
		left, right := 2, 4
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 6, result) // 2,2,2,3,4,4
	})

	t.Run("NegativeRange", func(t *testing.T) {
		arr := []int{-10, -5, -3, -1, 0, 2, 5}
		left, right := -6, -2
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 2, result) // -5, -3
	})

	t.Run("SingleElementArray", func(t *testing.T) {
		arr := []int{42}
		assert.Equal(t, 1, src.CountInRange(arr, 40, 45))
		assert.Equal(t, 0, src.CountInRange(arr, 50, 60))
		assert.Equal(t, 1, src.CountInRange(arr, 42, 42))
	})

	t.Run("LargeRange", func(t *testing.T) {
		var arr []int
		for i := 0; i < 100; i += 2 {
			arr = append(arr, i) // [0, 2, 4, 6, ..., 98]
		}
		left, right := 10, 20
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 6, result) // 10,12,14,16,18,20
	})

	t.Run("BoundaryEdgeCases", func(t *testing.T) {
		arr := []int{1, 3, 5, 7, 9}
		// Range exactly matches first element
		assert.Equal(t, 1, src.CountInRange(arr, 1, 1))
		// Range exactly matches last element
		assert.Equal(t, 1, src.CountInRange(arr, 9, 9))
		// Range from first to last
		assert.Equal(t, 5, src.CountInRange(arr, 1, 9))
	})

	t.Run("EmptyArray", func(t *testing.T) {
		arr := []int{}
		result := src.CountInRange(arr, 1, 10)
		assert.Equal(t, 0, result)
	})

	t.Run("VeryLargeNumbers", func(t *testing.T) {
		arr := []int{-1000000, -500000, 0, 500000, 1000000}
		left, right := -600000, 600000
		result := src.CountInRange(arr, left, right)
		assert.Equal(t, 3, result) // -500000, 0, 500000
	})
}
