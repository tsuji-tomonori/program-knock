class LRUCache:
    """
    LRU (Least Recently Used) Cache implementation.
    """

    def __init__(self, capacity: int):
        """
        Initialize LRU cache with given capacity.

        Args:
            capacity: Maximum capacity of the cache
        """
        self.capacity = capacity
        self.cache: dict[int, int] = {}  # key -> value mapping
        self.order: list[int] = []  # tracks the order of key usage (most recent at end)

    def get(self, key: int) -> int:
        """
        Get value for the key. If key exists, mark it as recently used.

        Args:
            key: Key to search for

        Returns:
            Value for the key, or -1 if not found
        """
        if key in self.cache:
            # Move key to end (most recently used)
            self.order.remove(key)
            self.order.append(key)
            return self.cache[key]
        return -1

    def put(self, key: int, value: int) -> None:
        """
        Put key-value pair into cache. If capacity is exceeded, remove LRU item.

        Args:
            key: Key to store
            value: Value to store
        """
        if key in self.cache:
            # Update existing key
            self.cache[key] = value
            # Move to end (most recently used)
            self.order.remove(key)
            self.order.append(key)
        else:
            # Add new key
            if len(self.cache) >= self.capacity:
                # Remove least recently used (first in order)
                lru_key = self.order.pop(0)
                del self.cache[lru_key]

            self.cache[key] = value
            self.order.append(key)
