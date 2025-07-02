package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestKMeansClusteringSample1(t *testing.T) {
	// Set seed for reproducible results
	src.SetRandomSeed(42)
	points := []src.Point{
		{X: 1.0, Y: 2.0},
		{X: 1.5, Y: 1.8},
		{X: 5.0, Y: 8.0},
		{X: 8.0, Y: 8.0},
		{X: 1.0, Y: 0.6},
		{X: 9.0, Y: 11.0},
	}
	result := src.KMeansClustering(points, 2, 100)

	// Check that result has correct length and values are 0 or 1
	assert.Equal(t, 6, len(result))
	for _, clusterID := range result {
		assert.True(t, clusterID == 0 || clusterID == 1)
	}

	// Check that similar points are in the same cluster
	// Points (1.0, 2.0), (1.5, 1.8), (1.0, 0.6) should be closer to each other
	// Points (5.0, 8.0), (8.0, 8.0), (9.0, 11.0) should be closer to each other
	closeGroup1 := []int{result[0], result[1], result[4]} // indices 0, 1, 4
	closeGroup2 := []int{result[2], result[3], result[5]} // indices 2, 3, 5

	// At least one of these should be true (depending on which cluster gets which group)
	sameCluster1 := len(uniqueInts(closeGroup1)) == 1
	sameCluster2 := len(uniqueInts(closeGroup2)) == 1
	assert.True(t, sameCluster1 || sameCluster2)
}

func TestKMeansClusteringSample2(t *testing.T) {
	points := []src.Point{{X: 1.0, Y: 2.0}, {X: 2.0, Y: 3.0}, {X: 3.0, Y: 4.0}}
	result := src.KMeansClustering(points, 1, 100)
	expected := []int{0, 0, 0}
	assert.Equal(t, expected, result)
}

func TestKMeansClusteringSample3(t *testing.T) {
	src.SetRandomSeed(42)
	points := []src.Point{{X: 1.0, Y: 1.0}, {X: 2.0, Y: 2.0}, {X: 10.0, Y: 10.0}, {X: 11.0, Y: 11.0}, {X: 50.0, Y: 50.0}}
	result := src.KMeansClustering(points, 3, 100)

	// Check that result has correct length and values are 0, 1, or 2
	assert.Equal(t, 5, len(result))
	for _, clusterID := range result {
		assert.True(t, clusterID >= 0 && clusterID <= 2)
	}

	// Check that all 3 clusters are used
	assert.Equal(t, 3, len(uniqueInts(result)))
}

func TestKMeansClusteringEmptyPoints(t *testing.T) {
	result := src.KMeansClustering([]src.Point{}, 2, 100)
	assert.Equal(t, []int{}, result)
}

func TestKMeansClusteringSinglePoint(t *testing.T) {
	points := []src.Point{{X: 1.0, Y: 1.0}}
	result := src.KMeansClustering(points, 1, 100)
	assert.Equal(t, []int{0}, result)
}

func TestKMeansClusteringKEqualsNumPoints(t *testing.T) {
	points := []src.Point{{X: 1.0, Y: 1.0}, {X: 2.0, Y: 2.0}, {X: 3.0, Y: 3.0}}
	result := src.KMeansClustering(points, 3, 100)
	assert.Equal(t, 3, len(result))
	expectedSet := map[int]bool{0: true, 1: true, 2: true}
	resultSet := make(map[int]bool)
	for _, val := range result {
		resultSet[val] = true
	}
	assert.Equal(t, expectedSet, resultSet)
}

func TestKMeansClusteringKGreaterThanNumPoints(t *testing.T) {
	points := []src.Point{{X: 1.0, Y: 1.0}, {X: 2.0, Y: 2.0}}
	result := src.KMeansClustering(points, 5, 100)
	assert.Equal(t, []int{0, 1}, result)
}

func TestKMeansClusteringIdenticalPoints(t *testing.T) {
	points := []src.Point{{X: 1.0, Y: 1.0}, {X: 1.0, Y: 1.0}, {X: 1.0, Y: 1.0}, {X: 1.0, Y: 1.0}}
	result := src.KMeansClustering(points, 2, 100)
	assert.Equal(t, 4, len(result))
	for _, clusterID := range result {
		assert.True(t, clusterID == 0 || clusterID == 1)
	}
}

func TestKMeansClusteringLinearPoints(t *testing.T) {
	src.SetRandomSeed(42)
	points := []src.Point{{X: 0.0, Y: 0.0}, {X: 1.0, Y: 0.0}, {X: 2.0, Y: 0.0}, {X: 3.0, Y: 0.0}, {X: 4.0, Y: 0.0}}
	result := src.KMeansClustering(points, 2, 100)
	assert.Equal(t, 5, len(result))
	for _, clusterID := range result {
		assert.True(t, clusterID == 0 || clusterID == 1)
	}
}

func TestKMeansClusteringTwoClearClusters(t *testing.T) {
	src.SetRandomSeed(42)
	// Two clear groups
	points := []src.Point{
		{X: 0.0, Y: 0.0},
		{X: 0.1, Y: 0.1},
		{X: 0.2, Y: 0.0},
		{X: 10.0, Y: 10.0},
		{X: 10.1, Y: 10.1},
		{X: 10.0, Y: 10.2},
	}
	result := src.KMeansClustering(points, 2, 100)

	// Points 0,1,2 should be in one cluster, points 3,4,5 in another
	cluster012 := uniqueInts([]int{result[0], result[1], result[2]})
	cluster345 := uniqueInts([]int{result[3], result[4], result[5]})

	// Each group should be in the same cluster
	assert.Equal(t, 1, len(cluster012))
	assert.Equal(t, 1, len(cluster345))
	// And the two groups should be in different clusters
	assert.NotEqual(t, cluster012[0], cluster345[0])
}

func TestKMeansClusteringMaxIterParameter(t *testing.T) {
	src.SetRandomSeed(42)
	points := []src.Point{{X: 1.0, Y: 1.0}, {X: 2.0, Y: 2.0}, {X: 10.0, Y: 10.0}, {X: 11.0, Y: 11.0}}
	result1 := src.KMeansClustering(points, 2, 1)

	src.SetRandomSeed(42)
	result2 := src.KMeansClustering(points, 2, 100)

	// Both should return valid results
	assert.Equal(t, 4, len(result1))
	assert.Equal(t, 4, len(result2))
	for _, clusterID := range result1 {
		assert.True(t, clusterID == 0 || clusterID == 1)
	}
	for _, clusterID := range result2 {
		assert.True(t, clusterID == 0 || clusterID == 1)
	}
}

// Helper function to get unique integers from a slice
func uniqueInts(slice []int) []int {
	keys := make(map[int]bool)
	var result []int
	for _, val := range slice {
		if !keys[val] {
			keys[val] = true
			result = append(result, val)
		}
	}
	return result
}
