from src.count_connections import Param, Log, count_connections


def test_basic():
    """
    基本的なテストケース。
    時刻ごとの接続数をテストする。
    end_time=5, period=1 で複数のログを使用して接続数を集計。
    結果として [3, 5, 5, 5, 8, 6] が期待される。
    """
    param = Param(
        end_time=5,
        period=1,
        logs=[
            Log(0, 3, 0),
            Log(1, 2, 0),
            Log(4, 5, 2),
            Log(5, 3, 5),
        ],
    )
    assert count_connections(param) == [3, 5, 5, 5, 8, 6]


def test_no_logs():
    """
    ログが存在しない場合のテスト。
    すべての接続数が 0 であることを確認。
    """
    param = Param(end_time=3, period=1, logs=[])
    assert count_connections(param) == [0, 0, 0, 0]


def test_gap_logs():
    """
    ログの間隔にギャップがある場合のテスト。
    end_time=6, period=2 で複数のログを使用して接続数を集計。
    結果として [0, 4, 4, 5] が期待される。
    """
    param = Param(
        end_time=6,
        period=2,
        logs=[
            Log(1, 4, 0),
            Log(3, 1, 1),
            Log(6, 3, 2),
        ],
    )
    assert count_connections(param) == [0, 4, 4, 5]


def test_single_log():
    """
    単一のログが存在する場合のテスト。
    end_time=5, period=1 で1つのログのみ使用し、接続数を集計。
    結果として [5, 5, 5, 5, 5, 5] が期待される。
    """
    param = Param(
        end_time=5,
        period=1,
        logs=[
            Log(0, 5, 0),
        ],
    )
    assert count_connections(param) == [5, 5, 5, 5, 5, 5]


def test_all_disconnect():
    """
    すべての接続が切断される場合のテスト。
    時刻 2 で全ての接続が切断され、結果として [10, 10, 0, 0, 0] が期待される。
    """
    param = Param(
        end_time=4,
        period=1,
        logs=[
            Log(0, 10, 0),
            Log(2, 0, 10),
        ],
    )
    assert count_connections(param) == [10, 10, 0, 0, 0]


def test_partial_disconnect():
    """
    部分的に切断が行われる場合のテスト。
    end_time=4, period=1 で部分的に接続が切断される状況をテスト。
    結果として [10, 10, 5, 5, 5] が期待される。
    """
    param = Param(
        end_time=4,
        period=1,
        logs=[
            Log(0, 10, 0),
            Log(2, 0, 5),
        ],
    )
    assert count_connections(param) == [10, 10, 5, 5, 5]


def test_large_connect_and_disconnect():
    """
    非常に多くの接続が行われ、その後すべて切断されるケースのテスト。
    end_time=100, period=10 で一気に大量の接続があり、その後すべて切断される状況をテスト。
    最終的にすべての接続が切断され、結果として [0, 10**10, 10**10, 10**10, 0, 0, 0, 0, 0, 0, 0] が期待される。
    """
    param = Param(
        end_time=100,
        period=10,
        logs=[
            Log(10, 10**10, 0),
            Log(40, 0, 10**10),
        ],
    )
    assert count_connections(param) == [0, 10**10, 10**10, 10**10, 0, 0, 0, 0, 0, 0, 0]


def test_output_1000_entries():
    """
    上限のテスト。
    end_time=1000, period=1 で各時刻ごとに出力が行われるケースをテスト。
    すべての時間に対する接続数が正しく計算されているかを確認。
    """
    logs = [Log(i, 1, 0) for i in range(1001)]
    param = Param(end_time=1000, period=1, logs=logs)
    expected = [i + 1 for i in range(1001)]
    assert count_connections(param) == expected


def test_boundary_period_equals_end_time():
    """
    period が end_time と等しい場合のテスト。
    end_time=10, period=10 の場合、結果として [10, 10] が期待される。
    """
    param = Param(
        end_time=10,
        period=10,
        logs=[
            Log(0, 10, 0),
        ],
    )
    assert count_connections(param) == [10, 10]


def test_overlapping_connections_and_disconnections():
    """
    接続と切断が重複して発生する場合のテスト。
    同じ時刻で接続と切断が同時に発生する状況をテスト。
    """
    param = Param(
        end_time=5,
        period=1,
        logs=[
            Log(0, 5, 0),
            Log(2, 3, 2),  # 時刻2で3接続、2切断
            Log(4, 0, 6),  # 時刻4で全て切断
        ],
    )
    assert count_connections(param) == [5, 5, 6, 6, 0, 0]
