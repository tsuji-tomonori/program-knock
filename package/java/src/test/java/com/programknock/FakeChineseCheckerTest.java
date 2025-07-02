package com.programknock;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

class FakeChineseCheckerTest {

    @Test
    void testSample1() {
        String text = "漢字";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSample2() {
        String text = "漢字テスト";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSample3() {
        String text = "中国語";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSample4() {
        String text = "123";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSample5() {
        String text = "漢123字";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testEmptyString() {
        String text = "";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSingleKanji() {
        String text = "漢";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testEnglishText() {
        String text = "hello";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testHiragana() {
        String text = "ひらがな";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testKatakana() {
        String text = "カタカナ";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testMixedChineseAndEnglish() {
        String text = "漢字ABC";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testSpaces() {
        String text = "漢 字";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testPunctuation() {
        String text = "漢字！";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testLongChineseText() {
        String text = "中華人民共和国";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testTraditionalChinese() {
        String text = "繁體中文";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testNullInput() {
        assertEquals("誤", FakeChineseChecker.isFakeChinese(null));
    }

    @Test
    void testSingleCharacterNumbers() {
        assertEquals("誤", FakeChineseChecker.isFakeChinese("1"));
        assertEquals("誤", FakeChineseChecker.isFakeChinese("0"));
        assertEquals("誤", FakeChineseChecker.isFakeChinese("9"));
    }

    @Test
    void testSingleSpace() {
        assertEquals("誤", FakeChineseChecker.isFakeChinese(" "));
    }

    @Test
    void testComplexMixed() {
        String text = "中文English日本語123";
        assertEquals("誤", FakeChineseChecker.isFakeChinese(text));
    }

    @Test
    void testChineseNumbers() {
        String text = "一二三四五";
        assertEquals("正", FakeChineseChecker.isFakeChinese(text));
    }
}
