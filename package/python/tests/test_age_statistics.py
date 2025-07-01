from src.age_statistics import analyze_age_distribution


class TestAgeStatistics:
    def test_sample_1(self):
        ages = [25, 30, 35, 40, 45, 50]
        assert analyze_age_distribution(ages) == (50, 25, 37.5, 3)

    def test_sample_2(self):
        ages = [18, 22, 22, 24, 29, 35, 40, 50, 60]
        assert analyze_age_distribution(ages) == (60, 18, 33.33, 5)

    def test_single_age(self):
        ages = [30]
        assert analyze_age_distribution(ages) == (30, 30, 30.0, 1)

    def test_all_same_age(self):
        ages = [25, 25, 25, 25]
        assert analyze_age_distribution(ages) == (25, 25, 25.0, 4)

    def test_two_ages(self):
        ages = [20, 40]
        assert analyze_age_distribution(ages) == (40, 20, 30.0, 1)

    def test_edge_case_young(self):
        ages = [0, 1, 2, 3, 4]
        assert analyze_age_distribution(ages) == (4, 0, 2.0, 3)

    def test_edge_case_old(self):
        ages = [110, 115, 120]
        assert analyze_age_distribution(ages) == (120, 110, 115.0, 2)

    def test_large_dataset(self):
        ages = list(range(20, 71))  # Ages from 20 to 70
        assert analyze_age_distribution(ages) == (70, 20, 45.0, 26)

    def test_mixed_ages(self):
        ages = [18, 25, 32, 45, 52, 60, 65, 70, 22, 28]
        assert analyze_age_distribution(ages) == (70, 18, 41.7, 5)

    def test_precision_rounding(self):
        ages = [33, 34, 35]
        assert analyze_age_distribution(ages) == (35, 33, 34.0, 2)

    def test_precision_rounding_2(self):
        ages = [10, 20, 30, 40, 50, 60, 70]
        assert analyze_age_distribution(ages) == (70, 10, 40.0, 4)
