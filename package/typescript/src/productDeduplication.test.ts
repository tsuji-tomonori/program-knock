import { deduplicateProducts } from './productDeduplication';

describe('ProductDeduplication', () => {
  test('sample_1', () => {
    const products: Array<[string, number]> = [
      ["apple", 300],
      ["banana", 200],
      ["apple", 250],
      ["orange", 400]
    ];
    const expected = [["orange", 400], ["apple", 300], ["banana", 200]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('sample_2', () => {
    const products: Array<[string, number]> = [
      ["watch", 5000],
      ["watch", 5000],
      ["ring", 7000],
      ["ring", 6500]
    ];
    const expected = [["ring", 7000], ["watch", 5000]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('sample_3', () => {
    const products: Array<[string, number]> = [
      ["pen", 100],
      ["notebook", 200],
      ["eraser", 50],
      ["pen", 150]
    ];
    const expected = [["notebook", 200], ["pen", 150], ["eraser", 50]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('sample_4', () => {
    const products: Array<[string, number]> = [];
    const expected: Array<[string, number]> = [];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('sample_5', () => {
    const products: Array<[string, number]> = [
      ["bag", 1200],
      ["shoes", 3000],
      ["bag", 1000],
      ["hat", 2500]
    ];
    const expected = [["shoes", 3000], ["hat", 2500], ["bag", 1200]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('no_duplicates', () => {
    const products: Array<[string, number]> = [
      ["apple", 300],
      ["banana", 200],
      ["orange", 400]
    ];
    const expected = [["orange", 400], ["apple", 300], ["banana", 200]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('all_same_product', () => {
    const products: Array<[string, number]> = [
      ["apple", 100],
      ["apple", 300],
      ["apple", 200],
      ["apple", 250]
    ];
    const expected = [["apple", 300]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('same_price_different_products', () => {
    const products: Array<[string, number]> = [
      ["apple", 100],
      ["banana", 100],
      ["orange", 100]
    ];
    const result = deduplicateProducts(products);
    // All products should be present with same price
    expect(result).toHaveLength(3);
    expect(result.every(([, price]) => price === 100)).toBe(true);
    const productNames = new Set(result.map(([name]) => name));
    expect(productNames).toEqual(new Set(["apple", "banana", "orange"]));
  });

  test('single_product', () => {
    const products: Array<[string, number]> = [["apple", 300]];
    const expected = [["apple", 300]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('multiple_same_price_duplicates', () => {
    const products: Array<[string, number]> = [
      ["apple", 100],
      ["apple", 100],
      ["banana", 200]
    ];
    const expected = [["banana", 200], ["apple", 100]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });

  test('large_dataset_performance', () => {
    // Test with large dataset
    const products: Array<[string, number]> = [];
    for (let i = 0; i < 1000; i++) {
      const productName = `product_${i % 100}`; // 100 unique products
      const price = (i % 10) * 100 + 100; // Prices from 100 to 1000
      products.push([productName, price]);
    }

    const result = deduplicateProducts(products);

    // Should have 100 unique products
    expect(result).toHaveLength(100);

    // Should be sorted by price descending
    const prices = result.map(([, price]) => price);
    expect(prices).toEqual([...prices].sort((a, b) => b - a));
  });

  test('edge_case_high_prices', () => {
    const products: Array<[string, number]> = [
      ["luxury_item", 1000000],
      ["budget_item", 1],
      ["luxury_item", 999999],
    ];
    const expected = [["luxury_item", 1000000], ["budget_item", 1]];
    expect(deduplicateProducts(products)).toEqual(expected);
  });
});
