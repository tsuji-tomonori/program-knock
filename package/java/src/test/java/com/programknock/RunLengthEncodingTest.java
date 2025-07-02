package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class RunLengthEncodingTest {

    @Test
    void testSample1() {
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 3),
            new RunLengthEncoding.CharCount("b", 2),
            new RunLengthEncoding.CharCount("c", 1),
            new RunLengthEncoding.CharCount("d", 4)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding("aaabbcdddd"));
    }

    @Test
    void testSample2() {
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("c", 1)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding("abc"));
    }

    @Test
    void testSample3() {
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 7)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding("aaaaaaa"));
    }

    @Test
    void testAlternatingCharacters() {
        String s = "abababab";
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding(s));
    }

    @Test
    void testSingleCharacterString() {
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("x", 1)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding("x"));
    }

    @Test
    void testLongSequence() {
        StringBuilder sb = new StringBuilder();
        for (int i = 0; i < 1000; i++) {
            sb.append("a");
        }
        String s = sb.toString();

        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 1000)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding(s));
    }

    @Test
    void testVaryingLengths() {
        String s = "aabbbaabbbbaaaaaabbbaaaaabbbbbbbaaaaaaaaabbbbbbbbbbaaaaaaaaaaaa";
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 2),
            new RunLengthEncoding.CharCount("b", 3),
            new RunLengthEncoding.CharCount("a", 2),
            new RunLengthEncoding.CharCount("b", 4),
            new RunLengthEncoding.CharCount("a", 6),
            new RunLengthEncoding.CharCount("b", 3),
            new RunLengthEncoding.CharCount("a", 5),
            new RunLengthEncoding.CharCount("b", 7),
            new RunLengthEncoding.CharCount("a", 9),
            new RunLengthEncoding.CharCount("b", 10),
            new RunLengthEncoding.CharCount("a", 12)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding(s));
    }

    @Test
    void testComplexPattern() {
        String s = "aabccdeeeeffggghhhiiijjjkkklll";
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 2),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("c", 2),
            new RunLengthEncoding.CharCount("d", 1),
            new RunLengthEncoding.CharCount("e", 4),
            new RunLengthEncoding.CharCount("f", 2),
            new RunLengthEncoding.CharCount("g", 3),
            new RunLengthEncoding.CharCount("h", 3),
            new RunLengthEncoding.CharCount("i", 3),
            new RunLengthEncoding.CharCount("j", 3),
            new RunLengthEncoding.CharCount("k", 3),
            new RunLengthEncoding.CharCount("l", 3)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding(s));
    }

    @Test
    void testAllUniqueCharacters() {
        String s = "abcdefghij";
        List<RunLengthEncoding.CharCount> expected = Arrays.asList(
            new RunLengthEncoding.CharCount("a", 1),
            new RunLengthEncoding.CharCount("b", 1),
            new RunLengthEncoding.CharCount("c", 1),
            new RunLengthEncoding.CharCount("d", 1),
            new RunLengthEncoding.CharCount("e", 1),
            new RunLengthEncoding.CharCount("f", 1),
            new RunLengthEncoding.CharCount("g", 1),
            new RunLengthEncoding.CharCount("h", 1),
            new RunLengthEncoding.CharCount("i", 1),
            new RunLengthEncoding.CharCount("j", 1)
        );
        assertEquals(expected, RunLengthEncoding.runLengthEncoding(s));
    }

    @Test
    void testEmptyString() {
        assertEquals(new ArrayList<>(), RunLengthEncoding.runLengthEncoding(""));
    }
}
