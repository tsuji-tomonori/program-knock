/**
 * Finds the first position where target can be inserted to keep array sorted
 * (equivalent to Python's bisect.bisect_left)
 */
function bisectLeft(arr: number[], target: number): number {
  let left = 0;
  let right = arr.length;

  while (left < right) {
    const mid = Math.floor((left + right) / 2);
    if (arr[mid] < target) {
      left = mid + 1;
    } else {
      right = mid;
    }
  }

  return left;
}

/**
 * Finds the position after the last occurrence of target
 * (equivalent to Python's bisect.bisect_right)
 */
function bisectRight(arr: number[], target: number): number {
  let left = 0;
  let right = arr.length;

  while (left < right) {
    const mid = Math.floor((left + right) / 2);
    if (arr[mid] <= target) {
      left = mid + 1;
    } else {
      right = mid;
    }
  }

  return left;
}

/**
 * ソート済みの配列から指定範囲 [left, right] に含まれる要素の個数を求める関数。
 *
 * @param arr - ソート済みの整数配列（昇順）
 * @param left - 範囲の下限
 * @param right - 範囲の上限
 * @returns 範囲 [left, right] に含まれる要素の個数
 */
export function countInRange(arr: number[], left: number, right: number): number {
  // Find the leftmost position where we can insert left (first element >= left)
  const leftIndex = bisectLeft(arr, left);

  // Find the rightmost position where we can insert right (first element > right)
  const rightIndex = bisectRight(arr, right);

  // Count of elements in range [left, right] is the difference
  return rightIndex - leftIndex;
}
