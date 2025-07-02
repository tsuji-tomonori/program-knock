package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static org.junit.jupiter.api.Assertions.*;

class RemoveDuplicateCustomersTest {

    @Test
    void testSample1() {
        List<Integer> customerIds = Arrays.asList(101, 202, 303, 101, 404, 202, 505);
        List<Integer> expected = Arrays.asList(101, 202, 303, 404, 505);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testSample2() {
        List<Integer> customerIds = Arrays.asList(1, 2, 3, 4, 5, 6, 7, 8, 9);
        List<Integer> expected = Arrays.asList(1, 2, 3, 4, 5, 6, 7, 8, 9);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testAllSame() {
        List<Integer> customerIds = Arrays.asList(123, 123, 123, 123, 123);
        List<Integer> expected = Arrays.asList(123);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testEmptyList() {
        List<Integer> customerIds = new ArrayList<>();
        List<Integer> expected = new ArrayList<>();
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testSingleElement() {
        List<Integer> customerIds = Arrays.asList(42);
        List<Integer> expected = Arrays.asList(42);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testNegativeValues() {
        List<Integer> customerIds = Arrays.asList(-1, -2, -1, 3, -2, 3);
        List<Integer> expected = Arrays.asList(-1, -2, 3);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testOrderPreservation() {
        List<Integer> customerIds = Arrays.asList(3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5);
        List<Integer> expected = Arrays.asList(3, 1, 4, 5, 9, 2, 6);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testInterleavedDuplicates() {
        List<Integer> customerIds = Arrays.asList(1, 2, 1, 3, 2, 4, 1, 3, 5);
        List<Integer> expected = Arrays.asList(1, 2, 3, 4, 5);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testBoundaryValues() {
        List<Integer> customerIds = Arrays.asList(-1000000, 1000000, 0, -1000000, 1000000);
        List<Integer> expected = Arrays.asList(-1000000, 1000000, 0);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testConsecutiveDuplicates() {
        List<Integer> customerIds = Arrays.asList(1, 1, 1, 2, 2, 3, 3, 3, 3);
        List<Integer> expected = Arrays.asList(1, 2, 3);
        assertEquals(expected, RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds));
    }

    @Test
    void testLargeInput() {
        List<Integer> customerIds = new ArrayList<>();
        for (int i = 0; i < 10000; i++) {
            customerIds.add(i % 100);
        }

        List<Integer> result = RemoveDuplicateCustomers.removeDuplicateCustomers(customerIds);

        assertEquals(100, result.size());
        for (int i = 0; i < 100; i++) {
            assertEquals(Integer.valueOf(i), result.get(i));
        }
    }
}
