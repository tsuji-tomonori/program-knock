from src.find_closest_value import find_closest_value


def test_sample1():
    # サンプル1
    # [1, 3, 5, 7, 9] において target=6 のとき最も近い値は 5 または 7。
    # ただし小さい方の 5 を返す。
    arr = [1, 3, 5, 7, 9]
    target = 6
    assert find_closest_value(arr, target) == 5


def test_sample2():
    # サンプル2
    # [2, 4, 6, 8, 10] において target=7 のとき最も近い値は 6 または 8。
    # ただし小さい方の 6 を返す。
    arr = [2, 4, 6, 8, 10]
    target = 7
    assert find_closest_value(arr, target) == 6


def test_sample3():
    # サンプル3
    # [1, 2, 3, 4, 5] において target=10 のとき最も近い値は 5。
    arr = [1, 2, 3, 4, 5]
    target = 10
    assert find_closest_value(arr, target) == 5


def test_sample4():
    # サンプル4
    # [-10, -5, 0, 5, 10] において target=-7 のとき最も近い値は -5。
    arr = [-10, -5, 0, 5, 10]
    target = -7
    assert find_closest_value(arr, target) == -5


def test_sample5():
    # サンプル5
    # [10] において target=7 のとき配列に 10 しかないので 10 を返す。
    arr = [10]
    target = 7
    assert find_closest_value(arr, target) == 10


# --- 追加テストケース ---


def test_boundary_value_small():
    # 非常に小さい target （制約の下限近く）
    # [-10, -5, 0, 5, 10] かつ target=-999999999 のとき最も近い値は -10
    arr = [-10, -5, 0, 5, 10]
    target = -999999999
    assert find_closest_value(arr, target) == -10


def test_boundary_value_large():
    # 非常に大きい target （制約の上限近く）
    # [-10, -5, 0, 5, 10] かつ target=999999999 のとき最も近い値は 10
    arr = [-10, -5, 0, 5, 10]
    target = 999999999
    assert find_closest_value(arr, target) == 10


def test_equidistant_case():
    # target がちょうど2つの要素と同距離の場合、小さい方を返すことを確認
    # [1, 3, 5, 7, 9], target=4 の場合、3 と 5 が同距離 => 小さい方の 3 を返す
    arr = [1, 3, 5, 7, 9]
    target = 4
    assert find_closest_value(arr, target) == 3


def test_exact_match():
    # target と配列の要素が一致する場合は、そのままの値を返す
    # [1, 3, 5, 7, 9], target=5 のときは 5
    arr = [1, 3, 5, 7, 9]
    target = 5
    assert find_closest_value(arr, target) == 5


def test_single_element():
    # 配列の長さが1の場合、常にその要素を返す
    # arr=[100], target=-100
    arr = [100]
    target = -100
    assert find_closest_value(arr, target) == 100


def test_large_data_middle_target():
    """
    0 から 99999 までの昇順ソート配列 (要素数=100000) を用意し、
    真ん中付近 (target=50000) を検索。
    """
    arr = list(range(100000))  # [0, 1, 2, ..., 99999]
    target = 50000
    # 期待値は 50000 (ピッタリ存在)
    assert find_closest_value(arr, target) == 50000


def test_large_data_low_target():
    """
    -50000 から 49999 までの昇順ソート配列 (要素数=100000) を用意し、
    配列よりも小さい値 (target=-999999) を検索。
    """
    arr = list(range(-50000, 50000))  # [-50000, -49999, ..., 49999]
    target = -999999
    # 一番近いのは配列の先頭(-50000)
    assert find_closest_value(arr, target) == -50000


def test_large_data_high_target():
    """
    -50000 から 49999 までの昇順ソート配列 (要素数=100000) を用意し、
    配列よりも大きい値 (target=999999) を検索。
    """
    arr = list(range(-50000, 50000))  # [-50000, -49999, ..., 49999]
    target = 999999
    # 一番近いのは配列の末尾(49999)
    assert find_closest_value(arr, target) == 49999
