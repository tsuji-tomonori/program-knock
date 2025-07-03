<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\KMeansClustering;

class KMeansClusteringTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertIsArray($value, $message = ''): void
    {
        if (!is_array($value)) {
            throw new \AssertionError($message ?: "Expected array, but got " . gettype($value));
        }
    }

    private function assertCount($expected, $actual, $message = ''): void
    {
        $count = count($actual);
        if ($count !== $expected) {
            throw new \AssertionError($message ?: "Expected count $expected, but got $count");
        }
    }

    private function assertContains($needle, $haystack, $message = ''): void
    {
        if (!in_array($needle, $haystack, true)) {
            throw new \AssertionError($message ?: "Value " . json_encode($needle) . " not found in " . json_encode($haystack));
        }
    }

    private function assertLessThan($expected, $actual, $message = ''): void
    {
        if ($actual >= $expected) {
            throw new \AssertionError($message ?: "Expected value < $expected, but got $actual");
        }
    }

    public function testSampleCase1(): void
    {
        $points = [
            [1.0, 2.0],
            [1.5, 1.8],
            [5.0, 8.0],
            [8.0, 8.0],
            [1.0, 0.6],
            [9.0, 11.0]
        ];

        $result = KMeansClustering::cluster($points, 2);

        $this->assertIsArray($result);
        $this->assertCount(6, $result);

        // すべての値が0または1であることを確認
        foreach ($result as $cluster) {
            $this->assertContains($cluster, [0, 1]);
        }
    }

    public function testSampleCase2(): void
    {
        $points = [
            [1.0, 2.0],
            [2.0, 3.0],
            [3.0, 4.0]
        ];

        $result = KMeansClustering::cluster($points, 1);
        $this->assertEquals([0, 0, 0], $result);
    }

    public function testSampleCase3(): void
    {
        $points = [
            [1.0, 1.0],
            [2.0, 2.0],
            [10.0, 10.0],
            [11.0, 11.0],
            [50.0, 50.0]
        ];

        $result = KMeansClustering::cluster($points, 3);

        $this->assertIsArray($result);
        $this->assertCount(5, $result);

        // 3つの異なるクラスタが存在することを確認
        $uniqueClusters = array_unique($result);
        $this->assertCount(3, $uniqueClusters);
    }

    public function testEmptyPoints(): void
    {
        $result = KMeansClustering::cluster([], 2);
        $this->assertEquals([], $result);
    }

    public function testSinglePoint(): void
    {
        $points = [[1.0, 1.0]];
        $result = KMeansClustering::cluster($points, 1);
        $this->assertEquals([0], $result);
    }

    public function testTwoPointsTwoClusters(): void
    {
        $points = [
            [0.0, 0.0],
            [10.0, 10.0]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        // 2つの異なるクラスタに分かれることを確認
        $uniqueClusters = array_unique($result);
        $this->assertCount(2, $uniqueClusters);
    }

    public function testIdenticalPoints(): void
    {
        $points = [
            [1.0, 1.0],
            [1.0, 1.0],
            [1.0, 1.0]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }

    public function testLinearPoints(): void
    {
        $points = [
            [1.0, 1.0],
            [2.0, 2.0],
            [3.0, 3.0],
            [4.0, 4.0]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);

        // すべての値が有効なクラスタインデックスであることを確認
        foreach ($result as $cluster) {
            $this->assertContains($cluster, [0, 1]);
        }
    }

    public function testWellSeparatedClusters(): void
    {
        $points = [
            // クラスタ1
            [1.0, 1.0],
            [1.1, 1.1],
            [0.9, 0.9],
            // クラスタ2
            [10.0, 10.0],
            [10.1, 10.1],
            [9.9, 9.9]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(6, $result);

        // 最初の3点が同じクラスタ、後の3点が同じクラスタであることを期待
        $cluster1 = $result[0];
        $cluster2 = $result[3];

        $this->assertEquals($cluster1, $result[1]);
        $this->assertEquals($cluster1, $result[2]);
        $this->assertEquals($cluster2, $result[4]);
        $this->assertEquals($cluster2, $result[5]);
    }

    public function testMaxIterations(): void
    {
        $points = [
            [1.0, 1.0],
            [2.0, 2.0],
            [10.0, 10.0],
            [11.0, 11.0]
        ];

        // 最大反復回数を1に設定
        $result = KMeansClustering::cluster($points, 2, 1);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
    }

    public function testLargeDataset(): void
    {
        // 大量のデータポイントを生成
        $points = [];

        // 3つの明確に分離されたクラスタを生成
        for ($i = 0; $i < 50; $i++) {
            $points[] = [random_int(0, 10) / 10.0, random_int(0, 10) / 10.0]; // クラスタ1
            $points[] = [random_int(90, 100) / 10.0, random_int(90, 100) / 10.0]; // クラスタ2
            $points[] = [random_int(0, 10) / 10.0, random_int(90, 100) / 10.0]; // クラスタ3
        }

        $startTime = microtime(true);
        $result = KMeansClustering::cluster($points, 3);
        $endTime = microtime(true);

        $this->assertIsArray($result);
        $this->assertCount(150, $result);
        $this->assertLessThan(5.0, $endTime - $startTime);
    }

    public function testKGreaterThanPoints(): void
    {
        $points = [
            [1.0, 1.0],
            [2.0, 2.0]
        ];

        // k > 点の数の場合
        $result = KMeansClustering::cluster($points, 5);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testConvergence(): void
    {
        // 非常に明確に分離されたクラスタで収束をテスト
        $points = [
            [0.0, 0.0],
            [0.1, 0.1],
            [100.0, 100.0],
            [100.1, 100.1]
        ];

        $result = KMeansClustering::cluster($points, 2, 10);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);

        // 近い点が同じクラスタに分類されることを確認
        $this->assertEquals($result[0], $result[1]);
        $this->assertEquals($result[2], $result[3]);
    }

    public function testNegativeCoordinates(): void
    {
        $points = [
            [-1.0, -1.0],
            [-2.0, -2.0],
            [1.0, 1.0],
            [2.0, 2.0]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
    }

    public function testFloatingPointPrecision(): void
    {
        $points = [
            [1.123456789, 2.987654321],
            [1.123456790, 2.987654322],
            [10.111111111, 20.222222222]
        ];

        $result = KMeansClustering::cluster($points, 2);
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }
}
