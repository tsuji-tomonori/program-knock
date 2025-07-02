import { countInRange } from './countInRange';

describe('countInRange', () => {
  test('basic case', () => {
    const arr = [1, 3, 5, 7, 9, 11];
    const left = 4;
    const right = 8;
    expect(countInRange(arr, left, right)).toBe(2); // 5, 7 の2つ
  });

  test('negative numbers', () => {
    const arr = [-5, -3, -1, 2, 4, 6, 8, 10];
    const left = -2;
    const right = 5;
    expect(countInRange(arr, left, right)).toBe(3); // -1, 2, 4 の3つ
  });

  test('out of range', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 6;
    const right = 10;
    expect(countInRange(arr, left, right)).toBe(0); // 範囲に該当なし
  });

  test('partial range', () => {
    const arr = [10, 20, 30, 40, 50];
    const left = 15;
    const right = 45;
    expect(countInRange(arr, left, right)).toBe(3); // 20, 30, 40 の3つ
  });

  test('exact boundaries', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 2;
    const right = 4;
    expect(countInRange(arr, left, right)).toBe(3); // 2, 3, 4 の3つ
  });

  test('single element match', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 3;
    const right = 3;
    expect(countInRange(arr, left, right)).toBe(1); // 3 の1つ
  });

  test('single element no match', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 6;
    const right = 6;
    expect(countInRange(arr, left, right)).toBe(0); // なし
  });

  test('all elements', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 0;
    const right = 10;
    expect(countInRange(arr, left, right)).toBe(5); // 全ての要素
  });

  test('empty range before', () => {
    const arr = [5, 6, 7, 8, 9];
    const left = 1;
    const right = 3;
    expect(countInRange(arr, left, right)).toBe(0); // なし
  });

  test('empty range after', () => {
    const arr = [1, 2, 3, 4, 5];
    const left = 10;
    const right = 15;
    expect(countInRange(arr, left, right)).toBe(0); // なし
  });

  test('duplicates', () => {
    const arr = [1, 2, 2, 2, 3, 4, 4, 5];
    const left = 2;
    const right = 4;
    expect(countInRange(arr, left, right)).toBe(6); // 2,2,2,3,4,4 の6つ
  });

  test('negative range', () => {
    const arr = [-10, -5, -3, -1, 0, 2, 5];
    const left = -6;
    const right = -2;
    expect(countInRange(arr, left, right)).toBe(2); // -5, -3 の2つ
  });

  test('single element array', () => {
    const arr = [42];
    expect(countInRange(arr, 40, 45)).toBe(1);
    expect(countInRange(arr, 50, 60)).toBe(0);
    expect(countInRange(arr, 42, 42)).toBe(1);
  });

  test('large range', () => {
    const arr: number[] = [];
    for (let i = 0; i < 100; i += 2) {
      arr.push(i);
    } // [0, 2, 4, 6, ..., 98]
    const left = 10;
    const right = 20;
    expect(countInRange(arr, left, right)).toBe(6); // 10,12,14,16,18,20 の6つ
  });

  test('boundary edge cases', () => {
    const arr = [1, 3, 5, 7, 9];
    // Range exactly matches first element
    expect(countInRange(arr, 1, 1)).toBe(1);
    // Range exactly matches last element
    expect(countInRange(arr, 9, 9)).toBe(1);
    // Range from first to last
    expect(countInRange(arr, 1, 9)).toBe(5);
  });
});
