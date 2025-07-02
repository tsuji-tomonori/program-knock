package src

import (
	"math"
	"math/rand"
	"time"
)

// Point represents a 2D point with x and y coordinates
type Point struct {
	X, Y float64
}

// KMeansClustering performs K-means clustering on 2D points.
//
// Parameters:
//   - points: slice of 2D points
//   - k: number of clusters
//   - maxIter: maximum number of iterations
//
// Returns:
//   - slice of cluster indices for each point
func KMeansClustering(points []Point, k int, maxIter int) []int {
	if len(points) == 0 {
		return []int{}
	}

	if k == 1 {
		result := make([]int, len(points))
		return result // all zeros
	}

	if k >= len(points) {
		result := make([]int, len(points))
		for i := range result {
			result[i] = i
		}
		return result
	}

	// Initialize centroids randomly from the points
	centroids := make([]Point, k)
	indices := rand.Perm(len(points))
	for i := 0; i < k; i++ {
		centroids[i] = points[indices[i]]
	}

	var clusters []int

	for iter := 0; iter < maxIter; iter++ {
		// Assign each point to the nearest centroid
		clusters = make([]int, len(points))
		for i, point := range points {
			minDistance := math.Inf(1)
			closestCluster := 0

			for j, centroid := range centroids {
				distance := math.Sqrt(math.Pow(point.X-centroid.X, 2) + math.Pow(point.Y-centroid.Y, 2))
				if distance < minDistance {
					minDistance = distance
					closestCluster = j
				}
			}
			clusters[i] = closestCluster
		}

		// Calculate new centroids
		newCentroids := make([]Point, k)
		for clusterID := 0; clusterID < k; clusterID++ {
			var clusterPoints []Point
			for i, c := range clusters {
				if c == clusterID {
					clusterPoints = append(clusterPoints, points[i])
				}
			}

			if len(clusterPoints) > 0 {
				var sumX, sumY float64
				for _, p := range clusterPoints {
					sumX += p.X
					sumY += p.Y
				}
				newCentroids[clusterID] = Point{
					X: sumX / float64(len(clusterPoints)),
					Y: sumY / float64(len(clusterPoints)),
				}
			} else {
				// If no points assigned to this cluster, keep the old centroid
				newCentroids[clusterID] = centroids[clusterID]
			}
		}

		// Check for convergence
		converged := true
		for i := 0; i < k; i++ {
			if centroids[i].X != newCentroids[i].X || centroids[i].Y != newCentroids[i].Y {
				converged = false
				break
			}
		}

		if converged {
			break
		}

		centroids = newCentroids
	}

	return clusters
}

// SetRandomSeed sets the random seed for reproducible results
func SetRandomSeed(seed int64) {
	rand.Seed(seed)
}

func init() {
	// Initialize random seed with current time
	rand.Seed(time.Now().UnixNano())
}
