/**
 * Hit & Blow の判定を行う関数
 *
 * @param answer - 正解の数値のリスト
 * @param guess - プレイヤーの推測リスト (同じ長さ)
 * @returns [Hit数, Blow数]
 */
export function hitAndBlow(answer: number[], guess: number[]): [number, number] {
  // Count hits (same position and same value)
  const hits = answer.reduce((count, val, i) => count + (val === guess[i] ? 1 : 0), 0);

  // Count total matches (regardless of position)
  const answerCounter = new Map<number, number>();
  const guessCounter = new Map<number, number>();

  // Count occurrences in answer
  for (const num of answer) {
    answerCounter.set(num, (answerCounter.get(num) || 0) + 1);
  }

  // Count occurrences in guess
  for (const num of guess) {
    guessCounter.set(num, (guessCounter.get(num) || 0) + 1);
  }

  // Total matches is the sum of minimum counts for each number
  let totalMatches = 0;
  for (const [num, answerCount] of answerCounter) {
    const guessCount = guessCounter.get(num) || 0;
    totalMatches += Math.min(answerCount, guessCount);
  }

  // Blows = total matches - hits
  const blows = totalMatches - hits;

  return [hits, blows];
}
