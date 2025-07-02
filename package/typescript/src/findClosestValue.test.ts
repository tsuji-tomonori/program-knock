import { findClosestValue } from './findClosestValue';

describe('findClosestValue', () => {
  test('sample 1', () => {
    // サンプル1
    // [1, 3, 5, 7, 9] において target=6 のとき最も近い値は 5 または 7。
    // ただし小さい方の 5 を返す。
    const arr = [1, 3, 5, 7, 9];
    const target = 6;
    expect(findClosestValue(arr, target)).toBe(5);
  });

  test('sample 2', () => {
    // サンプル2
    // [2, 4, 6, 8, 10] において target=7 のとき最も近い値は 6 または 8。
    // ただし小さい方の 6 を返す。
    const arr = [2, 4, 6, 8, 10];
    const target = 7;
    expect(findClosestValue(arr, target)).toBe(6);
  });

  test('sample 3', () => {
    // サンプル3
    // [1, 2, 3, 4, 5] において target=10 のとき最も近い値は 5。
    const arr = [1, 2, 3, 4, 5];
    const target = 10;
    expect(findClosestValue(arr, target)).toBe(5);
  });

  test('sample 4', () => {
    // サンプル4
    // [-10, -5, 0, 5, 10] において target=-7 のとき最も近い値は -5。
    const arr = [-10, -5, 0, 5, 10];
    const target = -7;
    expect(findClosestValue(arr, target)).toBe(-5);
  });

  test('sample 5', () => {
    // サンプル5
    // [10] において target=7 のとき配列に 10 しかないので 10 を返す。
    const arr = [10];
    const target = 7;
    expect(findClosestValue(arr, target)).toBe(10);
  });

  // --- 追加テストケース ---

  test('boundary value small', () => {
    // 非常に小さい target （制約の下限近く）
    // [-10, -5, 0, 5, 10] かつ target=-999999999 のとき最も近い値は -10
    const arr = [-10, -5, 0, 5, 10];
    const target = -999999999;
    expect(findClosestValue(arr, target)).toBe(-10);
  });

  test('boundary value large', () => {
    // 非常に大きい target （制約の上限近く）
    // [-10, -5, 0, 5, 10] かつ target=999999999 のとき最も近い値は 10
    const arr = [-10, -5, 0, 5, 10];
    const target = 999999999;
    expect(findClosestValue(arr, target)).toBe(10);
  });

  test('equidistant case', () => {
    // target がちょうど2つの要素と同距離の場合、小さい方を返すことを確認
    // [1, 3, 5, 7, 9], target=4 の場合、3 と 5 が同距離 => 小さい方の 3 を返す
    const arr = [1, 3, 5, 7, 9];
    const target = 4;
    expect(findClosestValue(arr, target)).toBe(3);
  });

  test('exact match', () => {
    // target と配列の要素が一致する場合は、そのままの値を返す
    // [1, 3, 5, 7, 9], target=5 のときは 5
    const arr = [1, 3, 5, 7, 9];
    const target = 5;
    expect(findClosestValue(arr, target)).toBe(5);
  });

  test('single element', () => {
    // 配列の長さが1の場合、常にその要素を返す
    // arr=[100], target=-100
    const arr = [100];
    const target = -100;
    expect(findClosestValue(arr, target)).toBe(100);
  });

  test('large data middle target', () => {
    /**
     * 0 から 99999 までの昇順ソート配列 (要素数=100000) を用意し、
     * 真ん中付近 (target=50000) を検索。
     */
    const arr = Array.from({ length: 100000 }, (_, i) => i); // [0, 1, 2, ..., 99999]
    const target = 50000;
    // 期待値は 50000 (ピッタリ存在)
    expect(findClosestValue(arr, target)).toBe(50000);
  });

  test('large data low target', () => {
    /**
     * -50000 から 49999 までの昇順ソート配列 (要素数=100000) を用意し、
     * 配列よりも小さい値 (target=-999999) を検索。
     */
    const arr = Array.from({ length: 100000 }, (_, i) => i - 50000); // [-50000, -49999, ..., 49999]
    const target = -999999;
    // 一番近いのは配列の先頭(-50000)
    expect(findClosestValue(arr, target)).toBe(-50000);
  });

  test('large data high target', () => {
    /**
     * -50000 から 49999 までの昇順ソート配列 (要素数=100000) を用意し、
     * 配列よりも大きい値 (target=999999) を検索。
     */
    const arr = Array.from({ length: 100000 }, (_, i) => i - 50000); // [-50000, -49999, ..., 49999]
    const target = 999999;
    // 一番近いのは配列の末尾(49999)
    expect(findClosestValue(arr, target)).toBe(49999);
  });
});
