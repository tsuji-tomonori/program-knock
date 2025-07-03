<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\RunLengthEncoding;

class RunLengthEncodingTest
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

    private function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Expected true, but got false");
        }
    }

    private function assertLessThan($expected, $actual, $message = ''): void
    {
        if ($actual >= $expected) {
            throw new \AssertionError($message ?: "Expected $actual to be less than $expected");
        }
    }

    public function testSampleCase1(): void
    {
        $input = "aaabbcdddd";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 3], ["b", 2], ["c", 1], ["d", 4]];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $input = "abc";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 1], ["b", 1], ["c", 1]];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $input = "aaaaaaa";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 7]];
        $this->assertEquals($expected, $result);
    }

    public function testEmptyString(): void
    {
        $input = "";
        $result = RunLengthEncoding::encode($input);
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    public function testSingleCharacter(): void
    {
        $input = "a";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 1]];
        $this->assertEquals($expected, $result);
    }

    public function testTwoSameCharacters(): void
    {
        $input = "aa";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 2]];
        $this->assertEquals($expected, $result);
    }

    public function testTwoDifferentCharacters(): void
    {
        $input = "ab";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 1], ["b", 1]];
        $this->assertEquals($expected, $result);
    }

    public function testAlternatingPattern(): void
    {
        $input = "ababab";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 1], ["b", 1], ["a", 1], ["b", 1], ["a", 1], ["b", 1]];
        $this->assertEquals($expected, $result);
    }

    public function testLongRuns(): void
    {
        $input = str_repeat("a", 1000) . str_repeat("b", 500) . str_repeat("c", 200);
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 1000], ["b", 500], ["c", 200]];
        $this->assertEquals($expected, $result);
    }

    public function testAllDifferentCharacters(): void
    {
        $input = "abcdefghijklmnopqrstuvwxyz";
        $result = RunLengthEncoding::encode($input);

        $expected = [];
        for ($i = 0; $i < 26; $i++) {
            $expected[] = [chr(ord('a') + $i), 1];
        }

        $this->assertEquals($expected, $result);
    }

    public function testComplexPattern(): void
    {
        $input = "aabbbaabbbaaaa";
        $result = RunLengthEncoding::encode($input);
        $expected = [["a", 2], ["b", 3], ["a", 2], ["b", 3], ["a", 4]];
        $this->assertEquals($expected, $result);
    }

    public function testDecode(): void
    {
        $encoded = [["a", 3], ["b", 2], ["c", 1], ["d", 4]];
        $result = RunLengthEncoding::decode($encoded);
        $expected = "aaabbcdddd";
        $this->assertEquals($expected, $result);
    }

    public function testDecodeEmpty(): void
    {
        $encoded = [];
        $result = RunLengthEncoding::decode($encoded);
        $expected = "";
        $this->assertEquals($expected, $result);
    }

    public function testDecodeSingleChar(): void
    {
        $encoded = [["x", 5]];
        $result = RunLengthEncoding::decode($encoded);
        $expected = "xxxxx";
        $this->assertEquals($expected, $result);
    }

    public function testEncodeDecodeRoundTrip(): void
    {
        $inputs = [
            "aaabbcdddd",
            "abc",
            "aaaaaaa",
            "abcdefg",
            "aabbccddee",
            "aaaabbbbccccdddd"
        ];

        foreach ($inputs as $input) {
            $encoded = RunLengthEncoding::encode($input);
            $decoded = RunLengthEncoding::decode($encoded);
            $this->assertEquals($input, $decoded);
        }
    }

    public function testCalculateEncodedSize(): void
    {
        $encoded = [["a", 3], ["b", 2], ["c", 1]];
        $size = RunLengthEncoding::calculateEncodedSize($encoded);
        $this->assertEquals(6, $size); // 3 entries * 2 = 6
    }

    public function testCalculateCompressionRatio(): void
    {
        $original = "aaaaaaa"; // 7 characters
        $encoded = [["a", 7]];  // 2 units (char + count)

        $ratio = RunLengthEncoding::calculateCompressionRatio($original, $encoded);
        $this->assertEquals(2/7, $ratio);
    }

    public function testCompressionRatioNoCompression(): void
    {
        $original = "abcdefg"; // 7 characters
        $encoded = [["a", 1], ["b", 1], ["c", 1], ["d", 1], ["e", 1], ["f", 1], ["g", 1]]; // 14 units

        $ratio = RunLengthEncoding::calculateCompressionRatio($original, $encoded);
        $this->assertEquals(2.0, $ratio); // Larger than original
    }

    public function testCompressionRatioEmptyString(): void
    {
        $original = "";
        $encoded = [];

        $ratio = RunLengthEncoding::calculateCompressionRatio($original, $encoded);
        $this->assertEquals(0.0, $ratio);
    }

    public function testLargeInput(): void
    {
        // Create a large input with good compression potential
        $input = str_repeat("a", 10000) . str_repeat("b", 10000) . str_repeat("c", 10000);

        $startTime = microtime(true);
        $result = RunLengthEncoding::encode($input);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $expected = [["a", 10000], ["b", 10000], ["c", 10000]];
        $this->assertEquals($expected, $result);

        // Check compression ratio
        $ratio = RunLengthEncoding::calculateCompressionRatio($input, $result);
        $this->assertLessThan(0.01, $ratio); // Very good compression
    }

    public function testWorstCaseCompression(): void
    {
        // Alternating pattern - worst case for RLE
        $input = str_repeat("ab", 1000);
        $result = RunLengthEncoding::encode($input);

        // Should result in 2000 entries (each char appears once)
        $this->assertCount(2000, $result);

        $ratio = RunLengthEncoding::calculateCompressionRatio($input, $result);
        $this->assertEquals(2.0, $ratio); // Double the size
    }

    public function testMixedCompressionScenario(): void
    {
        // Some parts compress well, others don't
        $input = str_repeat("a", 100) . "bcdefg" . str_repeat("h", 50) . "ijklmn";
        $result = RunLengthEncoding::encode($input);

        $this->assertCount(14, $result);

        // Check first group
        $this->assertEquals(["a", 100], $result[0]);

        // Check individual chars
        $this->assertEquals(["b", 1], $result[1]);
        $this->assertEquals(["c", 1], $result[2]);

        // Check second group
        $this->assertEquals(["h", 50], $result[7]);

        // Check last few chars
        $this->assertEquals(["i", 1], $result[8]);
        $this->assertEquals(["n", 1], $result[13]);
    }

    public function testEdgeCaseVeryLongRun(): void
    {
        $input = str_repeat("x", 100000);
        $result = RunLengthEncoding::encode($input);

        $expected = [["x", 100000]];
        $this->assertEquals($expected, $result);

        // Excellent compression ratio
        $ratio = RunLengthEncoding::calculateCompressionRatio($input, $result);
        $this->assertEquals(0.00002, $ratio);
    }

    public function testRandomPattern(): void
    {
        $input = "aaabbaaaaacccdddddeeeee";
        $result = RunLengthEncoding::encode($input);
        $expected = [
            ["a", 3], ["b", 2], ["a", 5], ["c", 3],
            ["d", 5], ["e", 5]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testDecodeComplexPattern(): void
    {
        $encoded = [
            ["z", 1], ["y", 2], ["x", 3], ["w", 4], ["v", 5]
        ];
        $result = RunLengthEncoding::decode($encoded);
        $expected = "zyyxxxwwwwvvvvv";
        $this->assertEquals($expected, $result);
    }

    public function testConsistencyWithMultipleInputs(): void
    {
        $testCases = [
            "",
            "a",
            "aa",
            "ab",
            "abc",
            "aab",
            "abb",
            "aaa",
            "bbb",
            "aaabbb",
            "abcabc",
            str_repeat("test", 100)
        ];

        foreach ($testCases as $input) {
            $encoded = RunLengthEncoding::encode($input);
            $decoded = RunLengthEncoding::decode($encoded);
            $this->assertEquals($input, $decoded, "Failed for input: $input");
        }
    }

    public function testPerformanceStressTest(): void
    {
        // Create various patterns to stress test
        $patterns = [
            str_repeat("a", 50000),                    // All same
            str_repeat("ab", 25000),                   // Alternating
            str_repeat("aaa", 16666) . "b",           // Mostly same with one different
            str_repeat("abcd", 12500),                 // 4-char pattern
        ];

        foreach ($patterns as $i => $pattern) {
            $startTime = microtime(true);
            $encoded = RunLengthEncoding::encode($pattern);
            $decoded = RunLengthEncoding::decode($encoded);
            $endTime = microtime(true);

            if ($endTime - $startTime >= 0.5) {
                throw new \AssertionError("Performance test failed for pattern $i: took " . ($endTime - $startTime) . " seconds");
            }

            $this->assertEquals($pattern, $decoded);
        }
    }
}
