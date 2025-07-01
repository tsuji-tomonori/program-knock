from src.count_in_range import count_in_range


class TestCountInRange:
    def test_basic_case(self):
        arr = [1, 3, 5, 7, 9, 11]
        left, right = 4, 8
        assert count_in_range(arr, left, right) == 2  # 5, 7 の2つ

    def test_negative_numbers(self):
        arr = [-5, -3, -1, 2, 4, 6, 8, 10]
        left, right = -2, 5
        assert count_in_range(arr, left, right) == 3  # -1, 2, 4 の3つ

    def test_out_of_range(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 6, 10
        assert count_in_range(arr, left, right) == 0  # 範囲に該当なし

    def test_partial_range(self):
        arr = [10, 20, 30, 40, 50]
        left, right = 15, 45
        assert count_in_range(arr, left, right) == 3  # 20, 30, 40 の3つ

    def test_exact_boundaries(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 2, 4
        assert count_in_range(arr, left, right) == 3  # 2, 3, 4 の3つ

    def test_single_element_match(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 3, 3
        assert count_in_range(arr, left, right) == 1  # 3 の1つ

    def test_single_element_no_match(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 6, 6
        assert count_in_range(arr, left, right) == 0  # なし

    def test_all_elements(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 0, 10
        assert count_in_range(arr, left, right) == 5  # 全ての要素

    def test_empty_range_before(self):
        arr = [5, 6, 7, 8, 9]
        left, right = 1, 3
        assert count_in_range(arr, left, right) == 0  # なし

    def test_empty_range_after(self):
        arr = [1, 2, 3, 4, 5]
        left, right = 10, 15
        assert count_in_range(arr, left, right) == 0  # なし

    def test_duplicates(self):
        arr = [1, 2, 2, 2, 3, 4, 4, 5]
        left, right = 2, 4
        assert count_in_range(arr, left, right) == 6  # 2,2,2,3,4,4 の6つ

    def test_negative_range(self):
        arr = [-10, -5, -3, -1, 0, 2, 5]
        left, right = -6, -2
        assert count_in_range(arr, left, right) == 2  # -5, -3 の2つ

    def test_single_element_array(self):
        arr = [42]
        assert count_in_range(arr, 40, 45) == 1
        assert count_in_range(arr, 50, 60) == 0
        assert count_in_range(arr, 42, 42) == 1

    def test_large_range(self):
        arr = list(range(0, 100, 2))  # [0, 2, 4, 6, ..., 98]
        left, right = 10, 20
        assert count_in_range(arr, left, right) == 6  # 10,12,14,16,18,20 の6つ

    def test_boundary_edge_cases(self):
        arr = [1, 3, 5, 7, 9]
        # Range exactly matches first element
        assert count_in_range(arr, 1, 1) == 1
        # Range exactly matches last element
        assert count_in_range(arr, 9, 9) == 1
        # Range from first to last
        assert count_in_range(arr, 1, 9) == 5
