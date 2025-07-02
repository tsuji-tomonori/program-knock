package com.programknock;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

class LruCacheTest {

    @Test
    void testSample1() {
        LruCache cache = new LruCache(2);

        cache.put(1, 1);
        cache.put(2, 2);
        assertEquals(1, cache.get(1));
        cache.put(3, 3);
        assertEquals(-1, cache.get(2));
        cache.put(4, 4);
        assertEquals(-1, cache.get(1));
        assertEquals(3, cache.get(3));
        assertEquals(4, cache.get(4));
    }

    @Test
    void testCapacityOne() {
        LruCache cache = new LruCache(1);

        cache.put(1, 10);
        assertEquals(10, cache.get(1));

        cache.put(2, 20);
        assertEquals(-1, cache.get(1));
        assertEquals(20, cache.get(2));
    }

    @Test
    void testUpdateExistingKey() {
        LruCache cache = new LruCache(2);

        cache.put(1, 10);
        cache.put(2, 20);
        cache.put(1, 100);

        cache.put(3, 30);

        assertEquals(100, cache.get(1));
        assertEquals(-1, cache.get(2));
        assertEquals(30, cache.get(3));
    }

    @Test
    void testGetNonExistentKey() {
        LruCache cache = new LruCache(3);

        assertEquals(-1, cache.get(1));
        assertEquals(-1, cache.get(999));
    }

    @Test
    void testLRUOrderAfterGet() {
        LruCache cache = new LruCache(3);

        cache.put(1, 10);
        cache.put(2, 20);
        cache.put(3, 30);

        assertEquals(10, cache.get(1));

        cache.put(4, 40);

        assertEquals(10, cache.get(1));
        assertEquals(-1, cache.get(2));
        assertEquals(30, cache.get(3));
        assertEquals(40, cache.get(4));
    }

    @Test
    void testMixedOperations() {
        LruCache cache = new LruCache(3);

        cache.put(1, 1);
        cache.put(2, 2);
        cache.put(3, 3);
        cache.put(4, 4);

        assertEquals(-1, cache.get(1));
        assertEquals(2, cache.get(2));
        assertEquals(3, cache.get(3));
        assertEquals(4, cache.get(4));

        cache.put(2, 22);
        cache.put(5, 5);

        assertEquals(-1, cache.get(3));
        assertEquals(22, cache.get(2));
        assertEquals(4, cache.get(4));
        assertEquals(5, cache.get(5));
    }

    @Test
    void testRepeatedAccess() {
        LruCache cache = new LruCache(2);

        cache.put(1, 10);
        cache.put(2, 20);

        for (int i = 0; i < 5; i++) {
            assertEquals(10, cache.get(1));
        }

        cache.put(3, 30);
        assertEquals(10, cache.get(1));
        assertEquals(-1, cache.get(2));
        assertEquals(30, cache.get(3));
    }

    @Test
    void testLargeCapacity() {
        LruCache cache = new LruCache(1000);

        for (int i = 0; i < 1000; i++) {
            cache.put(i, i * 10);
        }

        for (int i = 0; i < 1000; i++) {
            assertEquals(i * 10, cache.get(i));
        }

        cache.put(1000, 10000);
        assertEquals(-1, cache.get(0));
        assertEquals(10, cache.get(1));
        assertEquals(10000, cache.get(1000));
    }

    @Test
    void testEmptyOperations() {
        LruCache cache = new LruCache(3);
        assertEquals(-1, cache.get(1));
        assertEquals(-1, cache.get(2));
        assertEquals(-1, cache.get(3));
    }

    @Test
    void testZeroReadsAfterEviction() {
        LruCache cache = new LruCache(2);

        cache.put(1, 1);
        cache.put(2, 2);
        cache.put(3, 3);

        assertEquals(-1, cache.get(1));
        assertEquals(2, cache.get(2));
        assertEquals(3, cache.get(3));
    }

    @Test
    void testCapacityBoundaryValues() {
        LruCache cache = new LruCache(1);

        cache.put(100, 1000);
        assertEquals(1000, cache.get(100));

        cache.put(200, 2000);
        assertEquals(-1, cache.get(100));
        assertEquals(2000, cache.get(200));

        cache.put(300, 3000);
        assertEquals(-1, cache.get(200));
        assertEquals(3000, cache.get(300));
    }
}
