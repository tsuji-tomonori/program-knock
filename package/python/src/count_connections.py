from typing import NamedTuple


class Log(NamedTuple):
    time: int  # 時刻 0 <= time <= 1000
    n_connect: int  # この時間に新規接続した端末の台数
    n_disconnect: int  # この時間に新規切断した端末の台数


class Param(NamedTuple):
    end_time: int  # 集計を終了する時刻
    period: int  # 集計する間隔
    logs: list[Log]  # ログ


def count_connections(param: Param) -> list[int]:
    """
    ある期間ごとの接続数を出力する関数.

    Args:
        param (Param): 接続情報

    Returns:
        list[int]: ある期間ごとの接続数
    """
    connect = [0 for _ in range(param.end_time + 1)]
    disconnect = [0 for _ in range(param.end_time + 1)]

    for log in param.logs:
        connect[log.time] = log.n_connect
        disconnect[log.time] = log.n_disconnect

    return [
        sum(connect[: t + 1]) - sum(disconnect[: t + 1])
        for t in range(0, param.end_time + 1, param.period)
    ]
