from src.lru_cache import LRUCache


class TestLRUCache:
    def test_sample_1(self):
        cache = LRUCache(2)

        # 1. put(1, 1) - キー1に値1を格納
        cache.put(1, 1)

        # 2. put(2, 2) - キー2に値2を格納
        cache.put(2, 2)

        # 3. get(1) → 出力: 1
        assert cache.get(1) == 1

        # 4. put(3, 3) - キー3に値3を格納（キー2が削除される）
        cache.put(3, 3)

        # 5. get(2) → 出力: -1
        assert cache.get(2) == -1

        # 6. put(4, 4) - キー4に値4を格納（キー1が削除される）
        cache.put(4, 4)

        # 7. get(1) → 出力: -1
        assert cache.get(1) == -1

        # 8. get(3) → 出力: 3
        assert cache.get(3) == 3

        # 9. get(4) → 出力: 4
        assert cache.get(4) == 4

    def test_capacity_one(self):
        cache = LRUCache(1)

        cache.put(1, 10)
        assert cache.get(1) == 10

        cache.put(2, 20)
        assert cache.get(1) == -1  # evicted
        assert cache.get(2) == 20

    def test_update_existing_key(self):
        cache = LRUCache(2)

        cache.put(1, 10)
        cache.put(2, 20)
        cache.put(1, 100)  # Update existing key

        assert cache.get(1) == 100
        assert cache.get(2) == 20

    def test_get_nonexistent_key(self):
        cache = LRUCache(2)

        assert cache.get(1) == -1

        cache.put(1, 10)
        assert cache.get(2) == -1

    def test_lru_order_after_get(self):
        cache = LRUCache(2)

        cache.put(1, 10)
        cache.put(2, 20)

        # Access key 1, making it most recently used
        cache.get(1)

        # Add new key, should evict key 2 (not key 1)
        cache.put(3, 30)

        assert cache.get(1) == 10  # still there
        assert cache.get(2) == -1  # evicted
        assert cache.get(3) == 30

    def test_lru_order_after_put_update(self):
        cache = LRUCache(2)

        cache.put(1, 10)
        cache.put(2, 20)

        # Update key 1, making it most recently used
        cache.put(1, 100)

        # Add new key, should evict key 2 (not key 1)
        cache.put(3, 30)

        assert cache.get(1) == 100  # still there, updated value
        assert cache.get(2) == -1  # evicted
        assert cache.get(3) == 30

    def test_larger_capacity(self):
        cache = LRUCache(3)

        cache.put(1, 10)
        cache.put(2, 20)
        cache.put(3, 30)

        # All should be present
        assert cache.get(1) == 10
        assert cache.get(2) == 20
        assert cache.get(3) == 30

        # Add fourth item, should evict first
        cache.put(4, 40)

        assert cache.get(1) == -1  # evicted
        assert cache.get(2) == 20
        assert cache.get(3) == 30
        assert cache.get(4) == 40

    def test_mixed_operations(self):
        cache = LRUCache(3)

        cache.put(1, 1)
        cache.put(2, 2)
        cache.put(3, 3)
        cache.put(4, 4)  # evicts key 1

        assert cache.get(1) == -1
        assert cache.get(2) == 2
        assert cache.get(3) == 3
        assert cache.get(4) == 4

        cache.put(2, 22)  # update key 2
        cache.put(5, 5)  # evicts key 3

        assert cache.get(3) == -1
        assert cache.get(2) == 22
        assert cache.get(4) == 4
        assert cache.get(5) == 5

    def test_repeated_access(self):
        cache = LRUCache(2)

        cache.put(1, 10)
        cache.put(2, 20)

        # Repeatedly access key 1
        for _ in range(5):
            assert cache.get(1) == 10

        # Add new key, should still evict key 2
        cache.put(3, 30)

        assert cache.get(1) == 10
        assert cache.get(2) == -1
        assert cache.get(3) == 30

    def test_zero_operations(self):
        cache = LRUCache(5)

        # No operations performed
        assert cache.get(1) == -1
