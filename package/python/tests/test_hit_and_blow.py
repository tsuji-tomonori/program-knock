from src.hit_and_blow import hit_and_blow


class TestHitAndBlow:
    def test_case_1(self):
        assert hit_and_blow([1, 2, 3, 4], [1, 3, 2, 5]) == (1, 2)

    def test_case_2(self):
        assert hit_and_blow([5, 6, 7, 8], [8, 7, 6, 5]) == (0, 4)

    def test_case_3(self):
        assert hit_and_blow([3, 1, 4, 7], [3, 1, 4, 7]) == (4, 0)

    def test_case_4(self):
        assert hit_and_blow([9, 8, 7, 6], [1, 2, 3, 4]) == (0, 0)

    def test_single_digit(self):
        assert hit_and_blow([5], [5]) == (1, 0)
        assert hit_and_blow([5], [3]) == (0, 0)

    def test_two_digits(self):
        assert hit_and_blow([1, 2], [2, 1]) == (0, 2)
        assert hit_and_blow([1, 2], [1, 3]) == (1, 0)
        assert hit_and_blow([1, 2], [3, 2]) == (1, 0)

    def test_partial_match(self):
        assert hit_and_blow([1, 2, 3], [1, 5, 2]) == (1, 1)
        assert hit_and_blow([1, 2, 3], [4, 1, 5]) == (0, 1)

    def test_all_hits(self):
        assert hit_and_blow([0, 1, 2, 3], [0, 1, 2, 3]) == (4, 0)
        assert hit_and_blow([9, 8, 7], [9, 8, 7]) == (3, 0)

    def test_all_blows(self):
        assert hit_and_blow([1, 2, 3], [3, 1, 2]) == (0, 3)
        assert hit_and_blow([1, 2], [2, 1]) == (0, 2)

    def test_no_matches(self):
        assert hit_and_blow([1, 2, 3], [4, 5, 6]) == (0, 0)
        assert hit_and_blow([0, 9], [1, 8]) == (0, 0)

    def test_mixed_scenario(self):
        assert hit_and_blow([1, 2, 3, 4, 5], [1, 5, 6, 4, 7]) == (2, 1)
        assert hit_and_blow([0, 1, 2, 3], [0, 2, 1, 4]) == (1, 2)

    def test_edge_case_zeros(self):
        assert hit_and_blow([0, 0, 0], [0, 0, 0]) == (3, 0)
        assert hit_and_blow([0, 1, 2], [0, 2, 1]) == (1, 2)

    def test_longer_sequence(self):
        assert hit_and_blow([1, 2, 3, 4, 5, 6], [1, 6, 2, 3, 4, 5]) == (1, 5)
        assert hit_and_blow([0, 1, 2, 3, 4, 5], [5, 4, 3, 2, 1, 0]) == (0, 6)

    def test_one_hit_multiple_blows(self):
        assert hit_and_blow([1, 2, 3, 4], [1, 4, 2, 5]) == (1, 2)
        assert hit_and_blow([9, 8, 7, 6], [9, 6, 8, 5]) == (1, 2)
