use std::f64;

pub fn kmeans_clustering(points: &[(f64, f64)], k: usize, max_iter: usize) -> Vec<usize> {
    if points.is_empty() || k == 0 {
        return vec![];
    }

    if k >= points.len() {
        return (0..points.len()).collect();
    }

    let mut centroids = initialize_centroids(points, k);
    let mut assignments = vec![0; points.len()];

    for _ in 0..max_iter {
        let new_assignments = assign_points_to_centroids(points, &centroids);

        if new_assignments == assignments {
            break;
        }

        assignments = new_assignments;
        centroids = update_centroids(points, &assignments, k);
    }

    assignments
}

fn initialize_centroids(points: &[(f64, f64)], k: usize) -> Vec<(f64, f64)> {
    let mut centroids = Vec::new();
    let step = points.len() / k;

    for i in 0..k {
        let index = i * step;
        centroids.push(points[index]);
    }

    centroids
}

fn assign_points_to_centroids(points: &[(f64, f64)], centroids: &[(f64, f64)]) -> Vec<usize> {
    points.iter().map(|point| {
        centroids.iter()
            .enumerate()
            .min_by(|(_, c1), (_, c2)| {
                let d1 = distance(point, c1);
                let d2 = distance(point, c2);
                d1.partial_cmp(&d2).unwrap()
            })
            .map(|(i, _)| i)
            .unwrap_or(0)
    }).collect()
}

fn update_centroids(points: &[(f64, f64)], assignments: &[usize], k: usize) -> Vec<(f64, f64)> {
    (0..k).map(|cluster| {
        let cluster_points: Vec<_> = points.iter()
            .zip(assignments.iter())
            .filter(|(_, &assignment)| assignment == cluster)
            .map(|(point, _)| point)
            .collect();

        if cluster_points.is_empty() {
            (0.0, 0.0)
        } else {
            let sum_x: f64 = cluster_points.iter().map(|(x, _)| x).sum();
            let sum_y: f64 = cluster_points.iter().map(|(_, y)| y).sum();
            let count = cluster_points.len() as f64;
            (sum_x / count, sum_y / count)
        }
    }).collect()
}

fn distance(p1: &(f64, f64), p2: &(f64, f64)) -> f64 {
    let dx = p1.0 - p2.0;
    let dy = p1.1 - p2.1;
    (dx * dx + dy * dy).sqrt()
}
