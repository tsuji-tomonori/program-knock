from src.remove_duplicate_customers import remove_duplicate_customers


def test_sample1():
    """
    サンプルテストケース1:
    [101, 202, 303, 101, 404, 202, 505] → (101, 202, 303, 404, 505)
    """
    customer_ids = [101, 202, 303, 101, 404, 202, 505]
    expected = (101, 202, 303, 404, 505)
    assert remove_duplicate_customers(customer_ids) == expected


def test_sample2():
    """
    サンプルテストケース2:
    [1, 2, 3, 4, 5, 6, 7, 8, 9] → (1, 2, 3, 4, 5, 6, 7, 8, 9)
    """
    customer_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9]
    expected = (1, 2, 3, 4, 5, 6, 7, 8, 9)
    assert remove_duplicate_customers(customer_ids) == expected


def test_sample3():
    """
    サンプルテストケース3:
    [42, 42, 42, 42, 42] → (42,)
    """
    customer_ids = [42, 42, 42, 42, 42]
    expected = (42,)
    assert remove_duplicate_customers(customer_ids) == expected


def test_sample4():
    """
    サンプルテストケース4:
    [] → ()
    """
    customer_ids = []
    expected = ()
    assert remove_duplicate_customers(customer_ids) == expected


def test_sample5():
    """
    サンプルテストケース5:
    [500, -1, 500, -1, 200, 300, 200, -100] → (500, -1, 200, 300, -100)
    """
    customer_ids = [500, -1, 500, -1, 200, 300, 200, -100]
    expected = (500, -1, 200, 300, -100)
    assert remove_duplicate_customers(customer_ids) == expected


def test_single_element():
    """
    境界値テスト:
    要素が1つだけの場合。
    """
    customer_ids = [0]
    expected = (0,)
    assert remove_duplicate_customers(customer_ids) == expected


def test_all_unique_elements():
    """
    境界値テスト:
    全ての要素が一意な場合、そのままの順序で返す。
    """
    customer_ids = [10, 20, 30, 40, 50]
    expected = (10, 20, 30, 40, 50)
    assert remove_duplicate_customers(customer_ids) == expected


def test_all_duplicate_negative():
    """
    境界値テスト:
    全てが同じ負の値の場合。
    """
    customer_ids = [-5, -5, -5, -5]
    expected = (-5,)
    assert remove_duplicate_customers(customer_ids) == expected


def test_boundary_values():
    """
    境界値テスト:
    顧客IDが仕様の最小値と最大値の場合。
    """
    customer_ids = [-1000000, 1000000, -1000000, 1000000, 0]
    expected = (-1000000, 1000000, 0)
    assert remove_duplicate_customers(customer_ids) == expected


def test_interleaved_duplicates():
    """
    重複が交互に現れるパターンで、順序が正しく保持されるかを検証。
    例: [3, 1, 2, 3, 2, 1, 4, 5, 4] → (3, 1, 2, 4, 5)
    """
    customer_ids = [3, 1, 2, 3, 2, 1, 4, 5, 4]
    expected = (3, 1, 2, 4, 5)
    assert remove_duplicate_customers(customer_ids) == expected


def test_large_input():
    """
    境界値テスト:
    非常に大きなリスト(重複を含む)でも正しく動作するかを検証。
    例: 0~9999 の各数字を2回ずつ並べ、最初の出現順序を保持。
    """
    # 0から9999までを2回ずつ連結したリスト
    customer_ids = list(range(10000)) + list(range(10000))
    expected = tuple(range(10000))
    assert remove_duplicate_customers(customer_ids) == expected
