package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestAgeStatistics(t *testing.T) {
	t.Run("Sample1", func(t *testing.T) {
		ages := []int{25, 30, 35, 40, 45, 50}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 50, MinAge: 25, AvgAge: 37.5, CountBelowAvg: 3}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2", func(t *testing.T) {
		ages := []int{18, 22, 22, 24, 29, 35, 40, 50, 60}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 60, MinAge: 18, AvgAge: 33.33, CountBelowAvg: 5}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleAge", func(t *testing.T) {
		ages := []int{30}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 30, MinAge: 30, AvgAge: 30.0, CountBelowAvg: 1}
		assert.Equal(t, expected, result)
	})

	t.Run("AllSameAge", func(t *testing.T) {
		ages := []int{25, 25, 25, 25}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 25, MinAge: 25, AvgAge: 25.0, CountBelowAvg: 4}
		assert.Equal(t, expected, result)
	})

	t.Run("TwoAges", func(t *testing.T) {
		ages := []int{20, 40}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 40, MinAge: 20, AvgAge: 30.0, CountBelowAvg: 1}
		assert.Equal(t, expected, result)
	})

	t.Run("EdgeCaseYoung", func(t *testing.T) {
		ages := []int{0, 1, 2, 3, 4}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 4, MinAge: 0, AvgAge: 2.0, CountBelowAvg: 3}
		assert.Equal(t, expected, result)
	})

	t.Run("EdgeCaseOld", func(t *testing.T) {
		ages := []int{110, 115, 120}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 120, MinAge: 110, AvgAge: 115.0, CountBelowAvg: 2}
		assert.Equal(t, expected, result)
	})

	t.Run("LargeDataset", func(t *testing.T) {
		ages := make([]int, 51)
		for i := 0; i < 51; i++ {
			ages[i] = 20 + i
		}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 70, MinAge: 20, AvgAge: 45.0, CountBelowAvg: 26}
		assert.Equal(t, expected, result)
	})

	t.Run("MixedAges", func(t *testing.T) {
		ages := []int{18, 25, 32, 45, 52, 60, 65, 70, 22, 28}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 70, MinAge: 18, AvgAge: 41.7, CountBelowAvg: 5}
		assert.Equal(t, expected, result)
	})

	t.Run("PrecisionRounding", func(t *testing.T) {
		ages := []int{33, 34, 35}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 35, MinAge: 33, AvgAge: 34.0, CountBelowAvg: 2}
		assert.Equal(t, expected, result)
	})

	t.Run("PrecisionRounding2", func(t *testing.T) {
		ages := []int{10, 20, 30, 40, 50, 60, 70}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 70, MinAge: 10, AvgAge: 40.0, CountBelowAvg: 4}
		assert.Equal(t, expected, result)
	})

	t.Run("EmptySlice", func(t *testing.T) {
		ages := []int{}
		result := src.AnalyzeAgeDistribution(ages)
		expected := src.AgeStatistics{MaxAge: 0, MinAge: 0, AvgAge: 0, CountBelowAvg: 0}
		assert.Equal(t, expected, result)
	})
}
