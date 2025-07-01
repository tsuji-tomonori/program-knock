import datetime


def nth_weekday_of_month(year: int, month: int, weekday: int, n: int) -> datetime.date:
    """
    指定された月における、指定された曜日の n 番目の日付を返す関数です。
    曜日の指定は、月曜日=0, 火曜日=1, …, 日曜日=6 とします。

    例:
      nth_weekday_of_month(2025, 1, 0, 2) は、2025年1月の第2月曜日を返します。
    """
    d = datetime.date(year, month, 1)
    count = 0
    while d.month == month:
        if d.weekday() == weekday:
            count += 1
            if count == n:
                return d
        d += datetime.timedelta(days=1)
    raise ValueError(f"{year}年{month}月に{n}番目の曜日({weekday})は存在しません。")


def is_holiday(d: datetime.date) -> bool:
    """
    判定対象の日付が、土日または定められた祝日であるかを判定する。
    祝日リスト：
      - 固定日付: 元日 (1/1), 天皇誕生日 (2/23),
                   憲法記念日 (5/3), みどりの日 (5/4), こどもの日 (5/5),
                   山の日 (8/11), 文化の日 (11/3), 勤労感謝の日 (11/23)
      - 可変日付: 成人の日 (1月の第2月曜日),
                   海の日 (7月の第3月曜日),
                   敬老の日 (9月の第3月曜日),
                   スポーツの日 (10月の第2月曜日),
                   秋分の日 (9月22日または23日) ※簡易判定として両日を対象とする
    また、土曜日・日曜日は常に休みとする。
    """
    # 土日判定
    if d.weekday() >= 5:  # Saturday:5, Sunday:6
        return True

    # 固定祝日
    if d.month == 1 and d.day == 1:  # 元日
        return True
    if d.month == 2 and d.day == 23:  # 天皇誕生日
        return True
    if d.month == 5 and d.day in (3, 4, 5):  # 憲法記念日, みどりの日, こどもの日
        return True
    if d.month == 8 and d.day == 11:  # 山の日
        return True
    if d.month == 11 and d.day in (3, 23):  # 文化の日, 勤労感謝の日
        return True

    # 可変祝日
    if d.month == 1:
        # 成人の日: 1月の第2月曜日
        if d == nth_weekday_of_month(d.year, 1, 0, 2):
            return True
    if d.month == 7:
        # 海の日: 7月の第3月曜日
        if d == nth_weekday_of_month(d.year, 7, 0, 3):
            return True
    if d.month == 9:
        # 敬老の日: 9月の第3月曜日
        if d == nth_weekday_of_month(d.year, 9, 0, 3):
            return True
        # 秋分の日: 簡易判定として9月22日または23日を対象
        if d.day in (22, 23):
            return True
    if d.month == 10:
        # スポーツの日: 10月の第2月曜日
        if d == nth_weekday_of_month(d.year, 10, 0, 2):
            return True

    return False


def calculate_new_year_holiday(year: int) -> tuple[str, str, int]:
    """
    指定された年の年末年始休暇の開始日、終了日、休暇日数を計算する。

    基本の休暇期間は「12月29日～1月3日」とするが、
    この期間に隣接して土日または祝日である場合は、その日も休暇に含める。

    例:
      - 2025年の場合、基本期間は2024-12-29～2025-01-03。
        その前日の2024-12-28（土）および、翌日の2025-01-04（土）と2025-01-05（日）を追加し、
        休暇期間は2024-12-28～2025-01-05、休暇日数は9日となる。

    Args:
        year (int): 調査対象の年（例: 2025）

    Returns:
        tuple[str, str, int]: (休暇開始日, 休暇終了日, 休暇日数)
          日付は "YYYY-MM-DD" の形式、休暇日数は整数
    """
    # 基本期間は、指定年の1月3日まで、前の年の12月29日から
    start_date = datetime.date(year - 1, 12, 29)
    end_date = datetime.date(year, 1, 3)

    # 前日に連続する土日または祝日があれば、休暇開始日を前に拡張する
    while True:
        prev_day = start_date - datetime.timedelta(days=1)
        if is_holiday(prev_day):
            start_date = prev_day
        else:
            break

    # 後日に連続する土日または祝日があれば、休暇終了日を後ろに拡張する
    while True:
        next_day = end_date + datetime.timedelta(days=1)
        if is_holiday(next_day):
            end_date = next_day
        else:
            break

    holiday_days = (end_date - start_date).days + 1
    return (
        start_date.strftime("%Y-%m-%d"),
        end_date.strftime("%Y-%m-%d"),
        holiday_days,
    )
