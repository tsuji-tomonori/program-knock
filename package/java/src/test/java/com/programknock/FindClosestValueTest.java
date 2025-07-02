package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static org.junit.jupiter.api.Assertions.*;

class FindClosestValueTest {

    @Test
    void testSample1() {
        List<Integer> arr = Arrays.asList(1, 3, 5, 7, 9);
        int target = 6;
        assertEquals(5, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testSample2() {
        List<Integer> arr = Arrays.asList(2, 4, 6, 8, 10);
        int target = 7;
        assertEquals(6, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testSample3() {
        List<Integer> arr = Arrays.asList(1, 2, 3, 4, 5);
        int target = 10;
        assertEquals(5, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testSample4() {
        List<Integer> arr = Arrays.asList(-10, -5, 0, 5, 10);
        int target = -7;
        assertEquals(-5, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testSample5() {
        List<Integer> arr = Arrays.asList(10);
        int target = 7;
        assertEquals(10, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testBoundaryValueSmall() {
        List<Integer> arr = Arrays.asList(-10, -5, 0, 5, 10);
        int target = -999999999;
        assertEquals(-10, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testBoundaryValueLarge() {
        List<Integer> arr = Arrays.asList(-10, -5, 0, 5, 10);
        int target = 999999999;
        assertEquals(10, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testEquidistantCase() {
        List<Integer> arr = Arrays.asList(1, 3, 5, 7, 9);
        int target = 4;
        assertEquals(3, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testExactMatch() {
        List<Integer> arr = Arrays.asList(1, 3, 5, 7, 9);
        int target = 5;
        assertEquals(5, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testSingleElement() {
        List<Integer> arr = Arrays.asList(100);
        int target = -100;
        assertEquals(100, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testLargeDataMiddleTarget() {
        List<Integer> arr = IntStream.range(0, 100000)
            .boxed()
            .collect(Collectors.toList());
        int target = 50000;
        assertEquals(50000, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testLargeDataLowTarget() {
        List<Integer> arr = IntStream.range(-50000, 50000)
            .boxed()
            .collect(Collectors.toList());
        int target = -999999;
        assertEquals(-50000, FindClosestValue.findClosestValue(arr, target));
    }

    @Test
    void testLargeDataHighTarget() {
        List<Integer> arr = IntStream.range(-50000, 50000)
            .boxed()
            .collect(Collectors.toList());
        int target = 999999;
        assertEquals(49999, FindClosestValue.findClosestValue(arr, target));
    }
}
