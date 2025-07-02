use std::collections::{HashMap, VecDeque};

pub struct LRUCache {
    capacity: usize,
    cache: HashMap<i32, i32>,
    order: VecDeque<i32>,
}

impl LRUCache {
    pub fn new(capacity: usize) -> Self {
        LRUCache {
            capacity,
            cache: HashMap::new(),
            order: VecDeque::new(),
        }
    }

    pub fn get(&mut self, key: i32) -> Option<i32> {
        if let Some(&value) = self.cache.get(&key) {
            self.move_to_front(key);
            Some(value)
        } else {
            None
        }
    }

    pub fn put(&mut self, key: i32, value: i32) {
        if self.cache.contains_key(&key) {
            self.cache.insert(key, value);
            self.move_to_front(key);
        } else {
            if self.cache.len() >= self.capacity {
                if let Some(lru_key) = self.order.pop_back() {
                    self.cache.remove(&lru_key);
                }
            }

            self.cache.insert(key, value);
            self.order.push_front(key);
        }
    }

    fn move_to_front(&mut self, key: i32) {
        if let Some(pos) = self.order.iter().position(|&k| k == key) {
            self.order.remove(pos);
        }
        self.order.push_front(key);
    }

    pub fn len(&self) -> usize {
        self.cache.len()
    }

    pub fn is_empty(&self) -> bool {
        self.cache.is_empty()
    }
}
