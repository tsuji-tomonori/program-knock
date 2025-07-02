/**
 * 問い合わせ履歴から重複した顧客IDを除去し、最初に出現した順序を保持したタプルとして返す関数。
 *
 * @param customerIds - 顧客IDのリスト
 * @returns 重複を除いた顧客IDのタプル（配列として返す）
 */
export function removeDuplicateCustomers(customerIds: number[]): number[] {
  const seen = new Set<number>(); // 既に出現した顧客IDを記録するためのセット
  const uniqueIds: number[] = []; // 順序を保持するための配列

  for (const cid of customerIds) {
    if (!seen.has(cid)) {
      seen.add(cid);
      uniqueIds.push(cid);
    }
  }

  return uniqueIds;
}
