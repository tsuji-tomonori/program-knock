def find_closest_value(arr: list[int], target: int) -> int:
    """
    ソート済みの整数配列から、指定された値に最も近い値を返す。

    - 最も近い値が複数ある場合は、小さい方を返す
    - 配列 arr は昇順ソートされており、重複はない
    - arr の要素数は 1 以上

    Args:
        arr (list[int]): ソートされた整数のリスト
        target (int): 探索値

    Returns:
        int: arr 内で target に最も近い値
    """

    # 要素数が 1 の場合は、その要素を返す
    if len(arr) == 1:
        return arr[0]

    left, right = 0, len(arr) - 1

    # 二分探索
    while left < right:
        mid = (left + right) // 2

        if arr[mid] == target:
            # 一致する値が見つかった場合は即座に返す
            return arr[mid]
        elif arr[mid] < target:
            left = mid + 1
        else:
            right = mid

    # left と right が同じ位置で終了する
    # 候補として arr[left] を取り、必要に応じて直前の要素 (arr[left-1]) と比較する
    candidate = arr[left]

    # left が配列の先頭より先であれば、直前の要素と比較
    if left > 0:
        prev_val = arr[left - 1]
        dist_candidate = abs(candidate - target)
        dist_prev_val = abs(prev_val - target)

        # 直前の要素がより近い場合、もしくは同距離かつ前の方が小さい場合は prev_val を返す
        if dist_prev_val < dist_candidate or (
            dist_prev_val == dist_candidate and prev_val < candidate
        ):
            candidate = prev_val

    return candidate
