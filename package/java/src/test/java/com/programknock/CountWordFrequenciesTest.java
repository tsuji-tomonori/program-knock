package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class CountWordFrequenciesTest {

    @Test
    void testBasic() {
        String text = "apple banana apple orange banana apple";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("apple", 3);
        expected.put("banana", 2);
        expected.put("orange", 1);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testSingleWord() {
        String text = "python";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("python", 1);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testTiedFrequencies() {
        String text = "dog cat bird cat dog bird";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("bird", 2);
        expected.put("cat", 2);
        expected.put("dog", 2);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testEmptyString() {
        String text = "";
        Map<String, Integer> expected = new LinkedHashMap<>();

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testRepeatedSingleWord() {
        List<String> words = new ArrayList<>();
        for (int i = 0; i < 100; i++) {
            words.add("test");
        }
        String text = String.join(" ", words);

        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("test", 100);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testWordsOfVariousLengths() {
        String text = "a bb ccc a bb";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("a", 2);
        expected.put("bb", 2);
        expected.put("ccc", 1);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testAlphabeticalOrderForSameFrequency() {
        String text = "cat dog cat dog fish bird fish bird";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("bird", 2);
        expected.put("cat", 2);
        expected.put("dog", 2);
        expected.put("fish", 2);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testVaryingFrequencies() {
        String text = "apple apple banana apple banana orange lemon";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("apple", 3);
        expected.put("banana", 2);
        expected.put("lemon", 1);
        expected.put("orange", 1);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testOneLetterWords() {
        String text = "a b c a b a";
        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("a", 3);
        expected.put("b", 2);
        expected.put("c", 1);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testManyWordsCase() {
        List<String> words = new ArrayList<>();
        for (int i = 0; i < 10; i++) words.add("apple");
        for (int i = 0; i < 5; i++) words.add("banana");
        for (int i = 0; i < 5; i++) words.add("cherry");
        for (int i = 0; i < 3; i++) words.add("banana");
        for (int i = 0; i < 2; i++) words.add("apple");
        for (int i = 0; i < 2; i++) words.add("cherry");

        String text = String.join(" ", words);

        Map<String, Integer> expected = new LinkedHashMap<>();
        expected.put("apple", 12);
        expected.put("banana", 8);
        expected.put("cherry", 7);

        assertEquals(expected, CountWordFrequencies.countWordFrequencies(text));
    }

    @Test
    void testNullInput() {
        assertEquals(new LinkedHashMap<>(), CountWordFrequencies.countWordFrequencies(null));
    }

    @Test
    void testWhitespaceOnly() {
        assertEquals(new LinkedHashMap<>(), CountWordFrequencies.countWordFrequencies("   "));
    }
}
