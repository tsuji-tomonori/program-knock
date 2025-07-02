package com.programknock;

import java.util.*;

public class LruCache {

    private final int capacity;
    private final Map<Integer, Integer> cache;
    private final List<Integer> order;

    public LruCache(int capacity) {
        this.capacity = capacity;
        this.cache = new HashMap<>();
        this.order = new ArrayList<>();
    }

    public int get(int key) {
        if (cache.containsKey(key)) {
            order.remove(Integer.valueOf(key));
            order.add(key);
            return cache.get(key);
        }
        return -1;
    }

    public void put(int key, int value) {
        if (cache.containsKey(key)) {
            cache.put(key, value);
            order.remove(Integer.valueOf(key));
            order.add(key);
        } else {
            if (cache.size() >= capacity) {
                int lruKey = order.remove(0);
                cache.remove(lruKey);
            }
            cache.put(key, value);
            order.add(key);
        }
    }
}
