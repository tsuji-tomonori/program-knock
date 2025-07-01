from typing import List, Tuple


def analyze_age_distribution(ages: List[int]) -> Tuple[int, int, float, int]:
    """
    Analyze age distribution and return statistics.

    Args:
        ages: List of ages (0-120)

    Returns:
        Tuple of (max_age, min_age, avg_age, count_below_avg)
    """
    max_age = max(ages)
    min_age = min(ages)
    avg_age = round(sum(ages) / len(ages), 2)
    count_below_avg = sum(1 for age in ages if age <= avg_age)

    return (max_age, min_age, avg_age, count_below_avg)
