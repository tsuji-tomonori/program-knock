<?php

declare(strict_types=1);

namespace ProgramKnock;

class KMeansClustering
{
    /**
     * K-meansクラスタリングを実行
     *
     * @param array<array{0: float, 1: float}> $points データ点のリスト
     * @param int $k クラスタ数
     * @param int $maxIter 最大反復回数
     * @return array<int> 各データ点が属するクラスタのインデックス
     */
    public static function cluster(array $points, int $k, int $maxIter = 100): array
    {
        if (empty($points)) {
            return [];
        }

        if ($k === 1) {
            return array_fill(0, count($points), 0);
        }

        $n = count($points);
        $k = min($k, $n);

        // 重心を初期化（最初のk個の点を使用）
        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $centroids[] = [$points[$i][0], $points[$i][1]];
        }

        $assignments = array_fill(0, $n, 0);

        for ($iter = 0; $iter < $maxIter; $iter++) {
            $newAssignments = [];

            // 各点を最も近い重心に割り当て
            for ($i = 0; $i < $n; $i++) {
                $minDistance = PHP_FLOAT_MAX;
                $closestCluster = 0;

                for ($j = 0; $j < $k; $j++) {
                    $distance = self::euclideanDistance($points[$i], $centroids[$j]);
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $closestCluster = $j;
                    }
                }

                $newAssignments[$i] = $closestCluster;
            }

            // 収束チェック
            if ($newAssignments === $assignments) {
                break;
            }

            $assignments = $newAssignments;

            // 重心を再計算
            $newCentroids = [];
            for ($j = 0; $j < $k; $j++) {
                $clusterPoints = [];
                for ($i = 0; $i < $n; $i++) {
                    if ($assignments[$i] === $j) {
                        $clusterPoints[] = $points[$i];
                    }
                }

                if (!empty($clusterPoints)) {
                    $sumX = 0.0;
                    $sumY = 0.0;
                    foreach ($clusterPoints as $point) {
                        $sumX += $point[0];
                        $sumY += $point[1];
                    }
                    $count = count($clusterPoints);
                    $newCentroids[$j] = [$sumX / $count, $sumY / $count];
                } else {
                    // 空のクラスタの場合、元の重心を維持
                    $newCentroids[$j] = $centroids[$j];
                }
            }

            $centroids = $newCentroids;
        }

        return $assignments;
    }

    /**
     * ユークリッド距離を計算
     *
     * @param array{0: float, 1: float} $point1 点1
     * @param array{0: float, 1: float} $point2 点2
     * @return float ユークリッド距離
     */
    private static function euclideanDistance(array $point1, array $point2): float
    {
        $dx = $point1[0] - $point2[0];
        $dy = $point1[1] - $point2[1];
        return sqrt($dx * $dx + $dy * $dy);
    }
}
