package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestFindClosestValue(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		// [1, 3, 5, 7, 9] target=6, closest values are 5 and 7, return smaller one (5)
		arr := []int{1, 3, 5, 7, 9}
		target := 6
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 5, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		// [2, 4, 6, 8, 10] target=7, closest values are 6 and 8, return smaller one (6)
		arr := []int{2, 4, 6, 8, 10}
		target := 7
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 6, result)
	})

	t.Run("Sample3", func(t *testing.T) {
		// [1, 2, 3, 4, 5] target=10, closest value is 5
		arr := []int{1, 2, 3, 4, 5}
		target := 10
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 5, result)
	})

	t.Run("Sample4", func(t *testing.T) {
		// [-10, -5, 0, 5, 10] target=-7, closest value is -5
		arr := []int{-10, -5, 0, 5, 10}
		target := -7
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, -5, result)
	})

	t.Run("Sample5", func(t *testing.T) {
		// [10] target=7, only one element, return 10
		arr := []int{10}
		target := 7
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 10, result)
	})

	t.Run("BoundaryValueSmall", func(t *testing.T) {
		// Very small target
		arr := []int{-10, -5, 0, 5, 10}
		target := -999999999
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, -10, result)
	})

	t.Run("BoundaryValueLarge", func(t *testing.T) {
		// Very large target
		arr := []int{-10, -5, 0, 5, 10}
		target := 999999999
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 10, result)
	})

	t.Run("EquidistantCase", func(t *testing.T) {
		// target is equidistant from two elements, return smaller one
		arr := []int{1, 3, 5, 7, 9}
		target := 4
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 3, result)
	})

	t.Run("ExactMatch", func(t *testing.T) {
		// target matches an element exactly
		arr := []int{1, 3, 5, 7, 9}
		target := 5
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 5, result)
	})

	t.Run("SingleElement", func(t *testing.T) {
		// Array with single element
		arr := []int{100}
		target := -100
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 100, result)
	})

	t.Run("LargeDataMiddleTarget", func(t *testing.T) {
		// Large array with target in middle
		arr := make([]int, 100000)
		for i := range arr {
			arr[i] = i
		}
		target := 50000
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 50000, result)
	})

	t.Run("LargeDataLowTarget", func(t *testing.T) {
		// Large array with target below range
		arr := make([]int, 100000)
		for i := range arr {
			arr[i] = i - 50000
		}
		target := -999999
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, -50000, result)
	})

	t.Run("LargeDataHighTarget", func(t *testing.T) {
		// Large array with target above range
		arr := make([]int, 100000)
		for i := range arr {
			arr[i] = i - 50000
		}
		target := 999999
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 49999, result)
	})

	t.Run("TwoElements", func(t *testing.T) {
		// Array with two elements
		arr := []int{1, 10}
		target := 5
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 1, result) // Both are distance 4, return smaller
	})

	t.Run("NegativeNumbers", func(t *testing.T) {
		// All negative numbers
		arr := []int{-100, -50, -20, -10, -1}
		target := -25
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, -20, result)
	})

	t.Run("ZeroTarget", func(t *testing.T) {
		// Target is zero
		arr := []int{-5, -2, 1, 3, 8}
		target := 0
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 1, result)
	})

	t.Run("TargetAtStart", func(t *testing.T) {
		// Target is smaller than first element
		arr := []int{5, 10, 15, 20}
		target := 1
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 5, result)
	})

	t.Run("TargetAtEnd", func(t *testing.T) {
		// Target is larger than last element
		arr := []int{1, 5, 10, 15}
		target := 100
		result := src.FindClosestValue(arr, target)
		assert.Equal(t, 15, result)
	})
}
