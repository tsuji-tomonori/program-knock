import random


def kmeans_clustering(points: list[tuple[float, float]], k: int, max_iter: int = 100) -> list[int]:
    """
    Perform K-means clustering on 2D points.

    Args:
        points: List of 2D points (x, y)
        k: Number of clusters
        max_iter: Maximum number of iterations

    Returns:
        List of cluster indices for each point
    """
    if not points:
        return []

    if k == 1:
        return [0] * len(points)

    if k >= len(points):
        return list(range(len(points)))

    # Initialize centroids randomly from the points
    centroids = random.sample(points, k)
    clusters: list[int] = []

    for _ in range(max_iter):
        # Assign each point to the nearest centroid
        clusters = []
        for point in points:
            distances = [
                ((point[0] - centroid[0]) ** 2 + (point[1] - centroid[1]) ** 2) ** 0.5
                for centroid in centroids
            ]
            closest_cluster = distances.index(min(distances))
            clusters.append(closest_cluster)

        # Calculate new centroids
        new_centroids = []
        for cluster_id in range(k):
            cluster_points = [points[i] for i, c in enumerate(clusters) if c == cluster_id]
            if cluster_points:
                avg_x = sum(p[0] for p in cluster_points) / len(cluster_points)
                avg_y = sum(p[1] for p in cluster_points) / len(cluster_points)
                new_centroids.append((avg_x, avg_y))
            else:
                # If no points assigned to this cluster, keep the old centroid
                new_centroids.append(centroids[cluster_id])

        # Check for convergence
        if centroids == new_centroids:
            break

        centroids = new_centroids

    return clusters
