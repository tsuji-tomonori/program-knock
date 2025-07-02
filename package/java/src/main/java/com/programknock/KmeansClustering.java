package com.programknock;

import java.util.*;

public class KmeansClustering {

    public static class Point {
        public final double x;
        public final double y;

        public Point(double x, double y) {
            this.x = x;
            this.y = y;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Point point = (Point) obj;
            return Double.compare(point.x, x) == 0 && Double.compare(point.y, y) == 0;
        }

        @Override
        public int hashCode() {
            return Objects.hash(x, y);
        }

        @Override
        public String toString() {
            return "Point{x=" + x + ", y=" + y + "}";
        }
    }

    public static List<Integer> kmeansClustering(List<Point> points, int k) {
        return kmeansClustering(points, k, 100);
    }

    public static List<Integer> kmeansClustering(List<Point> points, int k, int maxIter) {
        if (points.isEmpty()) {
            return new ArrayList<>();
        }

        if (k == 1) {
            List<Integer> result = new ArrayList<>();
            for (int i = 0; i < points.size(); i++) {
                result.add(0);
            }
            return result;
        }

        if (k >= points.size()) {
            List<Integer> result = new ArrayList<>();
            for (int i = 0; i < points.size(); i++) {
                result.add(i);
            }
            return result;
        }

        // Initialize centroids randomly from the points
        List<Point> centroids = new ArrayList<>();
        Random random = new Random(42); // Use fixed seed for reproducible results
        List<Point> shuffledPoints = new ArrayList<>(points);
        Collections.shuffle(shuffledPoints, random);
        for (int i = 0; i < k; i++) {
            centroids.add(shuffledPoints.get(i));
        }

        List<Integer> clusters = new ArrayList<>();

        for (int iter = 0; iter < maxIter; iter++) {
            // Assign each point to the nearest centroid
            clusters.clear();
            for (Point point : points) {
                double minDistance = Double.MAX_VALUE;
                int closestCluster = 0;

                for (int i = 0; i < centroids.size(); i++) {
                    Point centroid = centroids.get(i);
                    double distance = Math.sqrt(
                        Math.pow(point.x - centroid.x, 2) + Math.pow(point.y - centroid.y, 2)
                    );
                    if (distance < minDistance) {
                        minDistance = distance;
                        closestCluster = i;
                    }
                }
                clusters.add(closestCluster);
            }

            // Calculate new centroids
            List<Point> newCentroids = new ArrayList<>();
            for (int clusterId = 0; clusterId < k; clusterId++) {
                List<Point> clusterPoints = new ArrayList<>();
                for (int i = 0; i < points.size(); i++) {
                    if (clusters.get(i) == clusterId) {
                        clusterPoints.add(points.get(i));
                    }
                }

                if (!clusterPoints.isEmpty()) {
                    double avgX = clusterPoints.stream().mapToDouble(p -> p.x).average().orElse(0.0);
                    double avgY = clusterPoints.stream().mapToDouble(p -> p.y).average().orElse(0.0);
                    newCentroids.add(new Point(avgX, avgY));
                } else {
                    // If no points assigned to this cluster, keep the old centroid
                    newCentroids.add(centroids.get(clusterId));
                }
            }

            // Check for convergence
            if (centroids.equals(newCentroids)) {
                break;
            }

            centroids = newCentroids;
        }

        return clusters;
    }
}
