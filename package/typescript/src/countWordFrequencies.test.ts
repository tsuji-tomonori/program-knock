import { countWordFrequencies } from './countWordFrequencies';

describe('countWordFrequencies', () => {
  test('basic test', () => {
    const text = "apple banana apple orange banana apple";
    const result = countWordFrequencies(text);

    // Check the order and values
    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["apple", 3],
      ["banana", 2],
      ["orange", 1]
    ]);
  });

  test('single word', () => {
    const text = "python";
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([["python", 1]]);
  });

  test('tied frequencies', () => {
    const text = "dog cat bird cat dog bird";
    const result = countWordFrequencies(text);

    // All have frequency 2, so should be sorted alphabetically
    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["bird", 2],
      ["cat", 2],
      ["dog", 2]
    ]);
  });

  test('empty string', () => {
    const text = "";
    const result = countWordFrequencies(text);
    expect(result.size).toBe(0);
  });

  test('repeated single word', () => {
    // 100回 "test" が繰り返される
    const text = Array(100).fill("test").join(" ");
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([["test", 100]]);
  });

  test('words of various lengths', () => {
    const text = "a bb ccc a bb";
    // 出現回数
    // a   -> 2
    // bb  -> 2
    // ccc -> 1
    // 順番は出現回数が多い順、同数ならキーの辞書順
    // a と bb は同数なので "a" < "bb"
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["a", 2],
      ["bb", 2],
      ["ccc", 1]
    ]);
  });

  test('alphabetical order for same frequency', () => {
    // 同じ回数の場合のアルファベット順確認
    const text = "cat dog cat dog fish bird fish bird";
    // 出現回数
    // cat  -> 2
    // dog  -> 2
    // fish -> 2
    // bird -> 2
    // 全て 2 なのでキーを辞書順に並べる
    // bird < cat < dog < fish
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["bird", 2],
      ["cat", 2],
      ["dog", 2],
      ["fish", 2]
    ]);
  });

  test('varying frequencies', () => {
    const text = "apple apple banana apple banana orange lemon";
    // 出現回数
    // apple  -> 3
    // banana -> 2
    // orange -> 1
    // lemon  -> 1
    // 同じ回数の orange と lemon は辞書順で lemon が先
    // よって apple(3), banana(2), lemon(1), orange(1)
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["apple", 3],
      ["banana", 2],
      ["lemon", 1],
      ["orange", 1]
    ]);
  });

  test('one letter words', () => {
    const text = "a b c a b a";
    // 出現回数
    // a -> 3
    // b -> 2
    // c -> 1
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["a", 3],
      ["b", 2],
      ["c", 1]
    ]);
  });

  test('many words case', () => {
    // 長めのテキスト（複数の単語を混在）
    const words = [
      ...Array(10).fill("apple"),
      ...Array(5).fill("banana"),
      ...Array(5).fill("cherry"),
      ...Array(3).fill("banana"),
      ...Array(2).fill("apple"),
      ...Array(2).fill("cherry")
    ];
    const text = words.join(" ");

    // 出現回数
    // apple  -> 10 + 2 = 12
    // banana -> 5 + 3 = 8
    // cherry -> 5 + 2 = 7
    // 順番は apple(12), banana(8), cherry(7)
    const result = countWordFrequencies(text);

    const entries = Array.from(result.entries());
    expect(entries).toEqual([
      ["apple", 12],
      ["banana", 8],
      ["cherry", 7]
    ]);
  });
});
