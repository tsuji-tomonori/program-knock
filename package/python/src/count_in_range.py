import bisect


def count_in_range(arr: list[int], left: int, right: int) -> int:
    """
    ソート済みの配列から指定範囲 [l, r] に含まれる要素の個数を求める関数。

    Args:
        arr (list[int]): ソート済みの整数配列（昇順）。
        left (int): 範囲の下限。
        right (int): 範囲の上限。

    Returns:
        int: 範囲 [left, right] に含まれる要素の個数。
    """
    # Find the leftmost position where we can insert left (first element >= left)
    left_index = bisect.bisect_left(arr, left)

    # Find the rightmost position where we can insert right (first element > right)
    right_index = bisect.bisect_right(arr, right)

    # Count of elements in range [l, r] is the difference
    return right_index - left_index
