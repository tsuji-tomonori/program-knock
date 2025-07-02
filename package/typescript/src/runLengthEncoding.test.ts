import { runLengthEncoding } from './runLengthEncoding';

describe('runLengthEncoding', () => {
  // サンプルテストケース
  test('sample 1', () => {
    // "aaabbcdddd" -> [("a", 3), ("b", 2), ("c", 1), ("d", 4)]
    expect(runLengthEncoding("aaabbcdddd")).toEqual([["a", 3], ["b", 2], ["c", 1], ["d", 4]]);
  });

  test('sample 2', () => {
    // "abc" -> [("a", 1), ("b", 1), ("c", 1)]
    expect(runLengthEncoding("abc")).toEqual([["a", 1], ["b", 1], ["c", 1]]);
  });

  test('sample 3', () => {
    // "aaaaaaa" -> [("a", 7)]
    expect(runLengthEncoding("aaaaaaa")).toEqual([["a", 7]]);
  });

  // その他のテストケース
  test('alternating characters', () => {
    // 交互に並ぶ文字: 各文字が1回ずつ連続する
    const s = "abababab";
    const expected = [
      ["a", 1],
      ["b", 1],
      ["a", 1],
      ["b", 1],
      ["a", 1],
      ["b", 1],
      ["a", 1],
      ["b", 1],
    ];
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('single character max length', () => {
    // 境界値テスト: 最大文字数 100,000 の同一文字
    const s = "a".repeat(100000);
    const expected = [["a", 100000]];
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('non repeating characters', () => {
    // 連続していない全て異なる文字（アルファベット順）
    const s = "abcdefghijklmnopqrstuvwxyz";
    const expected = s.split('').map(char => [char, 1] as [string, number]);
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('varied repetition', () => {
    // 各文字の繰り返し回数が異なる
    const s = "aabbbccccddddd";
    const expected = [["a", 2], ["b", 3], ["c", 4], ["d", 5]];
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('mixed groups', () => {
    // 連続グループが混在するケース
    const s = "zzzzzyxx";
    const expected = [["z", 5], ["y", 1], ["x", 2]];
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('increasing group sizes', () => {
    // グループのサイズが順に増加するケース
    const s = "abbcccddddeeeee";
    const expected = [["a", 1], ["b", 2], ["c", 3], ["d", 4], ["e", 5]];
    expect(runLengthEncoding(s)).toEqual(expected);
  });

  test('minimum length string', () => {
    // 境界値テスト: 最小の文字列（長さ1）
    const s = "a";
    const expected = [["a", 1]];
    expect(runLengthEncoding(s)).toEqual(expected);
  });
});
