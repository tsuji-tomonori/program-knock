import random
from src.kmeans_clustering import kmeans_clustering


class TestKMeansClustering:
    def test_sample_1(self):
        # Set seed for reproducible results
        random.seed(42)
        points = [
            (1.0, 2.0),
            (1.5, 1.8),
            (5.0, 8.0),
            (8.0, 8.0),
            (1.0, 0.6),
            (9.0, 11.0),
        ]
        result = kmeans_clustering(points, k=2)

        # Check that result has correct length and values are 0 or 1
        assert len(result) == 6
        assert all(cluster_id in [0, 1] for cluster_id in result)

        # Check that similar points are in the same cluster
        # Points (1.0, 2.0), (1.5, 1.8), (1.0, 0.6) should be closer to each other
        # Points (5.0, 8.0), (8.0, 8.0), (9.0, 11.0) should be closer to each other
        close_group1 = [result[0], result[1], result[4]]  # indices 0, 1, 4
        close_group2 = [result[2], result[3], result[5]]  # indices 2, 3, 5

        # At least one of these should be true (depending on which cluster gets which group)
        same_cluster_1 = len(set(close_group1)) == 1
        same_cluster_2 = len(set(close_group2)) == 1
        assert same_cluster_1 or same_cluster_2

    def test_sample_2(self):
        points = [(1.0, 2.0), (2.0, 3.0), (3.0, 4.0)]
        result = kmeans_clustering(points, k=1)
        expected = [0, 0, 0]
        assert result == expected

    def test_sample_3(self):
        random.seed(42)
        points = [(1.0, 1.0), (2.0, 2.0), (10.0, 10.0), (11.0, 11.0), (50.0, 50.0)]
        result = kmeans_clustering(points, k=3)

        # Check that result has correct length and values are 0, 1, or 2
        assert len(result) == 5
        assert all(cluster_id in [0, 1, 2] for cluster_id in result)

        # Check that all 3 clusters are used
        assert len(set(result)) == 3

    def test_empty_points(self):
        result = kmeans_clustering([], k=2)
        assert result == []

    def test_single_point(self):
        points = [(1.0, 1.0)]
        result = kmeans_clustering(points, k=1)
        assert result == [0]

    def test_k_equals_num_points(self):
        points = [(1.0, 1.0), (2.0, 2.0), (3.0, 3.0)]
        result = kmeans_clustering(points, k=3)
        assert len(result) == 3
        assert set(result) == {0, 1, 2}

    def test_k_greater_than_num_points(self):
        points = [(1.0, 1.0), (2.0, 2.0)]
        result = kmeans_clustering(points, k=5)
        assert result == [0, 1]

    def test_identical_points(self):
        points = [(1.0, 1.0), (1.0, 1.0), (1.0, 1.0), (1.0, 1.0)]
        result = kmeans_clustering(points, k=2)
        assert len(result) == 4
        assert all(cluster_id in [0, 1] for cluster_id in result)

    def test_linear_points(self):
        random.seed(42)
        points = [(0.0, 0.0), (1.0, 0.0), (2.0, 0.0), (3.0, 0.0), (4.0, 0.0)]
        result = kmeans_clustering(points, k=2)
        assert len(result) == 5
        assert all(cluster_id in [0, 1] for cluster_id in result)

    def test_two_clear_clusters(self):
        random.seed(42)
        # Two clear groups
        points = [
            (0.0, 0.0),
            (0.1, 0.1),
            (0.2, 0.0),
            (10.0, 10.0),
            (10.1, 10.1),
            (10.0, 10.2),
        ]
        result = kmeans_clustering(points, k=2)

        # Points 0,1,2 should be in one cluster, points 3,4,5 in another
        cluster_0_1_2 = {result[0], result[1], result[2]}
        cluster_3_4_5 = {result[3], result[4], result[5]}

        # Each group should be in the same cluster
        assert len(cluster_0_1_2) == 1
        assert len(cluster_3_4_5) == 1
        # And the two groups should be in different clusters
        assert cluster_0_1_2 != cluster_3_4_5

    def test_max_iter_parameter(self):
        random.seed(42)
        points = [(1.0, 1.0), (2.0, 2.0), (10.0, 10.0), (11.0, 11.0)]
        result1 = kmeans_clustering(points, k=2, max_iter=1)
        result2 = kmeans_clustering(points, k=2, max_iter=100)

        # Both should return valid results
        assert len(result1) == 4
        assert len(result2) == 4
        assert all(cluster_id in [0, 1] for cluster_id in result1)
        assert all(cluster_id in [0, 1] for cluster_id in result2)
