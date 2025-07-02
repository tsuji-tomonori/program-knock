/**
 * Perform K-means clustering on 2D points.
 * Note: This is a simple implementation without true random initialization
 * for deterministic results in testing.
 *
 * @param points - List of 2D points [x, y]
 * @param k - Number of clusters
 * @param maxIter - Maximum number of iterations
 * @returns List of cluster indices for each point
 */
export function kmeansClusteringSimple(
  points: Array<[number, number]>,
  k: number,
  maxIter: number = 100
): number[] {
  if (!points.length) {
    return [];
  }

  if (k === 1) {
    return new Array(points.length).fill(0);
  }

  if (k >= points.length) {
    return points.map((_, i) => i);
  }

  // Initialize centroids by selecting evenly spaced points
  const centroids: Array<[number, number]> = [];
  for (let i = 0; i < k; i++) {
    const index = Math.floor((i * points.length) / k);
    centroids.push([...points[index]]);
  }

  let clusters: number[] = [];

  for (let iter = 0; iter < maxIter; iter++) {
    // Assign each point to the nearest centroid
    clusters = [];
    for (const point of points) {
      const distances = centroids.map(centroid => {
        const dx = point[0] - centroid[0];
        const dy = point[1] - centroid[1];
        return Math.sqrt(dx * dx + dy * dy);
      });
      const closestCluster = distances.indexOf(Math.min(...distances));
      clusters.push(closestCluster);
    }

    // Calculate new centroids
    const newCentroids: Array<[number, number]> = [];
    for (let clusterId = 0; clusterId < k; clusterId++) {
      const clusterPoints = points.filter((_, i) => clusters[i] === clusterId);

      if (clusterPoints.length > 0) {
        const avgX = clusterPoints.reduce((sum, p) => sum + p[0], 0) / clusterPoints.length;
        const avgY = clusterPoints.reduce((sum, p) => sum + p[1], 0) / clusterPoints.length;
        newCentroids.push([avgX, avgY]);
      } else {
        // If no points assigned to this cluster, keep the old centroid
        newCentroids.push([...centroids[clusterId]]);
      }
    }

    // Check for convergence
    const converged = centroids.every((centroid, i) =>
      Math.abs(centroid[0] - newCentroids[i][0]) < 1e-10 &&
      Math.abs(centroid[1] - newCentroids[i][1]) < 1e-10
    );

    if (converged) {
      break;
    }

    centroids.splice(0, centroids.length, ...newCentroids);
  }

  return clusters;
}
