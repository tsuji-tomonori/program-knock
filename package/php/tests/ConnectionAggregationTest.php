<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\ConnectionAggregation;

class ConnectionAggregationTest
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

    private function assertCount($expected, $actual, $message = ''): void
    {
        $count = count($actual);
        if ($count !== $expected) {
            throw new \AssertionError($message ?: "Expected count $expected, but got $count");
        }
    }

    public function testSampleCase1(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 3, 'n_disconnect' => 0],
            ['time' => 1, 'n_connect' => 2, 'n_disconnect' => 0],
            ['time' => 4, 'n_connect' => 5, 'n_disconnect' => 2],
            ['time' => 5, 'n_connect' => 3, 'n_disconnect' => 5]
        ];

        $result = ConnectionAggregation::calculate(5, 1, $logs);
        $this->assertEquals([3, 5, 5, 5, 8, 6], $result);
    }

    public function testSampleCase2(): void
    {
        $result = ConnectionAggregation::calculate(3, 1, []);
        $this->assertEquals([0, 0, 0, 0], $result);
    }

    public function testSampleCase3(): void
    {
        $logs = [
            ['time' => 1, 'n_connect' => 4, 'n_disconnect' => 0],
            ['time' => 3, 'n_connect' => 1, 'n_disconnect' => 1],
            ['time' => 6, 'n_connect' => 3, 'n_disconnect' => 2]
        ];

        $result = ConnectionAggregation::calculate(6, 2, $logs);
        $this->assertEquals([0, 4, 4, 5], $result);
    }

    public function testMinimalTimeRange(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 5, 'n_disconnect' => 0]
        ];

        $result = ConnectionAggregation::calculate(0, 1, $logs);
        $this->assertEquals([5], $result);
    }

    public function testMaxPeriod(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 3, 'n_disconnect' => 0],
            ['time' => 5, 'n_connect' => 2, 'n_disconnect' => 1]
        ];

        $result = ConnectionAggregation::calculate(10, 10, $logs);
        $this->assertEquals([3, 4], $result);
    }

    public function testFullDisconnectReconnect(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 5, 'n_disconnect' => 0],
            ['time' => 2, 'n_connect' => 0, 'n_disconnect' => 5],
            ['time' => 4, 'n_connect' => 3, 'n_disconnect' => 0]
        ];

        $result = ConnectionAggregation::calculate(4, 1, $logs);
        $this->assertEquals([5, 5, 0, 0, 3], $result);
    }

    public function testSimultaneousConnectDisconnect(): void
    {
        $logs = [
            ['time' => 1, 'n_connect' => 5, 'n_disconnect' => 3],
            ['time' => 3, 'n_connect' => 2, 'n_disconnect' => 4]
        ];

        $result = ConnectionAggregation::calculate(3, 1, $logs);
        $this->assertEquals([0, 2, 2, 0], $result);
    }

    public function testLargeTimeRange(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 10, 'n_disconnect' => 0],
            ['time' => 500, 'n_connect' => 5, 'n_disconnect' => 3],
            ['time' => 1000, 'n_connect' => 8, 'n_disconnect' => 2]
        ];

        $result = ConnectionAggregation::calculate(1000, 100, $logs);
        $this->assertIsArray($result);
        $this->assertCount(11, $result);
        $this->assertEquals(10, $result[0]);
        $this->assertEquals(12, $result[5]);
        $this->assertEquals(18, $result[10]);
    }

    public function testBoundaryTimeLog(): void
    {
        $logs = [
            ['time' => 10, 'n_connect' => 5, 'n_disconnect' => 2]
        ];

        $result = ConnectionAggregation::calculate(10, 5, $logs);
        $this->assertEquals([0, 0, 3], $result);
    }

    public function testNonConsecutiveTimeLogs(): void
    {
        $logs = [
            ['time' => 3, 'n_connect' => 5, 'n_disconnect' => 0],
            ['time' => 7, 'n_connect' => 3, 'n_disconnect' => 1],
            ['time' => 15, 'n_connect' => 2, 'n_disconnect' => 3]
        ];

        $result = ConnectionAggregation::calculate(20, 4, $logs);
        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $this->assertEquals(0, $result[0]);
        $this->assertEquals(5, $result[1]);
        $this->assertEquals(7, $result[2]);
        $this->assertEquals(6, $result[4]);
    }

    public function testLargeConnectionFluctuation(): void
    {
        $logs = [
            ['time' => 0, 'n_connect' => 100, 'n_disconnect' => 0],
            ['time' => 2, 'n_connect' => 50, 'n_disconnect' => 80],
            ['time' => 4, 'n_connect' => 30, 'n_disconnect' => 50]
        ];

        $result = ConnectionAggregation::calculate(5, 1, $logs);
        $this->assertEquals([100, 100, 70, 70, 50, 50], $result);
    }

    public function testSmallPeriodAggregation(): void
    {
        $logs = [
            ['time' => 1, 'n_connect' => 3, 'n_disconnect' => 0],
            ['time' => 3, 'n_connect' => 2, 'n_disconnect' => 1],
            ['time' => 6, 'n_connect' => 4, 'n_disconnect' => 2],
            ['time' => 8, 'n_connect' => 1, 'n_disconnect' => 3]
        ];

        $result = ConnectionAggregation::calculate(8, 1, $logs);
        $this->assertIsArray($result);
        $this->assertCount(9, $result);
        $this->assertEquals(0, $result[0]);
        $this->assertEquals(3, $result[1]);
        $this->assertEquals(4, $result[3]);
        $this->assertEquals(6, $result[6]);
        $this->assertEquals(4, $result[8]);
    }

    public function testPerformance(): void
    {
        // 大量のログデータを生成
        $logs = [];
        for ($i = 0; $i < 500; $i++) {
            $logs[] = [
                'time' => $i * 2,
                'n_connect' => random_int(0, 10),
                'n_disconnect' => random_int(0, 5)
            ];
        }

        $startTime = microtime(true);
        $result = ConnectionAggregation::calculate(1000, 50, $logs);
        $endTime = microtime(true);

        $this->assertIsArray($result);
        $this->assertLessThan(2.0, $endTime - $startTime);
    }

    public function testUnsortedLogs(): void
    {
        // ログが時系列順でない場合
        $logs = [
            ['time' => 5, 'n_connect' => 3, 'n_disconnect' => 0],
            ['time' => 1, 'n_connect' => 2, 'n_disconnect' => 0],
            ['time' => 3, 'n_connect' => 1, 'n_disconnect' => 1]
        ];

        $result = ConnectionAggregation::calculate(6, 2, $logs);
        $this->assertEquals([0, 2, 2, 5], $result);
    }

    public function testZeroConnections(): void
    {
        $logs = [
            ['time' => 1, 'n_connect' => 5, 'n_disconnect' => 5],
            ['time' => 3, 'n_connect' => 0, 'n_disconnect' => 0]
        ];

        $result = ConnectionAggregation::calculate(4, 1, $logs);
        $this->assertEquals([0, 0, 0, 0, 0], $result);
    }
}
