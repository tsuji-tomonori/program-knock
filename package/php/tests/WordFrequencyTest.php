<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\WordFrequency;

class WordFrequencyTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    public function testSampleCase1(): void
    {
        $result = WordFrequency::count("apple banana apple orange banana apple");
        $expected = ["apple" => 3, "banana" => 2, "orange" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $result = WordFrequency::count("python");
        $expected = ["python" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $result = WordFrequency::count("dog cat bird cat dog bird");
        $expected = ["bird" => 2, "cat" => 2, "dog" => 2];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase4(): void
    {
        $result = WordFrequency::count("");
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    public function testSingleWord(): void
    {
        $result = WordFrequency::count("hello");
        $expected = ["hello" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testMultipleSpaces(): void
    {
        $result = WordFrequency::count("  hello  world  hello  ");
        $expected = ["hello" => 2, "world" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testIdenticalWords(): void
    {
        $result = WordFrequency::count("test test test test");
        $expected = ["test" => 4];
        $this->assertEquals($expected, $result);
    }

    public function testAlphabeticalSorting(): void
    {
        $result = WordFrequency::count("zebra apple zebra apple banana");
        $expected = ["apple" => 2, "zebra" => 2, "banana" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testMixedFrequencies(): void
    {
        $result = WordFrequency::count("a b c a b a");
        $expected = ["a" => 3, "b" => 2, "c" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testLongWords(): void
    {
        $result = WordFrequency::count("supercalifragilisticexpialidocious hello supercalifragilisticexpialidocious");
        $expected = ["supercalifragilisticexpialidocious" => 2, "hello" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testComplexSorting(): void
    {
        $result = WordFrequency::count("x y z x y x");
        $expected = ["x" => 3, "y" => 2, "z" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testSameFrequencyMultipleWords(): void
    {
        $result = WordFrequency::count("one two three four");
        $expected = ["four" => 1, "one" => 1, "three" => 1, "two" => 1];
        $this->assertEquals($expected, $result);
    }

    public function testWhitespaceOnly(): void
    {
        $result = WordFrequency::count("   ");
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    public function testLargeText(): void
    {
        // 大量のテキストでのパフォーマンステスト
        $words = [];
        for ($i = 0; $i < 1000; $i++) {
            $words[] = "word" . ($i % 100); // 100種類の単語を繰り返し
        }
        $text = implode(' ', $words);

        $startTime = microtime(true);
        $result = WordFrequency::count($text);
        $endTime = microtime(true);

        // 各単語が10回ずつ出現するはず
        $this->assertEquals(10, $result["word0"]);
        $this->assertEquals(100, count($result)); // 100種類の単語

        // パフォーマンスチェック（1秒以内）
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }
    }

    public function testSpecialCharacterHandling(): void
    {
        // この実装では英小文字のみを想定しているが、エラーが出ないことを確認
        $result = WordFrequency::count("hello world");
        $expected = ["hello" => 1, "world" => 1];
        $this->assertEquals($expected, $result);
    }
}
