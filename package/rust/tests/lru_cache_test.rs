use program_knock::lru_cache::*;

#[test]
fn test_lru_cache_basic_operations() {
    let mut cache = LRUCache::new(2);
    cache.put(1, 1);
    cache.put(2, 2);
    assert_eq!(cache.get(1), Some(1));
    cache.put(3, 3);
    assert_eq!(cache.get(2), None);
    assert_eq!(cache.get(3), Some(3));
    assert_eq!(cache.get(1), Some(1));
}

#[test]
fn test_lru_cache_single_capacity() {
    let mut cache = LRUCache::new(1);
    cache.put(1, 1);
    assert_eq!(cache.get(1), Some(1));
    cache.put(2, 2);
    assert_eq!(cache.get(1), None);
    assert_eq!(cache.get(2), Some(2));
}

#[test]
fn test_lru_cache_get_put_operations() {
    let mut cache = LRUCache::new(3);
    cache.put(1, 10);
    cache.put(2, 20);
    cache.put(3, 30);
    assert_eq!(cache.get(2), Some(20));
    cache.put(4, 40);
    assert_eq!(cache.get(1), None);
    assert_eq!(cache.get(3), Some(30));
    assert_eq!(cache.get(2), Some(20));
    assert_eq!(cache.get(4), Some(40));
}

#[test]
fn test_lru_cache_nonexistent_key() {
    let mut cache = LRUCache::new(2);
    assert_eq!(cache.get(1), None);
    cache.put(1, 1);
    assert_eq!(cache.get(2), None);
}

#[test]
fn test_lru_cache_duplicate_put() {
    let mut cache = LRUCache::new(2);
    cache.put(1, 1);
    cache.put(1, 10);
    assert_eq!(cache.get(1), Some(10));
    cache.put(2, 2);
    cache.put(3, 3);
    assert_eq!(cache.get(1), None);
    assert_eq!(cache.get(2), Some(2));
}

#[test]
fn test_lru_cache_capacity_limit() {
    let mut cache = LRUCache::new(2);
    cache.put(1, 1);
    cache.put(2, 2);
    cache.put(3, 3);
    assert_eq!(cache.get(1), None);
    assert_eq!(cache.get(2), Some(2));
    assert_eq!(cache.get(3), Some(3));
}

#[test]
fn test_lru_cache_large_capacity() {
    let mut cache = LRUCache::new(100);
    for i in 1..=100 {
        cache.put(i, i * 10);
    }
    for i in 1..=100 {
        assert_eq!(cache.get(i), Some(i * 10));
    }
}

#[test]
fn test_lru_cache_negative_keys_values() {
    let mut cache = LRUCache::new(2);
    cache.put(-1, -10);
    cache.put(-2, -20);
    assert_eq!(cache.get(-1), Some(-10));
    assert_eq!(cache.get(-2), Some(-20));
}

#[test]
fn test_lru_cache_zero_values() {
    let mut cache = LRUCache::new(2);
    cache.put(1, 0);
    cache.put(0, 1);
    assert_eq!(cache.get(1), Some(0));
    assert_eq!(cache.get(0), Some(1));
}

#[test]
fn test_lru_cache_performance() {
    let mut cache = LRUCache::new(1000);
    for i in 0..10000 {
        cache.put(i % 500, i);
        if i % 100 == 0 {
            cache.get(i % 500);
        }
    }
    assert!(cache.len() <= 1000);
}
