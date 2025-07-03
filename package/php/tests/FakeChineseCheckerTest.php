<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\FakeChineseChecker;

class FakeChineseCheckerTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    public function testSampleCase1(): void
    {
        $text = '漢字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testSampleCase2(): void
    {
        $text = '漢字テスト';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testSampleCase3(): void
    {
        $text = '中国語';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testSampleCase4(): void
    {
        $text = '123';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testSampleCase5(): void
    {
        $text = '漢123字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testEmptyString(): void
    {
        $text = '';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testSingleKanji(): void
    {
        $text = '字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testMultipleKanji(): void
    {
        $text = '日本語学習者';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testHiragana(): void
    {
        $text = 'ひらがな';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testKatakana(): void
    {
        $text = 'カタカナ';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testMixedKanjiHiragana(): void
    {
        $text = '漢字ひらがな';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testMixedKanjiKatakana(): void
    {
        $text = '漢字カタカナ';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testEnglishLetters(): void
    {
        $text = 'ABC';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testMixedKanjiEnglish(): void
    {
        $text = '漢字ABC';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testNumbers(): void
    {
        $text = '12345';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testSymbols(): void
    {
        $text = '!@#$%';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testMixedKanjiSymbols(): void
    {
        $text = '漢字!';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testSpaces(): void
    {
        $text = '漢字 中国';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testTraditionalChinese(): void
    {
        $text = '繁體字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testSimplifiedChinese(): void
    {
        $text = '简体字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testComplexKanji(): void
    {
        $text = '鬱憂鬱';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testCommonKanji(): void
    {
        $text = '一二三四五六七八九十';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testKanjiWithPunctuation(): void
    {
        $text = '漢字。';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testKanjiWithComma(): void
    {
        $text = '漢字、中国語';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testLongKanjiString(): void
    {
        $text = '中華人民共和国日本国大韓民国朝鮮民主主義人民共和国';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testMixedLanguagesComplex(): void
    {
        $text = '漢字ひらがなカタカナABC123!@#';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testPersonNames(): void
    {
        // 日本人の名前（漢字のみ）
        $text = '田中太郎';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testPlaceNames(): void
    {
        // 地名（漢字のみ）
        $text = '東京都大阪府';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testMixedSingleCharacters(): void
    {
        $text = '漢a字';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testUnicodeKanji(): void
    {
        // より高度なUnicode漢字
        $text = '龍鳳麒麟';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testRareKanji(): void
    {
        // 稀な漢字
        $text = '鬱蠱';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('正', $result);
    }

    public function testFullWidthNumbers(): void
    {
        $text = '漢字１２３';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testFullWidthAlphabets(): void
    {
        $text = '漢字ＡＢＣ';
        $result = FakeChineseChecker::check($text);
        $this->assertEquals('誤', $result);
    }

    public function testPerformanceTest(): void
    {
        // 大量の漢字文字列でのパフォーマンステスト
        $text = str_repeat('漢字中国語日本語韓国語', 100);

        $startTime = microtime(true);
        $result = FakeChineseChecker::check($text);
        $endTime = microtime(true);

        // パフォーマンスチェック
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertEquals('正', $result);
    }

    public function testMixedPerformanceTest(): void
    {
        // 混合文字列でのパフォーマンステスト
        $text = str_repeat('漢字あいうえおカタカナABC123', 50);

        $startTime = microtime(true);
        $result = FakeChineseChecker::check($text);
        $endTime = microtime(true);

        // パフォーマンスチェック
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertEquals('誤', $result);
    }
}
