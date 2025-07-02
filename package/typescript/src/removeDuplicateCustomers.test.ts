import { removeDuplicateCustomers } from './removeDuplicateCustomers';

describe('RemoveDuplicateCustomers', () => {
  test('sample1', () => {
    // サンプルテストケース1:
    // [101, 202, 303, 101, 404, 202, 505] → [101, 202, 303, 404, 505]
    const customerIds = [101, 202, 303, 101, 404, 202, 505];
    const expected = [101, 202, 303, 404, 505];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('sample2', () => {
    // サンプルテストケース2:
    // [1, 2, 3, 4, 5, 6, 7, 8, 9] → [1, 2, 3, 4, 5, 6, 7, 8, 9]
    const customerIds = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    const expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('sample3', () => {
    // サンプルテストケース3:
    // [42, 42, 42, 42, 42] → [42]
    const customerIds = [42, 42, 42, 42, 42];
    const expected = [42];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('sample4', () => {
    // サンプルテストケース4:
    // [] → []
    const customerIds: number[] = [];
    const expected: number[] = [];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('sample5', () => {
    // サンプルテストケース5:
    // [500, -1, 500, -1, 200, 300, 200, -100] → [500, -1, 200, 300, -100]
    const customerIds = [500, -1, 500, -1, 200, 300, 200, -100];
    const expected = [500, -1, 200, 300, -100];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('single_element', () => {
    // 境界値テスト: 要素が1つだけの場合
    const customerIds = [0];
    const expected = [0];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('all_unique_elements', () => {
    // 境界値テスト: 全ての要素が一意な場合、そのままの順序で返す
    const customerIds = [10, 20, 30, 40, 50];
    const expected = [10, 20, 30, 40, 50];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('all_duplicate_negative', () => {
    // 境界値テスト: 全てが同じ負の値の場合
    const customerIds = [-5, -5, -5, -5];
    const expected = [-5];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('boundary_values', () => {
    // 境界値テスト: 顧客IDが仕様の最小値と最大値の場合
    const customerIds = [-1000000, 1000000, -1000000, 1000000, 0];
    const expected = [-1000000, 1000000, 0];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('interleaved_duplicates', () => {
    // 重複が交互に現れるパターンで、順序が正しく保持されるかを検証
    // 例: [3, 1, 2, 3, 2, 1, 4, 5, 4] → [3, 1, 2, 4, 5]
    const customerIds = [3, 1, 2, 3, 2, 1, 4, 5, 4];
    const expected = [3, 1, 2, 4, 5];
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });

  test('large_input', () => {
    // 境界値テスト: 非常に大きなリスト(重複を含む)でも正しく動作するかを検証
    // 例: 0~9999 の各数字を2回ずつ並べ、最初の出現順序を保持
    // 0から9999までを2回ずつ連結したリスト
    const customerIds = [...Array.from({ length: 10000 }, (_, i) => i), ...Array.from({ length: 10000 }, (_, i) => i)];
    const expected = Array.from({ length: 10000 }, (_, i) => i);
    expect(removeDuplicateCustomers(customerIds)).toEqual(expected);
  });
});
