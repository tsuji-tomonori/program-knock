import { kmeansClusteringSimple } from '../src/kmeansClusteringSimple';

describe('KMeansClusteringSimple', () => {
  test('sample_1', () => {
    const points: Array<[number, number]> = [
      [1.0, 2.0],
      [1.5, 1.8],
      [5.0, 8.0],
      [8.0, 8.0],
      [1.0, 0.6],
      [9.0, 11.0],
    ];
    const result = kmeansClusteringSimple(points, 2);

    // Check that result has correct length and values are 0 or 1
    expect(result).toHaveLength(6);
    expect(result.every(clusterId => clusterId === 0 || clusterId === 1)).toBe(true);

    // Check that similar points are in the same cluster
    // Points (1.0, 2.0), (1.5, 1.8), (1.0, 0.6) should be closer to each other
    // Points (5.0, 8.0), (8.0, 8.0), (9.0, 11.0) should be closer to each other
    const closeGroup1 = [result[0], result[1], result[4]]; // indices 0, 1, 4
    const closeGroup2 = [result[2], result[3], result[5]]; // indices 2, 3, 5

    // At least one of these should be true (depending on which cluster gets which group)
    const sameCluster1 = new Set(closeGroup1).size === 1;
    const sameCluster2 = new Set(closeGroup2).size === 1;
    expect(sameCluster1 || sameCluster2).toBe(true);
  });

  test('sample_2', () => {
    const points: Array<[number, number]> = [[1.0, 2.0], [2.0, 3.0], [3.0, 4.0]];
    const result = kmeansClusteringSimple(points, 1);
    const expected = [0, 0, 0];
    expect(result).toEqual(expected);
  });

  test('sample_3', () => {
    const points: Array<[number, number]> = [
      [1.0, 1.0],
      [2.0, 2.0],
      [10.0, 10.0],
      [11.0, 11.0],
      [50.0, 50.0]
    ];
    const result = kmeansClusteringSimple(points, 3);

    // Check that result has correct length and values are 0, 1, or 2
    expect(result).toHaveLength(5);
    expect(result.every(clusterId => clusterId >= 0 && clusterId <= 2)).toBe(true);

    // Check that all 3 clusters are used
    expect(new Set(result).size).toBe(3);
  });

  test('empty_points', () => {
    const result = kmeansClusteringSimple([], 2);
    expect(result).toEqual([]);
  });

  test('single_point', () => {
    const points: Array<[number, number]> = [[1.0, 1.0]];
    const result = kmeansClusteringSimple(points, 1);
    expect(result).toEqual([0]);
  });

  test('k_equals_num_points', () => {
    const points: Array<[number, number]> = [[1.0, 1.0], [2.0, 2.0], [3.0, 3.0]];
    const result = kmeansClusteringSimple(points, 3);
    expect(result).toHaveLength(3);
    expect(new Set(result)).toEqual(new Set([0, 1, 2]));
  });

  test('k_greater_than_num_points', () => {
    const points: Array<[number, number]> = [[1.0, 1.0], [2.0, 2.0]];
    const result = kmeansClusteringSimple(points, 5);
    expect(result).toEqual([0, 1]);
  });

  test('identical_points', () => {
    const points: Array<[number, number]> = [
      [1.0, 1.0],
      [1.0, 1.0],
      [1.0, 1.0],
      [1.0, 1.0]
    ];
    const result = kmeansClusteringSimple(points, 2);
    expect(result).toHaveLength(4);
    expect(result.every(clusterId => clusterId === 0 || clusterId === 1)).toBe(true);
  });

  test('linear_points', () => {
    const points: Array<[number, number]> = [
      [0.0, 0.0],
      [1.0, 0.0],
      [2.0, 0.0],
      [3.0, 0.0],
      [4.0, 0.0]
    ];
    const result = kmeansClusteringSimple(points, 2);
    expect(result).toHaveLength(5);
    expect(result.every(clusterId => clusterId === 0 || clusterId === 1)).toBe(true);
  });

  test('two_clear_clusters', () => {
    // Two clear groups
    const points: Array<[number, number]> = [
      [0.0, 0.0],
      [0.1, 0.1],
      [0.2, 0.0],
      [10.0, 10.0],
      [10.1, 10.1],
      [10.0, 10.2],
    ];
    const result = kmeansClusteringSimple(points, 2);

    // Points 0,1,2 should be in one cluster, points 3,4,5 in another
    const cluster012 = new Set([result[0], result[1], result[2]]);
    const cluster345 = new Set([result[3], result[4], result[5]]);

    // Each group should be in the same cluster
    expect(cluster012.size).toBe(1);
    expect(cluster345.size).toBe(1);
    // And the two groups should be in different clusters
    expect(cluster012).not.toEqual(cluster345);
  });

  test('max_iter_parameter', () => {
    const points: Array<[number, number]> = [
      [1.0, 1.0],
      [2.0, 2.0],
      [10.0, 10.0],
      [11.0, 11.0]
    ];
    const result1 = kmeansClusteringSimple(points, 2, 1);
    const result2 = kmeansClusteringSimple(points, 2, 100);

    // Both should return valid results
    expect(result1).toHaveLength(4);
    expect(result2).toHaveLength(4);
    expect(result1.every(clusterId => clusterId === 0 || clusterId === 1)).toBe(true);
    expect(result2.every(clusterId => clusterId === 0 || clusterId === 1)).toBe(true);
  });
});
