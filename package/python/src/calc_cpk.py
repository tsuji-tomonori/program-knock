import math
from typing import NamedTuple


class Param(NamedTuple):
    usl: float  # 規格上限値
    lsl: float  # 規格下限値
    data: list[float]  # 工程データ


def calc_cpk(param: Param) -> float:
    """
    工程能力指数を計算する関数.

    Args:
        param (Param): 計算に必要な値

    Returns:
        float: 工程能力指数(Cpk)を小数点以下3桁で四捨五入した値
               （問題文サンプルに合わせて 2.626 のように3桁で返します）
    """
    # 1. データの平均
    x = sum(param.data) / len(param.data)

    # 2. データの標準偏差(サンプル分散を用いる)
    #    標本標準偏差: sqrt(Σ((x - mean)^2) / (n - 1))
    squared_diff_sum = sum((x - d) ** 2 for d in param.data)
    sigma = math.sqrt(squared_diff_sum / (len(param.data) - 1))

    # 3. 規格中心値 M, 規格幅 R
    M = (param.usl + param.lsl) / 2
    R = param.usl - param.lsl

    # 4. k を計算
    k = abs(x - M) / (R / 2)

    # 5. Cp を計算
    Cp = (param.usl - param.lsl) / (6 * sigma)

    # 6. Cpk を計算
    Cpk = (1 - k) * Cp

    # 問題文のサンプル例に合わせ、小数点以下3桁で四捨五入して返す
    # （問題文には「小数点第4位で四捨五入」とありますが、
    #   サンプルが 2.626 の3桁表記となっているため、それに準拠します）
    return round(Cpk, 3)
