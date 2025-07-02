/**
 * LRU (Least Recently Used) Cache implementation.
 */
export class LRUCache {
  private capacity: number;
  private cache: Map<number, number>;
  private order: number[];

  /**
   * Initialize LRU cache with given capacity.
   *
   * @param capacity - Maximum capacity of the cache
   */
  constructor(capacity: number) {
    this.capacity = capacity;
    this.cache = new Map<number, number>();
    this.order = []; // tracks the order of key usage (most recent at end)
  }

  /**
   * Get value for the key. If key exists, mark it as recently used.
   *
   * @param key - Key to search for
   * @returns Value for the key, or -1 if not found
   */
  get(key: number): number {
    if (this.cache.has(key)) {
      // Move key to end (most recently used)
      const index = this.order.indexOf(key);
      this.order.splice(index, 1);
      this.order.push(key);
      return this.cache.get(key)!;
    }
    return -1;
  }

  /**
   * Put key-value pair into cache. If capacity is exceeded, remove LRU item.
   *
   * @param key - Key to store
   * @param value - Value to store
   */
  put(key: number, value: number): void {
    if (this.cache.has(key)) {
      // Update existing key
      this.cache.set(key, value);
      // Move to end (most recently used)
      const index = this.order.indexOf(key);
      this.order.splice(index, 1);
      this.order.push(key);
    } else {
      // Add new key
      if (this.cache.size >= this.capacity) {
        // Remove least recently used (first in order)
        const lruKey = this.order.shift()!;
        this.cache.delete(lruKey);
      }

      this.cache.set(key, value);
      this.order.push(key);
    }
  }
}
