use program_knock::kmeans_clustering::*;

#[test]
fn test_kmeans_clustering_k2() {
    let points = vec![(1.0, 1.0), (2.0, 2.0), (8.0, 8.0), (9.0, 9.0)];
    let result = kmeans_clustering(&points, 2, 10);
    assert_eq!(result.len(), 4);
    assert!(result[0] == result[1]);
    assert!(result[2] == result[3]);
    assert!(result[0] != result[2]);
}

#[test]
fn test_kmeans_clustering_k1() {
    let points = vec![(1.0, 1.0), (2.0, 2.0), (3.0, 3.0)];
    let result = kmeans_clustering(&points, 1, 10);
    assert_eq!(result, vec![0, 0, 0]);
}

#[test]
fn test_kmeans_clustering_k3() {
    let points = vec![
        (0.0, 0.0), (1.0, 0.0),
        (5.0, 5.0), (6.0, 5.0),
        (10.0, 10.0), (11.0, 10.0)
    ];
    let result = kmeans_clustering(&points, 3, 10);
    assert_eq!(result.len(), 6);
}

#[test]
fn test_kmeans_clustering_single_point() {
    let points = vec![(1.0, 1.0)];
    let result = kmeans_clustering(&points, 1, 10);
    assert_eq!(result, vec![0]);
}

#[test]
fn test_kmeans_clustering_k_equals_points() {
    let points = vec![(1.0, 1.0), (2.0, 2.0), (3.0, 3.0)];
    let result = kmeans_clustering(&points, 3, 10);
    assert_eq!(result, vec![0, 1, 2]);
}

#[test]
fn test_kmeans_clustering_identical_coordinates() {
    let points = vec![(1.0, 1.0), (1.0, 1.0), (1.0, 1.0)];
    let result = kmeans_clustering(&points, 2, 10);
    assert_eq!(result.len(), 3);
}

#[test]
fn test_kmeans_clustering_large_coordinates() {
    let points = vec![(1000.0, 1000.0), (2000.0, 2000.0)];
    let result = kmeans_clustering(&points, 2, 10);
    assert_eq!(result, vec![0, 1]);
}

#[test]
fn test_kmeans_clustering_small_precision() {
    let points = vec![(0.001, 0.001), (0.002, 0.002)];
    let result = kmeans_clustering(&points, 2, 10);
    assert_eq!(result.len(), 2);
}

#[test]
fn test_kmeans_clustering_max_iterations() {
    let points = vec![(1.0, 1.0), (2.0, 2.0), (3.0, 3.0), (4.0, 4.0)];
    let result = kmeans_clustering(&points, 2, 100);
    assert_eq!(result.len(), 4);
}

#[test]
fn test_kmeans_clustering_convergence() {
    let points = vec![
        (0.0, 0.0), (1.0, 1.0),
        (10.0, 10.0), (11.0, 11.0)
    ];
    let result = kmeans_clustering(&points, 2, 10);
    assert_eq!(result.len(), 4);
    assert!(result[0] == result[1]);
    assert!(result[2] == result[3]);
}
