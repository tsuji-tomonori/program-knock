<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\RangeCounter;

class RangeCounterTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Expected true, but got false");
        }
    }

    private function assertFalse($condition, $message = ''): void
    {
        if ($condition) {
            throw new \AssertionError($message ?: "Expected false, but got true");
        }
    }

    public function testSampleCase1(): void
    {
        $arr = [1, 3, 5, 7, 9, 11];
        $l = 4;
        $r = 8;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(2, $result); // 5, 7
    }

    public function testSampleCase2(): void
    {
        $arr = [-5, -3, -1, 2, 4, 6, 8, 10];
        $l = -2;
        $r = 5;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(3, $result); // -1, 2, 4
    }

    public function testSampleCase3(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $l = 6;
        $r = 10;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(0, $result); // No elements in range
    }

    public function testSampleCase4(): void
    {
        $arr = [10, 20, 30, 40, 50];
        $l = 15;
        $r = 45;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(3, $result); // 20, 30, 40
    }

    public function testEmptyArray(): void
    {
        $arr = [];
        $l = 1;
        $r = 10;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(0, $result);
    }

    public function testSingleElementInRange(): void
    {
        $arr = [5];
        $l = 3;
        $r = 7;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(1, $result);
    }

    public function testSingleElementOutOfRange(): void
    {
        $arr = [5];
        $l = 1;
        $r = 3;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(0, $result);
    }

    public function testAllElementsInRange(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $l = 0;
        $r = 10;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(5, $result);
    }

    public function testExactBoundaryMatch(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $l = 3;
        $r = 7;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(3, $result); // 3, 5, 7
    }

    public function testNegativeRange(): void
    {
        $arr = [-10, -8, -6, -4, -2, 0, 2, 4];
        $l = -7;
        $r = -3;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(2, $result); // -6, -4
    }

    public function testMixedRange(): void
    {
        $arr = [-5, -2, 0, 3, 6, 9, 12];
        $l = -1;
        $r = 7;
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(3, $result); // 0, 3, 6
    }

    public function testInvalidRange(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $l = 5;
        $r = 3; // l > r
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(0, $result);
    }

    public function testPointRange(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $l = 5;
        $r = 5; // Single point
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(1, $result); // Just 5
    }

    public function testPointRangeNotFound(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $l = 4;
        $r = 4; // Single point not in array
        $result = RangeCounter::countInRange($arr, $l, $r);
        $this->assertEquals(0, $result);
    }

    public function testLinearVsBinarySearch(): void
    {
        $testCases = [
            [[1, 3, 5, 7, 9, 11], 4, 8],
            [[-5, -3, -1, 2, 4, 6, 8, 10], -2, 5],
            [[1, 2, 3, 4, 5], 6, 10],
            [[10, 20, 30, 40, 50], 15, 45],
            [[], 1, 10],
            [[5], 3, 7]
        ];

        foreach ($testCases as [$arr, $l, $r]) {
            $binaryResult = RangeCounter::countInRange($arr, $l, $r);
            $linearResult = RangeCounter::countInRangeLinear($arr, $l, $r);
            $this->assertEquals($linearResult, $binaryResult, "Mismatch for range [$l, $r]");
        }
    }

    public function testCountMultipleRanges(): void
    {
        $arr = [1, 3, 5, 7, 9, 11, 13, 15];
        $ranges = [
            [3, 7],   // 3, 5, 7 = 3
            [8, 12],  // 9, 11 = 2
            [0, 2],   // 1 = 1
            [16, 20], // none = 0
            [5, 5]    // 5 = 1
        ];

        $results = RangeCounter::countMultipleRanges($arr, $ranges);
        $expected = [3, 2, 1, 0, 1];
        $this->assertEquals($expected, $results);
    }

    public function testContains(): void
    {
        $arr = [1, 3, 5, 7, 9];

        $this->assertTrue(RangeCounter::contains($arr, 5));
        $this->assertTrue(RangeCounter::contains($arr, 1));
        $this->assertTrue(RangeCounter::contains($arr, 9));
        $this->assertFalse(RangeCounter::contains($arr, 4));
        $this->assertFalse(RangeCounter::contains($arr, 0));
        $this->assertFalse(RangeCounter::contains($arr, 10));
    }

    public function testValidateInput(): void
    {
        $this->assertTrue(RangeCounter::validateInput([1, 2, 3, 4, 5]));
        $this->assertTrue(RangeCounter::validateInput([-5, -1, 0, 3, 10]));
        $this->assertTrue(RangeCounter::validateInput([42]));
        $this->assertTrue(RangeCounter::validateInput([]));
        $this->assertTrue(RangeCounter::validateInput([1, 1, 2, 3])); // Duplicates allowed

        $this->assertFalse(RangeCounter::validateInput([5, 3, 1])); // Not sorted
        $this->assertFalse(RangeCounter::validateInput([1, 3, 2, 4])); // Not sorted
    }

    public function testLargeArray(): void
    {
        $arr = range(0, 10000, 2); // Even numbers 0, 2, 4, ..., 10000

        // Test various ranges
        $this->assertEquals(501, RangeCounter::countInRange($arr, 0, 1000)); // 0-1000 even numbers
        $this->assertEquals(1, RangeCounter::countInRange($arr, 1000, 1000)); // Just 1000
        $this->assertEquals(0, RangeCounter::countInRange($arr, 1, 1)); // Odd number not in array
        $this->assertEquals(5001, RangeCounter::countInRange($arr, -100, 20000)); // Entire range
    }

    public function testNegativeNumbers(): void
    {
        $arr = [-100, -80, -60, -40, -20, 0, 20, 40, 60, 80, 100];

        $this->assertEquals(5, RangeCounter::countInRange($arr, -100, -1)); // -100, -80, -60, -40, -20
        $this->assertEquals(5, RangeCounter::countInRange($arr, -50, 50)); // -40, -20, 0, 20, 40
        $this->assertEquals(3, RangeCounter::countInRange($arr, 30, 90)); // 40, 60, 80
    }

    public function testEdgeCaseBounds(): void
    {
        $arr = [1000000000];

        $this->assertEquals(1, RangeCounter::countInRange($arr, 1000000000, 1000000000));
        $this->assertEquals(1, RangeCounter::countInRange($arr, 999999999, 1000000001));
        $this->assertEquals(0, RangeCounter::countInRange($arr, 999999999, 999999999));
        $this->assertEquals(0, RangeCounter::countInRange($arr, 1000000001, 1000000001));
    }

    public function testVeryNegativeNumbers(): void
    {
        $arr = [-1000000000, -999999999, -999999998];

        $this->assertEquals(3, RangeCounter::countInRange($arr, -1000000001, -999999997));
        $this->assertEquals(1, RangeCounter::countInRange($arr, -1000000000, -1000000000));
        $this->assertEquals(0, RangeCounter::countInRange($arr, -999999997, -999999997));
    }

    public function testDuplicatesInArray(): void
    {
        $arr = [1, 1, 2, 2, 3, 3, 4, 4, 5, 5];

        $this->assertEquals(4, RangeCounter::countInRange($arr, 2, 3)); // 2, 2, 3, 3
        $this->assertEquals(2, RangeCounter::countInRange($arr, 1, 1)); // 1, 1
        $this->assertEquals(10, RangeCounter::countInRange($arr, 0, 10)); // All elements
    }

    public function testPerformanceTest(): void
    {
        // Create large sorted array
        $arr = range(0, 50000);

        $startTime = microtime(true);
        $results = RangeCounter::performanceTest($arr, 1000);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertEquals(1000, count($results));

        // All results should be valid
        foreach ($results as $result) {
            $this->assertTrue($result >= 0);
            $this->assertTrue($result <= count($arr));
        }
    }

    public function testRandomizedTesting(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $size = rand(5, 100);
            $arr = [];

            // Generate sorted array (may have duplicates)
            $current = rand(-100, -50);
            for ($j = 0; $j < $size; $j++) {
                $arr[] = $current;
                $current += rand(0, 5); // Allow duplicates
            }

            $l = rand($arr[0] - 10, $arr[$size - 1] + 10);
            $r = rand($l, $arr[$size - 1] + 10);

            $binaryResult = RangeCounter::countInRange($arr, $l, $r);
            $linearResult = RangeCounter::countInRangeLinear($arr, $l, $r);

            $this->assertEquals($linearResult, $binaryResult, "Random test failed for range [$l, $r]");
        }
    }

    public function testBoundaryConditions(): void
    {
        $arr = [1, 5, 10, 15, 20];

        // Test boundaries
        $this->assertEquals(5, RangeCounter::countInRange($arr, 1, 20)); // Exact boundaries
        $this->assertEquals(3, RangeCounter::countInRange($arr, 5, 15)); // Inner boundaries
        $this->assertEquals(0, RangeCounter::countInRange($arr, 0, 0)); // Before range
        $this->assertEquals(0, RangeCounter::countInRange($arr, 21, 25)); // After range
        $this->assertEquals(0, RangeCounter::countInRange($arr, 2, 4)); // Gap in array
        $this->assertEquals(0, RangeCounter::countInRange($arr, 11, 14)); // Another gap
    }

    public function testConsistencyCheck(): void
    {
        $arr = [1, 3, 5, 7, 9, 11];
        $l = 4;
        $r = 8;

        // Multiple calls should return same result
        for ($i = 0; $i < 10; $i++) {
            $result = RangeCounter::countInRange($arr, $l, $r);
            $this->assertEquals(2, $result);
        }
    }

    public function testSpecialCases(): void
    {
        // All negative
        $arr = [-10, -8, -6, -4, -2];
        $this->assertEquals(2, RangeCounter::countInRange($arr, -9, -5)); // -8, -6

        // All positive
        $arr = [2, 4, 6, 8, 10];
        $this->assertEquals(3, RangeCounter::countInRange($arr, 3, 9)); // 4, 6, 8

        // Around zero
        $arr = [-2, -1, 0, 1, 2];
        $this->assertEquals(3, RangeCounter::countInRange($arr, -1, 1)); // -1, 0, 1
        $this->assertEquals(1, RangeCounter::countInRange($arr, 0, 0)); // 0
    }

    public function testVeryLargeRange(): void
    {
        $arr = [0, 1000000000];

        $this->assertEquals(2, RangeCounter::countInRange($arr, -1000000000, 1000000000));
        $this->assertEquals(1, RangeCounter::countInRange($arr, 500000000, 1000000000));
        $this->assertEquals(1, RangeCounter::countInRange($arr, -1000000000, 500000000));
    }
}
