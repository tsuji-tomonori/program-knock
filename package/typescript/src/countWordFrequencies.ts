/**
 * 入力されたテキスト中の各単語の出現回数をカウントする関数.
 *
 * @param text - 空白区切りの単語を含む文字列
 * @returns 各単語をキーとし、その出現回数を値とするMap（出現回数降順、同数の場合は辞書順）
 */
export function countWordFrequencies(text: string): Map<string, number> {
  // 空文字の場合は空Mapを返す
  if (!text) {
    return new Map();
  }

  // スペース区切りで単語をリスト化
  const words = text.split(' ');

  // 単語の出現回数をカウント
  const frequencyMap = new Map<string, number>();
  for (const word of words) {
    const currentCount = frequencyMap.get(word) || 0;
    frequencyMap.set(word, currentCount + 1);
  }

  // ソート: 出現回数(降順), 単語(昇順)
  // entries() で [単語, 出現回数] の配列を取り出し、
  // key 引数で (-出現回数, 単語) の順にソート
  const sortedEntries = Array.from(frequencyMap.entries()).sort((a, b) => {
    const [wordA, countA] = a;
    const [wordB, countB] = b;

    // 出現回数で降順ソート
    if (countA !== countB) {
      return countB - countA;
    }

    // 出現回数が同じ場合は単語で昇順ソート
    return wordA.localeCompare(wordB);
  });

  // ソート結果をもとに再度Map化（挿入順が保持される）
  return new Map(sortedEntries);
}
