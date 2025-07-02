import re

from src.calculate_new_year_holiday import calculate_new_year_holiday


def test_sample1():
    """
    サンプルテストケース1 (year=2025):
    基本期間 12/29～1/3 に加え、前日 2024-12-28（土）と翌日 2025-01-04,05（土・日）が追加される
    → 休暇期間: 2024-12-28 ～ 2025-01-05, 休暇日数: 9日
    """
    result = calculate_new_year_holiday(2025)
    expected = ("2024-12-28", "2025-01-05", 9)
    assert result == expected


def test_sample2():
    """
    サンプルテストケース2 (year=2026):
    基本期間 12/29～1/3 に加え、前日 2024-12-27,28（土・日）と翌日 2025-01-04（日）が追加される
    → 休暇期間: 2025-12-27 ～ 2026-01-04, 休暇日数: 9日
    """
    result = calculate_new_year_holiday(2026)
    expected = ("2025-12-27", "2026-01-04", 9)
    assert result == expected


def test_year_2023():
    """
    テストケース: 2023年
    基本期間のみ (2022-12-29 ～ 2023-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2023)
    expected = ("2022-12-29", "2023-01-03", 6)
    assert result == expected


def test_year_2024():
    """
    テストケース: 2024年
    基本期間のみ (2023-12-29 ～ 2024-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2024)
    expected = ("2023-12-29", "2024-01-03", 6)
    assert result == expected


def test_year_2027():
    """
    テストケース: 2027年
    基本期間のみ (2026-12-29 ～ 2027-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2027)
    expected = ("2026-12-29", "2027-01-03", 6)
    assert result == expected


def test_year_2021():
    """
    テストケース: 2021年
    基本期間のみ (2020-12-29 ～ 2021-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2021)
    expected = ("2020-12-29", "2021-01-03", 6)
    assert result == expected


def test_year_2020():
    """
    テストケース: 2020年
    基本期間 2019-12-29 ～ 2020-01-03 に加え、前日 2019-12-28（土）と
    翌日 2020-01-04,05（土・日）が追加される
    → 休暇期間: 2019-12-28 ～ 2020-01-05, 休暇日数: 9日
    """
    result = calculate_new_year_holiday(2020)
    expected = ("2019-12-28", "2020-01-05", 9)
    assert result == expected


def test_year_2019():
    """
    テストケース: 2019年
    基本期間のみ (2018-12-29 ～ 2019-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2019)
    expected = ("2018-12-29", "2019-01-03", 6)
    assert result == expected


def test_year_2000():
    """
    テストケース: 2000年
    基本期間のみ (1999-12-29 ～ 2000-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2000)
    expected = ("1999-12-29", "2000-01-03", 6)
    assert result == expected


def test_year_2100():
    """
    境界値テスト: 2100年
    基本期間のみ (2099-12-29 ～ 2100-01-03)、拡張なし → 休暇日数: 6日
    """
    result = calculate_new_year_holiday(2100)
    expected = ("2099-12-29", "2100-01-03", 6)
    assert result == expected


def test_output_format():
    """
    形式テスト:
    出力される開始日、終了日は "YYYY-MM-DD" 形式の文字列であり、
    休暇日数は整数であることを確認する
    """
    result = calculate_new_year_holiday(2025)
    start_date, end_date, days = result
    date_pattern = r"^\d{4}-\d{2}-\d{2}$"
    assert re.match(date_pattern, start_date)
    assert re.match(date_pattern, end_date)
    assert isinstance(days, int)
