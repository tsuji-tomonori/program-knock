<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\CustomerDataDeduplication;

class CustomerDataDeduplicationTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
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
        $customerIds = [101, 202, 303, 101, 404, 202, 505];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [101, 202, 303, 404, 505];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $customerIds = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $customerIds = [42, 42, 42, 42, 42];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [42];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase4(): void
    {
        $customerIds = [];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase5(): void
    {
        $customerIds = [500, -1, 500, -1, 200, 300, 200, -100];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [500, -1, 200, 300, -100];
        $this->assertEquals($expected, $result);
    }

    public function testSingleElement(): void
    {
        $customerIds = [999];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [999];
        $this->assertEquals($expected, $result);
    }

    public function testTwoElementsNoDuplicates(): void
    {
        $customerIds = [100, 200];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [100, 200];
        $this->assertEquals($expected, $result);
    }

    public function testTwoElementsDuplicates(): void
    {
        $customerIds = [100, 100];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [100];
        $this->assertEquals($expected, $result);
    }

    public function testNegativeNumbers(): void
    {
        $customerIds = [-1, -2, -1, -3, -2, -4];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [-1, -2, -3, -4];
        $this->assertEquals($expected, $result);
    }

    public function testZeroIncluded(): void
    {
        $customerIds = [0, 1, 0, 2, 0, 3];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [0, 1, 2, 3];
        $this->assertEquals($expected, $result);
    }

    public function testLargeNumbers(): void
    {
        $customerIds = [1000000, -1000000, 1000000, 999999, -1000000];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [1000000, -1000000, 999999];
        $this->assertEquals($expected, $result);
    }

    public function testOrderPreservation(): void
    {
        $customerIds = [5, 3, 8, 1, 3, 5, 9, 1, 2];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [5, 3, 8, 1, 9, 2];
        $this->assertEquals($expected, $result);
    }

    public function testAlternatingPattern(): void
    {
        $customerIds = [1, 2, 1, 2, 1, 2];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [1, 2];
        $this->assertEquals($expected, $result);
    }

    public function testConsecutiveDuplicates(): void
    {
        $customerIds = [1, 1, 2, 2, 2, 3, 3, 4];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [1, 2, 3, 4];
        $this->assertEquals($expected, $result);
    }

    public function testLargeDataset(): void
    {
        $customerIds = [];

        // Generate 10000 customer IDs with duplicates
        for ($i = 0; $i < 10000; $i++) {
            $customerIds[] = $i % 1000; // This creates duplicates
        }

        $startTime = microtime(true);
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Should have exactly 1000 unique IDs (0-999)
        $this->assertCount(1000, $result);

        // Verify order preservation (0, 1, 2, ..., 999)
        for ($i = 0; $i < 1000; $i++) {
            $this->assertEquals($i, $result[$i]);
        }
    }

    public function testRandomPattern(): void
    {
        $customerIds = [99, 1, 99, 55, 1, 33, 55, 77, 33, 22];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [99, 1, 55, 33, 77, 22];
        $this->assertEquals($expected, $result);
    }

    public function testVeryLargeInput(): void
    {
        $customerIds = [];

        // Generate 100000 customer IDs with many duplicates
        for ($i = 0; $i < 100000; $i++) {
            $customerIds[] = $i % 50; // Only 50 unique values
        }

        $result = CustomerDataDeduplication::removeDuplicates($customerIds);

        // Should have exactly 50 unique IDs
        $this->assertCount(50, $result);

        // Verify first appearance order (0, 1, 2, ..., 49)
        for ($i = 0; $i < 50; $i++) {
            $this->assertEquals($i, $result[$i]);
        }
    }

    public function testMixedPositiveNegativeZero(): void
    {
        $customerIds = [0, -5, 10, 0, -5, 20, 10, -10, 0];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [0, -5, 10, 20, -10];
        $this->assertEquals($expected, $result);
    }

    public function testExtremeValues(): void
    {
        $customerIds = [1000000, -1000000, 0, 1000000, -1000000, 500000, -500000];
        $result = CustomerDataDeduplication::removeDuplicates($customerIds);
        $expected = [1000000, -1000000, 0, 500000, -500000];
        $this->assertEquals($expected, $result);
    }
}
