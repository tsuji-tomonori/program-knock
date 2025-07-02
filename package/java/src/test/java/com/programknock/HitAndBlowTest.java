package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class HitAndBlowTest {

    @Test
    void testCase1() {
        assertEquals(new HitAndBlow.Result(1, 2),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3, 4), Arrays.asList(1, 3, 2, 5)));
    }

    @Test
    void testCase2() {
        assertEquals(new HitAndBlow.Result(0, 4),
            HitAndBlow.hitAndBlow(Arrays.asList(5, 6, 7, 8), Arrays.asList(8, 7, 6, 5)));
    }

    @Test
    void testCase3() {
        assertEquals(new HitAndBlow.Result(4, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(3, 1, 4, 7), Arrays.asList(3, 1, 4, 7)));
    }

    @Test
    void testCase4() {
        assertEquals(new HitAndBlow.Result(0, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(9, 8, 7, 6), Arrays.asList(1, 2, 3, 4)));
    }

    @Test
    void testSingleDigit() {
        assertEquals(new HitAndBlow.Result(1, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(5), Arrays.asList(5)));
        assertEquals(new HitAndBlow.Result(0, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(5), Arrays.asList(3)));
    }

    @Test
    void testTwoDigits() {
        assertEquals(new HitAndBlow.Result(0, 2),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2), Arrays.asList(2, 1)));
        assertEquals(new HitAndBlow.Result(1, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2), Arrays.asList(1, 3)));
        assertEquals(new HitAndBlow.Result(1, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2), Arrays.asList(3, 2)));
    }

    @Test
    void testPartialMatch() {
        assertEquals(new HitAndBlow.Result(1, 1),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3), Arrays.asList(1, 5, 2)));
        assertEquals(new HitAndBlow.Result(0, 1),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3), Arrays.asList(4, 1, 5)));
    }

    @Test
    void testAllHits() {
        assertEquals(new HitAndBlow.Result(4, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(0, 1, 2, 3), Arrays.asList(0, 1, 2, 3)));
        assertEquals(new HitAndBlow.Result(3, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(9, 8, 7), Arrays.asList(9, 8, 7)));
    }

    @Test
    void testAllBlows() {
        assertEquals(new HitAndBlow.Result(0, 3),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3), Arrays.asList(3, 1, 2)));
        assertEquals(new HitAndBlow.Result(0, 2),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2), Arrays.asList(2, 1)));
    }

    @Test
    void testNoMatches() {
        assertEquals(new HitAndBlow.Result(0, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3), Arrays.asList(4, 5, 6)));
        assertEquals(new HitAndBlow.Result(0, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(0, 9), Arrays.asList(1, 8)));
    }

    @Test
    void testMixedScenarios() {
        assertEquals(new HitAndBlow.Result(3, 1),
            HitAndBlow.hitAndBlow(Arrays.asList(1, 2, 3, 4, 5), Arrays.asList(1, 3, 3, 4, 2)));
    }

    @Test
    void testWithZeros() {
        assertEquals(new HitAndBlow.Result(0, 2),
            HitAndBlow.hitAndBlow(Arrays.asList(0, 1, 2), Arrays.asList(1, 0, 3)));
        assertEquals(new HitAndBlow.Result(2, 0),
            HitAndBlow.hitAndBlow(Arrays.asList(0, 0, 0), Arrays.asList(0, 0, 1)));
    }

    @Test
    void testLongerSequences() {
        assertEquals(new HitAndBlow.Result(2, 4),
            HitAndBlow.hitAndBlow(
                Arrays.asList(1, 2, 3, 4, 5, 6),
                Arrays.asList(6, 5, 3, 4, 2, 1)));
    }

    @Test
    void testReversed() {
        assertEquals(new HitAndBlow.Result(1, 4),
            HitAndBlow.hitAndBlow(
                Arrays.asList(1, 2, 3, 4, 5),
                Arrays.asList(5, 4, 3, 2, 1)));
    }

    @Test
    void testDuplicatesInAnswer() {
        assertEquals(new HitAndBlow.Result(1, 2),
            HitAndBlow.hitAndBlow(
                Arrays.asList(1, 1, 2, 3),
                Arrays.asList(1, 2, 1, 4)));
    }
}
