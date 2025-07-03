<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\AgeStatistics;

class AgeStatisticsTest
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
        $result = AgeStatistics::calculate([25, 30, 35, 40, 45, 50]);
        $this->assertEquals([50, 25, 37.5, 3], $result);
    }

    public function testSampleCase2(): void
    {
        $result = AgeStatistics::calculate([18, 22, 22, 24, 29, 35, 40, 50, 60]);
        $this->assertEquals([60, 18, 33.33, 5], $result);
    }

    public function testSinglePerson(): void
    {
        $result = AgeStatistics::calculate([30]);
        $this->assertEquals([30, 30, 30.0, 1], $result);
    }

    public function testAllSameAge(): void
    {
        $result = AgeStatistics::calculate([25, 25, 25, 25, 25]);
        $this->assertEquals([25, 25, 25.0, 5], $result);
    }

    public function testBoundaryAgeMin(): void
    {
        $result = AgeStatistics::calculate([0, 5, 10, 15, 20]);
        $this->assertEquals([20, 0, 10.0, 3], $result);
    }

    public function testBoundaryAgeMax(): void
    {
        $result = AgeStatistics::calculate([100, 110, 115, 118, 120]);
        $this->assertEquals([120, 100, 112.6, 2], $result);
    }

    public function testExtremeAgeRange(): void
    {
        $result = AgeStatistics::calculate([0, 120, 60]);
        $this->assertEquals([120, 0, 60.0, 2], $result);
    }

    public function testAverageIsInteger(): void
    {
        $result = AgeStatistics::calculate([20, 30, 40]);
        $this->assertEquals([40, 20, 30.0, 2], $result);
    }

    public function testRoundingBoundary(): void
    {
        $result = AgeStatistics::calculate([33, 34, 35]);
        $this->assertEquals([35, 33, 34.0, 2], $result);
    }

    public function testDecimalAverage(): void
    {
        $result = AgeStatistics::calculate([10, 11, 12]);
        $this->assertEquals([12, 10, 11.0, 2], $result);
    }

    public function testAllBelowAverage(): void
    {
        $result = AgeStatistics::calculate([20, 21, 22, 23, 24]);
        $this->assertEquals([24, 20, 22.0, 3], $result);
    }

    public function testPartialBelowAverage(): void
    {
        $result = AgeStatistics::calculate([30, 31, 32]);
        $this->assertEquals([32, 30, 31.0, 2], $result);
    }

    public function testExactAverageValue(): void
    {
        $result = AgeStatistics::calculate([25, 30, 35]);
        $this->assertEquals([35, 25, 30.0, 2], $result);
    }

    public function testLargeDataset(): void
    {
        // 10000人分のランダム年齢データ
        $ages = [];
        for ($i = 0; $i < 10000; $i++) {
            $ages[] = random_int(0, 120);
        }

        $startTime = microtime(true);
        $result = AgeStatistics::calculate($ages);
        $endTime = microtime(true);

        $this->assertIsArray($result);
        $this->assertEquals(4, count($result));
        $this->assertLessThan(1.0, $endTime - $startTime);
    }

    public function testEmptyArrayThrowsException(): void
    {
        try {
            AgeStatistics::calculate([]);
            throw new \AssertionError("Expected InvalidArgumentException");
        } catch (\InvalidArgumentException $e) {
            // Expected
        }
    }
}
