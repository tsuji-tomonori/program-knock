<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\HitAndBlow;

class HitAndBlowTest
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
        $answer = [1, 2, 3, 4];
        $guess = [1, 3, 2, 5];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [1, 2]; // 1 Hit (position 0), 2 Blows (2 and 3)
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $answer = [5, 6, 7, 8];
        $guess = [8, 7, 6, 5];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 4]; // 0 Hits, 4 Blows (all numbers exist but in wrong positions)
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $answer = [3, 1, 4, 7];
        $guess = [3, 1, 4, 7];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [4, 0]; // 4 Hits (perfect match), 0 Blows
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase4(): void
    {
        $answer = [9, 8, 7, 6];
        $guess = [1, 2, 3, 4];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 0]; // No matches at all
        $this->assertEquals($expected, $result);
    }

    public function testSingleDigit(): void
    {
        $answer = [5];
        $guess = [5];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [1, 0]; // 1 Hit, 0 Blows
        $this->assertEquals($expected, $result);
    }

    public function testSingleDigitMiss(): void
    {
        $answer = [5];
        $guess = [3];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 0]; // No match
        $this->assertEquals($expected, $result);
    }

    public function testTwoDigitsSwapped(): void
    {
        $answer = [1, 2];
        $guess = [2, 1];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 2]; // 0 Hits, 2 Blows
        $this->assertEquals($expected, $result);
    }

    public function testPartialMatch(): void
    {
        $answer = [1, 2, 3];
        $guess = [1, 4, 5];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [1, 0]; // 1 Hit (position 0), 0 Blows
        $this->assertEquals($expected, $result);
    }

    public function testOnlyBlows(): void
    {
        $answer = [1, 2, 3, 4, 5];
        $guess = [5, 4, 1, 2, 3];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 5]; // 0 Hits, 5 Blows
        $this->assertEquals($expected, $result);
    }

    public function testMixedHitsBlows(): void
    {
        $answer = [1, 2, 3, 4, 5];
        $guess = [1, 5, 3, 2, 9];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [2, 2]; // 2 Hits (positions 0, 2), 2 Blows (2, 5)
        $this->assertEquals($expected, $result);
    }

    public function testLargerSequence(): void
    {
        $answer = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $guess = [9, 8, 7, 6, 5, 4, 3, 2, 1, 0];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 10]; // All numbers present but in reverse order
        $this->assertEquals($expected, $result);
    }

    public function testLargerSequencePartialHits(): void
    {
        $answer = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $guess = [0, 8, 7, 3, 5, 4, 6, 2, 1, 9];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [4, 6]; // 4 Hits (positions 0, 3, 6, 9), 6 Blows
        $this->assertEquals($expected, $result);
    }

    public function testIsGameClearWin(): void
    {
        $answer = [1, 2, 3, 4];
        $guess = [1, 2, 3, 4];
        $result = HitAndBlow::isGameClear($answer, $guess);
        $this->assertTrue($result);
    }

    public function testIsGameClearNotWin(): void
    {
        $answer = [1, 2, 3, 4];
        $guess = [1, 2, 3, 5];
        $result = HitAndBlow::isGameClear($answer, $guess);
        $this->assertFalse($result);
    }

    public function testGetScoreString(): void
    {
        $answer = [1, 2, 3, 4];
        $guess = [1, 3, 2, 5];
        $result = HitAndBlow::getScoreString($answer, $guess);
        $expected = "Hit: 1, Blow: 2";
        $this->assertEquals($expected, $result);
    }

    public function testCalculateMultiple(): void
    {
        $answer = [1, 2, 3, 4];
        $guesses = [
            [1, 2, 3, 4],  // Perfect
            [4, 3, 2, 1],  // All wrong positions
            [1, 5, 6, 7],  // One hit
            [8, 9, 0, 5]   // No matches
        ];

        $results = HitAndBlow::calculateMultiple($answer, $guesses);
        $expected = [
            [4, 0],
            [0, 4],
            [1, 0],
            [0, 0]
        ];

        $this->assertEquals($expected, $results);
    }

    public function testValidateInputValid(): void
    {
        $this->assertTrue(HitAndBlow::validateInput([1, 2, 3, 4], 4));
        $this->assertTrue(HitAndBlow::validateInput([0, 9, 5], 3));
        $this->assertTrue(HitAndBlow::validateInput([7], 1));
    }

    public function testValidateInputInvalidLength(): void
    {
        $this->assertFalse(HitAndBlow::validateInput([1, 2, 3], 4));
        $this->assertFalse(HitAndBlow::validateInput([1, 2, 3, 4, 5], 4));
    }

    public function testValidateInputDuplicates(): void
    {
        $this->assertFalse(HitAndBlow::validateInput([1, 2, 2, 3], 4));
        $this->assertFalse(HitAndBlow::validateInput([5, 5], 2));
    }

    public function testValidateInputOutOfRange(): void
    {
        $this->assertFalse(HitAndBlow::validateInput([1, 2, 3, 10], 4));
        $this->assertFalse(HitAndBlow::validateInput([-1, 2, 3, 4], 4));
    }

    public function testValidateInputNonInteger(): void
    {
        // PHP type hints should prevent this, but testing edge cases
        $this->assertTrue(HitAndBlow::validateInput([0, 1, 2, 3], 4));
    }

    public function testEdgeCaseAllZeros(): void
    {
        $answer = [0, 0, 0, 0]; // This violates "no duplicates" rule but testing edge case
        $guess = [0, 0, 0, 0];

        // According to rules, no duplicates should exist, but if they did:
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [4, 0]; // All positions match
        $this->assertEquals($expected, $result);
    }

    public function testEdgeCaseAllNines(): void
    {
        $answer = [9, 8, 7, 6];
        $guess = [9, 8, 7, 6];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [4, 0]; // Perfect match
        $this->assertEquals($expected, $result);
    }

    public function testComplexBlowCalculation(): void
    {
        $answer = [1, 2, 3, 4, 5, 6];
        $guess = [6, 5, 4, 3, 2, 1];
        $result = HitAndBlow::calculate($answer, $guess);
        $expected = [0, 6]; // All numbers present but all in wrong positions
        $this->assertEquals($expected, $result);
    }

    public function testMixedScenario(): void
    {
        $answer = [2, 4, 6, 8, 1];
        $guess = [2, 1, 3, 8, 4];
        $result = HitAndBlow::calculate($answer, $guess);
        // Position 0: Hit (2)
        // Position 1: Miss (4 vs 1)
        // Position 2: Miss (6 vs 3)
        // Position 3: Hit (8)
        // Position 4: Miss (1 vs 4)
        // Blows: 1 (from position 1) and 4 (from position 4) are in answer but wrong positions
        $expected = [2, 2]; // 2 Hits, 2 Blows
        $this->assertEquals($expected, $result);
    }

    public function testPerformanceTest(): void
    {
        $answer = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $guesses = [];

        // Generate 1000 random guesses
        for ($i = 0; $i < 1000; $i++) {
            $guess = range(0, 9);
            shuffle($guess);
            $guesses[] = $guess;
        }

        $startTime = microtime(true);
        $results = HitAndBlow::calculateMultiple($answer, $guesses);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertEquals(1000, count($results));

        // Each result should be valid
        foreach ($results as $result) {
            $this->assertEquals(2, count($result)); // [hits, blows]
            $this->assertTrue($result[0] >= 0 && $result[0] <= 10);
            $this->assertTrue($result[1] >= 0 && $result[1] <= 10);
            $this->assertTrue($result[0] + $result[1] <= 10);
        }
    }

    public function testRandomizedTesting(): void
    {
        // Test various random scenarios
        for ($i = 0; $i < 100; $i++) {
            $length = rand(3, 8);
            $answer = range(0, $length - 1);
            shuffle($answer);

            $guess = range(0, $length - 1);
            shuffle($guess);

            $result = HitAndBlow::calculate($answer, $guess);

            // Basic sanity checks
            $this->assertTrue($result[0] >= 0);
            $this->assertTrue($result[1] >= 0);
            $this->assertTrue($result[0] <= $length);
            $this->assertTrue($result[1] <= $length);
            $this->assertTrue($result[0] + $result[1] <= $length);
        }
    }

    public function testConsistencyCheck(): void
    {
        $answer = [1, 2, 3, 4];
        $guess = [4, 3, 2, 1];

        // Calculate multiple times to ensure consistency
        $result1 = HitAndBlow::calculate($answer, $guess);
        $result2 = HitAndBlow::calculate($answer, $guess);
        $result3 = HitAndBlow::calculate($answer, $guess);

        $this->assertEquals($result1, $result2);
        $this->assertEquals($result2, $result3);
    }

    public function testSymmetryProperty(): void
    {
        // If we swap answer and guess, results might be different due to position dependency
        $answer = [1, 2, 3, 4];
        $guess = [2, 1, 4, 3];

        $result1 = HitAndBlow::calculate($answer, $guess);
        $result2 = HitAndBlow::calculate($guess, $answer);

        // Results should be the same due to symmetry in this case
        $this->assertEquals($result1, $result2);
    }
}
