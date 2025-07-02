package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static org.junit.jupiter.api.Assertions.*;

class CountInRangeTest {

    @Test
    void testBasicCase() {
        List<Integer> arr = Arrays.asList(1, 3, 5, 7, 9, 11);
        assertEquals(2, CountInRange.countInRange(arr, 4, 8));
    }

    @Test
    void testNegativeNumbers() {
        List<Integer> arr = Arrays.asList(-5, -3, -1, 2, 4, 6, 8, 10);
        assertEquals(3, CountInRange.countInRange(arr, -2, 5));
    }

    @Test
    void testOutOfRange() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(0, CountInRange.countInRange(arr, 6, 10));
    }

    @Test
    void testPartialRange() {
        List<Integer> arr = Arrays.asList(10, 20, 30, 40, 50);
        assertEquals(3, CountInRange.countInRange(arr, 15, 45));
    }

    @Test
    void testExactBoundaries() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(3, CountInRange.countInRange(arr, 2, 4));
    }

    @Test
    void testSingleElementMatch() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(1, CountInRange.countInRange(arr, 3, 3));
    }

    @Test
    void testSingleElementNoMatch() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(0, CountInRange.countInRange(arr, 6, 6));
    }

    @Test
    void testAllElements() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(5, CountInRange.countInRange(arr, -100, 100));
    }

    @Test
    void testNoneElements() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(0, CountInRange.countInRange(arr, -100, -50));
    }

    @Test
    void testSingleElementArray() {
        List<Integer> arr = Arrays.asList(5);
        assertEquals(1, CountInRange.countInRange(arr, 5, 5));
        assertEquals(1, CountInRange.countInRange(arr, 0, 10));
        assertEquals(0, CountInRange.countInRange(arr, 6, 10));
    }

    @Test
    void testLargeRange() {
        List<Integer> arr = IntStream.rangeClosed(1, 1000).boxed().collect(Collectors.toList());
        assertEquals(500, CountInRange.countInRange(arr, 250, 749));
    }

    @Test
    void testDuplicateElements() {
        List<Integer> arr = Arrays.asList(1, 2, 2, 2, 3, 4, 5);
        assertEquals(3, CountInRange.countInRange(arr, 2, 2));
    }

    @Test
    void testBoundaryAtStart() {
        List<Integer> arr = Arrays.asList(10, 20, 30, 40, 50);
        assertEquals(3, CountInRange.countInRange(arr, 10, 30));
    }

    @Test
    void testBoundaryAtEnd() {
        List<Integer> arr = Arrays.asList(10, 20, 30, 40, 50);
        assertEquals(3, CountInRange.countInRange(arr, 30, 50));
    }

    @Test
    void testEmptyArray() {
        List<Integer> arr = new ArrayList<>();
        assertEquals(0, CountInRange.countInRange(arr, 0, 10));
    }

    @Test
    void testLargeBoundaryValues() {
        List<Integer> arr = Arrays.asList(-1000000, 0, 1000000);
        assertEquals(3, CountInRange.countInRange(arr, -1000000, 1000000));
        assertEquals(1, CountInRange.countInRange(arr, -1, 1));
    }
}
