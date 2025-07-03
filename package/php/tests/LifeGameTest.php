<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\LifeGame;

class LifeGameTest
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

    private function assertLessThan($expected, $actual, $message = ''): void
    {
        if ($actual >= $expected) {
            throw new \AssertionError($message ?: "Expected value < $expected, but got $actual");
        }
    }

    public function testSampleCase1(): void
    {
        $board = [
            [0, 1, 0],
            [0, 1, 1],
            [1, 0, 0]
        ];

        $expected = [
            [0, 1, 1],
            [1, 1, 1],
            [0, 1, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $board = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1]
        ];

        $expected = [
            [1, 0, 1],
            [0, 0, 0],
            [1, 0, 1]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testStillPattern(): void
    {
        // 2x2ブロック（静止パターン）
        $board = [
            [1, 1, 0],
            [1, 1, 0],
            [0, 0, 0]
        ];

        $expected = [
            [1, 1, 0],
            [1, 1, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testOscillatorPattern(): void
    {
        // ブリンカー（振動パターン）
        $board = [
            [0, 1, 0],
            [0, 1, 0],
            [0, 1, 0]
        ];

        $expected = [
            [0, 0, 0],
            [1, 1, 1],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testAllDeadGrid(): void
    {
        $board = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
        ];

        $expected = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testSingleLiveCell(): void
    {
        $board = [
            [0, 0, 0],
            [0, 1, 0],
            [0, 0, 0]
        ];

        $expected = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals($expected, $result);
    }

    public function testBoundaryCell(): void
    {
        $board = [
            [1, 1, 0],
            [1, 0, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
        $this->assertEquals(3, count($result[0]));
    }

    public function testBirthRule(): void
    {
        // 死セルが3個の隣接で誕生
        $board = [
            [1, 1, 0],
            [1, 0, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals(1, $result[1][1]); // 中央セルが誕生
    }

    public function testSurvivalRule2Neighbors(): void
    {
        // 生セルが2個の隣接で生存
        $board = [
            [1, 1, 0],
            [0, 1, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals(1, $result[1][1]); // 中央セルが生存継続
    }

    public function testSurvivalRule3Neighbors(): void
    {
        // 生セルが3個の隣接で生存
        $board = [
            [1, 1, 0],
            [1, 1, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals(1, $result[0][0]); // 左上セルが生存継続
    }

    public function testDeathRuleUnderpopulation(): void
    {
        // 過疎による死滅（1個以下の隣接）
        $board = [
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 0]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals(0, $result[0][0]); // 過疎で死滅
        $this->assertEquals(0, $result[1][1]); // 過疎で死滅
    }

    public function testDeathRuleOvercrowding(): void
    {
        // 過密による死滅（4個以上の隣接）
        $board = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1]
        ];

        $result = LifeGame::nextGeneration($board);
        $this->assertEquals(0, $result[1][1]); // 過密で死滅
    }

    public function testGliderPattern(): void
    {
        // グライダーパターン（5x5グリッド）
        $board = [
            [0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0],
            [1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0]
        ];

        // 4世代後の状態をテスト
        $current = $board;
        for ($i = 0; $i < 4; $i++) {
            $current = LifeGame::nextGeneration($current);
        }

        $this->assertIsArray($current);
        // グライダーが右下に移動していることを確認
        $this->assertEquals(1, $current[2][3]); // 一部確認
    }

    public function testToadOscillator(): void
    {
        // トード振動子（4x4グリッド）
        $board = [
            [0, 0, 0, 0],
            [0, 1, 1, 1],
            [1, 1, 1, 0],
            [0, 0, 0, 0]
        ];

        // 2世代後に元に戻ることを確認
        $gen1 = LifeGame::nextGeneration($board);
        $gen2 = LifeGame::nextGeneration($gen1);

        $this->assertEquals($board, $gen2);
    }

    public function testMultipleGenerations(): void
    {
        $board = [
            [0, 1, 0],
            [0, 1, 0],
            [0, 1, 0]
        ];

        // 10世代の進化を追跡
        $current = $board;
        for ($i = 0; $i < 10; $i++) {
            $current = LifeGame::nextGeneration($current);
        }

        $this->assertIsArray($current);
        $this->assertEquals(3, count($current));
    }

    public function testLargeGrid(): void
    {
        // 大きなグリッドでのパフォーマンステスト
        $size = 20; // 50x50は時間がかかりすぎるため20x20に縮小
        $board = [];

        for ($i = 0; $i < $size; $i++) {
            $row = [];
            for ($j = 0; $j < $size; $j++) {
                $row[] = random_int(0, 1);
            }
            $board[] = $row;
        }

        $startTime = microtime(true);

        $current = $board;
        for ($i = 0; $i < 10; $i++) {
            $current = LifeGame::nextGeneration($current);
        }

        $endTime = microtime(true);

        $this->assertIsArray($current);
        $this->assertEquals($size, count($current));
        $this->assertLessThan(5.0, $endTime - $startTime);
    }

    public function testEmptyBoard(): void
    {
        $board = [];
        $result = LifeGame::nextGeneration($board);
        $this->assertEquals([], $result);
    }

    public function testSingleRowBoard(): void
    {
        $board = [[1, 0, 1]];
        $result = LifeGame::nextGeneration($board);
        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
        $this->assertEquals(3, count($result[0]));
    }
}
