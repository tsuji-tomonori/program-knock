package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class KmeansClusteringTest {

    @Test
    void testSample1() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 2.0),
            new KmeansClustering.Point(1.5, 1.8),
            new KmeansClustering.Point(5.0, 8.0),
            new KmeansClustering.Point(8.0, 8.0),
            new KmeansClustering.Point(1.0, 0.6),
            new KmeansClustering.Point(9.0, 11.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);

        // Check that result has correct length and values are 0 or 1
        assertEquals(6, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));

        // Check that similar points are in the same cluster
        // Points (1.0, 2.0), (1.5, 1.8), (1.0, 0.6) should be closer to each other
        // Points (5.0, 8.0), (8.0, 8.0), (9.0, 11.0) should be closer to each other
        Set<Integer> closeGroup1 = new HashSet<>(Arrays.asList(result.get(0), result.get(1), result.get(4)));
        Set<Integer> closeGroup2 = new HashSet<>(Arrays.asList(result.get(2), result.get(3), result.get(5)));

        // At least one group should be in the same cluster
        boolean sameCluster1 = closeGroup1.size() == 1;
        boolean sameCluster2 = closeGroup2.size() == 1;
        assertTrue(sameCluster1 || sameCluster2);
    }

    @Test
    void testSample2() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 2.0),
            new KmeansClustering.Point(2.0, 3.0),
            new KmeansClustering.Point(3.0, 4.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 1);
        List<Integer> expected = Arrays.asList(0, 0, 0);
        assertEquals(expected, result);
    }

    @Test
    void testSample3() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(2.0, 2.0),
            new KmeansClustering.Point(10.0, 10.0),
            new KmeansClustering.Point(11.0, 11.0),
            new KmeansClustering.Point(50.0, 50.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 3);

        // Check that result has correct length and values are 0, 1, or 2
        assertEquals(5, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId >= 0 && clusterId <= 2));

        // Check that all 3 clusters are used
        assertEquals(3, new HashSet<>(result).size());
    }

    @Test
    void testEmptyPoints() {
        List<Integer> result = KmeansClustering.kmeansClustering(new ArrayList<>(), 2);
        assertEquals(new ArrayList<>(), result);
    }

    @Test
    void testSinglePoint() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 1);
        assertEquals(Arrays.asList(0), result);
    }

    @Test
    void testKEqualsNumPoints() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(2.0, 2.0),
            new KmeansClustering.Point(3.0, 3.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 3);
        assertEquals(3, result.size());
        assertEquals(Set.of(0, 1, 2), new HashSet<>(result));
    }

    @Test
    void testKGreaterThanNumPoints() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(2.0, 2.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 5);
        assertEquals(Arrays.asList(0, 1), result);
    }

    @Test
    void testIdenticalPoints() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(1.0, 1.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);
        assertEquals(4, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
    }

    @Test
    void testLinearPoints() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(0.0, 0.0),
            new KmeansClustering.Point(1.0, 0.0),
            new KmeansClustering.Point(2.0, 0.0),
            new KmeansClustering.Point(3.0, 0.0),
            new KmeansClustering.Point(4.0, 0.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);
        assertEquals(5, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
    }

    @Test
    void testTwoClearClusters() {
        // Two clear groups
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(0.0, 0.0),
            new KmeansClustering.Point(0.1, 0.1),
            new KmeansClustering.Point(0.2, 0.0),
            new KmeansClustering.Point(10.0, 10.0),
            new KmeansClustering.Point(10.1, 10.1),
            new KmeansClustering.Point(10.0, 10.2)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);

        // Points 0,1,2 should be in one cluster, points 3,4,5 in another
        Set<Integer> cluster012 = new HashSet<>(Arrays.asList(result.get(0), result.get(1), result.get(2)));
        Set<Integer> cluster345 = new HashSet<>(Arrays.asList(result.get(3), result.get(4), result.get(5)));

        // Each group should be in the same cluster
        assertEquals(1, cluster012.size());
        assertEquals(1, cluster345.size());
        // And the two groups should be in different clusters
        assertNotEquals(cluster012, cluster345);
    }

    @Test
    void testMaxIterParameter() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(2.0, 2.0),
            new KmeansClustering.Point(10.0, 10.0),
            new KmeansClustering.Point(11.0, 11.0)
        );
        List<Integer> result1 = KmeansClustering.kmeansClustering(points, 2, 1);
        List<Integer> result2 = KmeansClustering.kmeansClustering(points, 2, 100);

        // Both should return valid results
        assertEquals(4, result1.size());
        assertEquals(4, result2.size());
        assertTrue(result1.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
        assertTrue(result2.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
    }

    @Test
    void testLargeDataset() {
        List<KmeansClustering.Point> points = new ArrayList<>();
        // Create 3 distinct clusters
        for (int i = 0; i < 50; i++) {
            points.add(new KmeansClustering.Point(i * 0.1, i * 0.1)); // Cluster 1
            points.add(new KmeansClustering.Point(50 + i * 0.1, 50 + i * 0.1)); // Cluster 2
            points.add(new KmeansClustering.Point(100 + i * 0.1, 100 + i * 0.1)); // Cluster 3
        }

        List<Integer> result = KmeansClustering.kmeansClustering(points, 3);
        assertEquals(150, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId >= 0 && clusterId <= 2));
    }

    @Test
    void testNegativeCoordinates() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(-1.0, -1.0),
            new KmeansClustering.Point(-2.0, -2.0),
            new KmeansClustering.Point(1.0, 1.0),
            new KmeansClustering.Point(2.0, 2.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);
        assertEquals(4, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
    }

    @Test
    void testHighPrecisionCoordinates() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(1.123456789, 2.987654321),
            new KmeansClustering.Point(1.123456790, 2.987654322),
            new KmeansClustering.Point(10.111111111, 20.222222222),
            new KmeansClustering.Point(10.111111112, 20.222222223)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 2);
        assertEquals(4, result.size());
        assertTrue(result.stream().allMatch(clusterId -> clusterId == 0 || clusterId == 1));
    }

    @Test
    void testSingleClusterK1() {
        List<KmeansClustering.Point> points = Arrays.asList(
            new KmeansClustering.Point(0.0, 0.0),
            new KmeansClustering.Point(100.0, 100.0),
            new KmeansClustering.Point(-50.0, 50.0)
        );
        List<Integer> result = KmeansClustering.kmeansClustering(points, 1);
        assertEquals(Arrays.asList(0, 0, 0), result);
    }
}
