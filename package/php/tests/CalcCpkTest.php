<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\CalcCpk;

class CalcCpkTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected $expected, but got $actual");
        }
    }

    private function assertIsFloat($value, $message = ''): void
    {
        if (!is_float($value)) {
            throw new \AssertionError($message ?: "Expected float, but got " . gettype($value));
        }
    }

    private function assertGreaterThan($expected, $actual, $message = ''): void
    {
        if ($actual <= $expected) {
            throw new \AssertionError($message ?: "Expected value > $expected, but got $actual");
        }
    }

    private function assertLessThan($expected, $actual, $message = ''): void
    {
        if ($actual >= $expected) {
            throw new \AssertionError($message ?: "Expected value < $expected, but got $actual");
        }
    }

    private function expectException(string $exceptionClass): void
    {
        // This is handled by the test methods that use it
    }

    public function testSampleCase(): void
    {
        $result = CalcCpk::calcCpk(10.0, 2.0, [4.5, 5.0, 4.8, 5.2, 5.5]);
        $this->assertEquals(2.626, $result);
    }

    public function testMinimalDataSet(): void
    {
        try {
            CalcCpk::calcCpk(5.0, 1.0, [3.0]);
            throw new \AssertionError("Expected InvalidArgumentException");
        } catch (\InvalidArgumentException $e) {
            // Expected
        }
    }

    public function testPerfectlyCentered(): void
    {
        try {
            CalcCpk::calcCpk(10.0, 0.0, [5.0, 5.0, 5.0, 5.0, 5.0]);
            throw new \AssertionError("Expected InvalidArgumentException");
        } catch (\InvalidArgumentException $e) {
            // Expected
        }
    }

    public function testExtremelyOffCenter(): void
    {
        $result = CalcCpk::calcCpk(10.0, 0.0, [9.8, 9.9, 9.7, 9.8, 9.9]);
        $this->assertEquals(0.717, $result);
    }

    public function testMinimalSpecWidth(): void
    {
        $result = CalcCpk::calcCpk(1.001, 1.000, [1.0005, 1.0003, 1.0007, 1.0004, 1.0006]);
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testMaximalSpecWidth(): void
    {
        $result = CalcCpk::calcCpk(1000.0, -1000.0, [0.0, 10.0, -5.0, 15.0, -10.0]);
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testNegativeValues(): void
    {
        $result = CalcCpk::calcCpk(0.0, -10.0, [-2.0, -3.0, -1.0, -4.0, -2.5]);
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testRoundingBoundary(): void
    {
        $result = CalcCpk::calcCpk(10.0, 0.0, [5.0, 5.001, 4.999, 5.0005, 4.9995]);
        $this->assertIsFloat($result);
    }

    public function testVerySmallStandardDeviation(): void
    {
        $result = CalcCpk::calcCpk(2.0, 1.0, [1.5, 1.500001, 1.499999, 1.500002, 1.499998]);
        $this->assertIsFloat($result);
        $this->assertGreaterThan(100, $result);
    }

    public function testVeryLargeStandardDeviation(): void
    {
        $result = CalcCpk::calcCpk(100.0, -100.0, [-50.0, 80.0, -30.0, 60.0, -70.0]);
        $this->assertIsFloat($result);
        $this->assertLessThan(1, $result);
    }

    public function testLargeDataSet(): void
    {
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = 5.0 + (random_int(-100, 100) / 100.0);
        }

        $startTime = microtime(true);
        $result = CalcCpk::calcCpk(10.0, 0.0, $data);
        $endTime = microtime(true);

        $this->assertIsFloat($result);
        $this->assertLessThan(1.0, $endTime - $startTime);
    }

    public function testSuperLargeDataSet(): void
    {
        $data = [];
        for ($i = 0; $i < 10000; $i++) {
            $data[] = 5.0 + (random_int(-200, 200) / 100.0);
        }

        $startTime = microtime(true);
        $result = CalcCpk::calcCpk(10.0, 0.0, $data);
        $endTime = microtime(true);

        $this->assertIsFloat($result);
        $this->assertLessThan(5.0, $endTime - $startTime);
    }

    public function testTheoreticalComparison(): void
    {
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = 3.0 + (random_int(-100, 100) / 200.0);
        }

        $result = CalcCpk::calcCpk(6.0, 0.0, $data);
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testRandomDataConsistency(): void
    {
        $data = [];
        for ($i = 0; $i < 50; $i++) {
            $data[] = 10.0 + (random_int(-500, 500) / 100.0);
        }

        $result1 = CalcCpk::calcCpk(20.0, 0.0, $data);
        $result2 = CalcCpk::calcCpk(20.0, 0.0, $data);

        $this->assertEquals($result1, $result2);
    }

    public function testEmptyDataThrowsException(): void
    {
        try {
            CalcCpk::calcCpk(10.0, 0.0, []);
            throw new \AssertionError("Expected InvalidArgumentException");
        } catch (\InvalidArgumentException $e) {
            // Expected
        }
    }
}
