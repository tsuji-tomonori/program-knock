import { LRUCache } from '../src/lruCache';

describe('LRUCache', () => {
  test('basic operations', () => {
    const cache = new LRUCache(2);

    cache.put(1, 1);
    cache.put(2, 2);
    expect(cache.get(1)).toBe(1);

    cache.put(3, 3); // evicts key 2
    expect(cache.get(2)).toBe(-1);
    expect(cache.get(3)).toBe(3);
    expect(cache.get(1)).toBe(1);

    cache.put(4, 4); // evicts key 3
    expect(cache.get(1)).toBe(1);
    expect(cache.get(3)).toBe(-1);
    expect(cache.get(4)).toBe(4);
  });

  test('update existing key', () => {
    const cache = new LRUCache(2);

    cache.put(1, 1);
    cache.put(2, 2);
    cache.put(1, 10); // update key 1
    expect(cache.get(1)).toBe(10);
    expect(cache.get(2)).toBe(2);
  });

  test('single capacity', () => {
    const cache = new LRUCache(1);

    cache.put(1, 1);
    expect(cache.get(1)).toBe(1);

    cache.put(2, 2);
    expect(cache.get(1)).toBe(-1);
    expect(cache.get(2)).toBe(2);
  });
});
