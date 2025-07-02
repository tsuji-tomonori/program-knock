/**
 * ソート済みの整数配列から、指定された値に最も近い値を返す。
 *
 * - 最も近い値が複数ある場合は、小さい方を返す
 * - 配列 arr は昇順ソートされており、重複はない
 * - arr の要素数は 1 以上
 *
 * @param arr - ソートされた整数のリスト
 * @param target - 探索値
 * @returns arr 内で target に最も近い値
 */
export function findClosestValue(arr: number[], target: number): number {
  // 要素数が 1 の場合は、その要素を返す
  if (arr.length === 1) {
    return arr[0];
  }

  let left = 0;
  let right = arr.length - 1;

  // 二分探索
  while (left < right) {
    const mid = Math.floor((left + right) / 2);

    if (arr[mid] === target) {
      // 一致する値が見つかった場合は即座に返す
      return arr[mid];
    } else if (arr[mid] < target) {
      left = mid + 1;
    } else {
      right = mid;
    }
  }

  // left と right が同じ位置で終了する
  // 候補として arr[left] を取り、必要に応じて直前の要素 (arr[left-1]) と比較する
  let candidate = arr[left];

  // left が配列の先頭より先であれば、直前の要素と比較
  if (left > 0) {
    const prevVal = arr[left - 1];
    const distCandidate = Math.abs(candidate - target);
    const distPrevVal = Math.abs(prevVal - target);

    // 直前の要素がより近い場合、もしくは同距離かつ前の方が小さい場合は prevVal を返す
    if (distPrevVal < distCandidate || (distPrevVal === distCandidate && prevVal < candidate)) {
      candidate = prevVal;
    }
  }

  return candidate;
}
