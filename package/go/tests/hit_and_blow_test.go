package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestHitAndBlow(t *testing.T) {
	t.Run("Case1", func(t *testing.T) {
		result := src.HitAndBlow([]int{1, 2, 3, 4}, []int{1, 3, 2, 5})
		expected := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected, result)
	})

	t.Run("Case2", func(t *testing.T) {
		result := src.HitAndBlow([]int{5, 6, 7, 8}, []int{8, 7, 6, 5})
		expected := src.HitAndBlowResult{Hits: 0, Blows: 4}
		assert.Equal(t, expected, result)
	})

	t.Run("Case3", func(t *testing.T) {
		result := src.HitAndBlow([]int{3, 1, 4, 7}, []int{3, 1, 4, 7})
		expected := src.HitAndBlowResult{Hits: 4, Blows: 0}
		assert.Equal(t, expected, result)
	})

	t.Run("Case4", func(t *testing.T) {
		result := src.HitAndBlow([]int{9, 8, 7, 6}, []int{1, 2, 3, 4})
		expected := src.HitAndBlowResult{Hits: 0, Blows: 0}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleDigit", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{5}, []int{5})
		expected1 := src.HitAndBlowResult{Hits: 1, Blows: 0}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{5}, []int{3})
		expected2 := src.HitAndBlowResult{Hits: 0, Blows: 0}
		assert.Equal(t, expected2, result2)
	})

	t.Run("TwoDigits", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2}, []int{2, 1})
		expected1 := src.HitAndBlowResult{Hits: 0, Blows: 2}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{1, 2}, []int{1, 3})
		expected2 := src.HitAndBlowResult{Hits: 1, Blows: 0}
		assert.Equal(t, expected2, result2)

		result3 := src.HitAndBlow([]int{1, 2}, []int{3, 2})
		expected3 := src.HitAndBlowResult{Hits: 1, Blows: 0}
		assert.Equal(t, expected3, result3)
	})

	t.Run("PartialMatch", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3}, []int{1, 5, 2})
		expected1 := src.HitAndBlowResult{Hits: 1, Blows: 1}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{1, 2, 3}, []int{4, 1, 5})
		expected2 := src.HitAndBlowResult{Hits: 0, Blows: 1}
		assert.Equal(t, expected2, result2)
	})

	t.Run("AllHits", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{0, 1, 2, 3}, []int{0, 1, 2, 3})
		expected1 := src.HitAndBlowResult{Hits: 4, Blows: 0}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{9, 8, 7}, []int{9, 8, 7})
		expected2 := src.HitAndBlowResult{Hits: 3, Blows: 0}
		assert.Equal(t, expected2, result2)
	})

	t.Run("AllBlows", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3}, []int{3, 1, 2})
		expected1 := src.HitAndBlowResult{Hits: 0, Blows: 3}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{1, 2}, []int{2, 1})
		expected2 := src.HitAndBlowResult{Hits: 0, Blows: 2}
		assert.Equal(t, expected2, result2)
	})

	t.Run("NoMatches", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3}, []int{4, 5, 6})
		expected1 := src.HitAndBlowResult{Hits: 0, Blows: 0}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{0, 9}, []int{1, 8})
		expected2 := src.HitAndBlowResult{Hits: 0, Blows: 0}
		assert.Equal(t, expected2, result2)
	})

	t.Run("MixedScenario", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3, 4, 5}, []int{1, 5, 6, 4, 7})
		expected1 := src.HitAndBlowResult{Hits: 2, Blows: 1}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{0, 1, 2, 3}, []int{0, 2, 1, 4})
		expected2 := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected2, result2)
	})

	t.Run("EdgeCaseZeros", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{0, 0, 0}, []int{0, 0, 0})
		expected1 := src.HitAndBlowResult{Hits: 3, Blows: 0}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{0, 1, 2}, []int{0, 2, 1})
		expected2 := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected2, result2)
	})

	t.Run("LongerSequence", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3, 4, 5, 6}, []int{1, 6, 2, 3, 4, 5})
		expected1 := src.HitAndBlowResult{Hits: 1, Blows: 5}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{0, 1, 2, 3, 4, 5}, []int{5, 4, 3, 2, 1, 0})
		expected2 := src.HitAndBlowResult{Hits: 0, Blows: 6}
		assert.Equal(t, expected2, result2)
	})

	t.Run("OneHitMultipleBlows", func(t *testing.T) {
		result1 := src.HitAndBlow([]int{1, 2, 3, 4}, []int{1, 4, 2, 5})
		expected1 := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected1, result1)

		result2 := src.HitAndBlow([]int{9, 8, 7, 6}, []int{9, 6, 8, 5})
		expected2 := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected2, result2)
	})

	t.Run("EmptyArrays", func(t *testing.T) {
		result := src.HitAndBlow([]int{}, []int{})
		expected := src.HitAndBlowResult{Hits: 0, Blows: 0}
		assert.Equal(t, expected, result)
	})

	t.Run("DuplicateNumbers", func(t *testing.T) {
		// Test with duplicate numbers in both answer and guess
		result := src.HitAndBlow([]int{1, 1, 2, 2}, []int{2, 2, 1, 1})
		expected := src.HitAndBlowResult{Hits: 0, Blows: 4}
		assert.Equal(t, expected, result)
	})

	t.Run("PartialDuplicates", func(t *testing.T) {
		// Test with partial duplicates
		result := src.HitAndBlow([]int{1, 1, 2, 3}, []int{1, 2, 1, 4})
		expected := src.HitAndBlowResult{Hits: 1, Blows: 2}
		assert.Equal(t, expected, result)
	})
}
