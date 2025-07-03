<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\ClosestValueFinder;

class ClosestValueFinderTest
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
        $arr = [1, 3, 5, 7, 9];
        $target = 6;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5, $result);
    }

    public function testSampleCase2(): void
    {
        $arr = [2, 4, 6, 8, 10];
        $target = 7;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(6, $result);
    }

    public function testSampleCase3(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $target = 10;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5, $result);
    }

    public function testSampleCase4(): void
    {
        $arr = [-10, -5, 0, 5, 10];
        $target = -7;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(-5, $result);
    }

    public function testSampleCase5(): void
    {
        $arr = [10];
        $target = 7;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(10, $result);
    }

    public function testExactMatch(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $target = 5;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5, $result);
    }

    public function testSingleElementExactMatch(): void
    {
        $arr = [42];
        $target = 42;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(42, $result);
    }

    public function testTargetSmallerThanAll(): void
    {
        $arr = [10, 20, 30, 40, 50];
        $target = 5;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(10, $result);
    }

    public function testTargetLargerThanAll(): void
    {
        $arr = [1, 2, 3, 4, 5];
        $target = 100;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5, $result);
    }

    public function testNegativeNumbers(): void
    {
        $arr = [-50, -30, -10, -5, -1];
        $target = -8;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(-10, $result);
    }

    public function testMixedNumbers(): void
    {
        $arr = [-100, -50, 0, 50, 100];
        $target = 25;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(0, $result); // Distance 25 vs 25, choose smaller
    }

    public function testTieChoosesSmaller(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $target = 4;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(3, $result); // Both 3 and 5 are distance 1, choose 3
    }

    public function testLargeNumbers(): void
    {
        $arr = [1000000000, 1000000001, 1000000002];
        $target = 1000000000;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(1000000000, $result);
    }

    public function testVeryNegativeNumbers(): void
    {
        $arr = [-1000000000, -999999999, -999999998];
        $target = -1000000000;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(-1000000000, $result);
    }

    public function testTwoElements(): void
    {
        $arr = [10, 20];
        $target = 15;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(10, $result); // Both distance 5, choose smaller
    }

    public function testTwoElementsCloserToSecond(): void
    {
        $arr = [10, 20];
        $target = 18;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(20, $result); // Distance 8 vs 2
    }

    public function testThreeElementsMiddle(): void
    {
        $arr = [1, 5, 9];
        $target = 5;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5, $result);
    }

    public function testLargeArray(): void
    {
        $arr = range(0, 10000, 2); // Even numbers 0, 2, 4, ..., 10000
        $target = 5001;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(5000, $result); // Distance 1 vs 1, choose smaller
    }

    public function testLinearVsBinarySearch(): void
    {
        $testCases = [
            [[1, 3, 5, 7, 9], 6],
            [[2, 4, 6, 8, 10], 7],
            [[-10, -5, 0, 5, 10], -7],
            [[10], 7],
            [[1, 2, 3, 4, 5], 10]
        ];

        foreach ($testCases as [$arr, $target]) {
            $binaryResult = ClosestValueFinder::findClosestValue($arr, $target);
            $linearResult = ClosestValueFinder::findClosestValueLinear($arr, $target);
            $this->assertEquals($linearResult, $binaryResult, "Mismatch for target $target");
        }
    }

    public function testFindClosestValues(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $targets = [2, 4, 6, 8];
        $results = ClosestValueFinder::findClosestValues($arr, $targets);
        $expected = [1, 3, 5, 7]; // Closest to each target
        $this->assertEquals($expected, $results);
    }

    public function testValidateInputValid(): void
    {
        $this->assertTrue(ClosestValueFinder::validateInput([1, 2, 3, 4, 5]));
        $this->assertTrue(ClosestValueFinder::validateInput([-5, -1, 0, 3, 10]));
        $this->assertTrue(ClosestValueFinder::validateInput([42]));
    }

    public function testValidateInputInvalid(): void
    {
        $this->assertFalse(ClosestValueFinder::validateInput([])); // Empty
        $this->assertFalse(ClosestValueFinder::validateInput([5, 3, 1])); // Not sorted
        $this->assertFalse(ClosestValueFinder::validateInput([1, 2, 2, 3])); // Duplicates
        $this->assertFalse(ClosestValueFinder::validateInput([3, 1, 4, 1])); // Neither sorted nor unique
    }

    public function testEdgeCaseZero(): void
    {
        $arr = [-5, -1, 0, 1, 5];
        $target = 0;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(0, $result);
    }

    public function testEdgeCaseNearZero(): void
    {
        $arr = [-2, -1, 1, 2];
        $target = 0;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(-1, $result); // Both -1 and 1 are distance 1, choose smaller
    }

    public function testComplexTieBreaking(): void
    {
        $arr = [10, 20, 30, 40, 50];
        $target = 35;
        $result = ClosestValueFinder::findClosestValue($arr, $target);
        $this->assertEquals(30, $result); // Both 30 and 40 are distance 5, choose smaller
    }

    public function testPerformanceTest(): void
    {
        // Create a large sorted array
        $arr = range(0, 100000, 2); // 50,001 elements

        $targets = [50000, 99999, 1, 75000];

        $startTime = microtime(true);
        $results = ClosestValueFinder::findClosestValues($arr, $targets);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Verify results
        // 99999 maps to 99998 (closest even number)
        $this->assertEquals([50000, 99998, 0, 75000], $results);
    }

    public function testRandomizedTesting(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $size = rand(5, 100);
            $arr = [];

            // Generate sorted unique array
            $current = rand(-1000, -500);
            for ($j = 0; $j < $size; $j++) {
                $arr[] = $current;
                $current += rand(1, 10);
            }

            $target = rand($arr[0] - 100, $arr[$size - 1] + 100);

            $binaryResult = ClosestValueFinder::findClosestValue($arr, $target);
            $linearResult = ClosestValueFinder::findClosestValueLinear($arr, $target);

            $this->assertEquals($linearResult, $binaryResult, "Random test failed for target $target");
        }
    }

    public function testBoundaryConditions(): void
    {
        $arr = [1, 1000000000];

        // Test target at boundary
        $this->assertEquals(1, ClosestValueFinder::findClosestValue($arr, 1));
        $this->assertEquals(1000000000, ClosestValueFinder::findClosestValue($arr, 1000000000));

        // Test target in middle
        $this->assertEquals(1, ClosestValueFinder::findClosestValue($arr, 500000000)); // Both are same distance, choose smaller
    }

    public function testConsistencyCheck(): void
    {
        $arr = [1, 3, 5, 7, 9];
        $target = 6;

        // Multiple calls should return same result
        for ($i = 0; $i < 10; $i++) {
            $result = ClosestValueFinder::findClosestValue($arr, $target);
            $this->assertEquals(5, $result);
        }
    }

    public function testSequentialTargets(): void
    {
        $arr = [0, 10, 20, 30, 40, 50];

        $testCases = [
            [-5, 0],   // Before range
            [5, 0],    // Tie, choose smaller
            [15, 10],  // Tie, choose smaller
            [25, 20],  // Tie, choose smaller
            [35, 30],  // Tie, choose smaller
            [45, 40],  // Tie, choose smaller
            [55, 50]   // After range
        ];

        foreach ($testCases as [$target, $expected]) {
            $result = ClosestValueFinder::findClosestValue($arr, $target);
            $this->assertEquals($expected, $result, "Failed for target $target");
        }
    }
}
