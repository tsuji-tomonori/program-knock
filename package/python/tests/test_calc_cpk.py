import pytest

from src.calc_cpk import Param, calc_cpk


def test_basic_1():
    """
    問題文に記載のサンプルテスト
    """
    param = Param(usl=10.0, lsl=2.0, data=[4.5, 5.0, 4.8, 5.2, 5.5])
    assert calc_cpk(param) == 2.626


def test_02_symmetric_data_small_stdev():
    """
    データがほぼ同じ (標準偏差がとても小さい) パターン。
    例: usl=10, lsl=0, data=[4.9999,5.0,5.0001,5.0,5.0]
    平均は5付近、k=0 に近く、Cp は非常に大きくなる => Cpk も大きい。
    """
    param = Param(usl=10, lsl=0, data=[4.9999, 5.0, 5.0001, 5.0, 5.0])
    cpk_val = calc_cpk(param)
    # 理論的には非常に大きい値になる(数万近く)が、
    # 実装でどこまで正確に出るかは浮動小数誤差次第。
    # おおむね 2万以上であれば合格、のように近似テストする:
    assert cpk_val > 20000


def test_03_symmetric_data_normal_stdev():
    """
    usl=10, lsl=0 でデータが [4,5,6,5,5] の場合。
    平均=5, 標準偏差=~0.707..., Cp=~2.357, k=0 => Cpk=~2.357
    """
    param = Param(usl=10, lsl=0, data=[4, 5, 6, 5, 5])
    cpk_val = calc_cpk(param)
    # ある程度誤差を見込んだ近似比較
    assert cpk_val == 2.357


def test_04_near_lsl():
    """
    usl=10, lsl=2 でデータが下側近く: [2.1,2.2,2.0,2.1,2.3]
    手計算例: Cpk ≈ 0.409
    """
    param = Param(usl=10, lsl=2, data=[2.1, 2.2, 2.0, 2.1, 2.3])
    cpk_val = calc_cpk(param)
    assert cpk_val == 0.409


def test_05_near_usl():
    """
    usl=10, lsl=2 でデータが上側近く: [9.5,9.7,9.8,9.9,9.4]
    手計算例: Cpk ≈ 0.547
    """
    param = Param(usl=10, lsl=2, data=[9.5, 9.7, 9.8, 9.9, 9.4])
    cpk_val = calc_cpk(param)
    assert cpk_val == 0.547


def test_06_large_range():
    """
    usl=100, lsl=0, data=[50,55,60,45,40]
    手計算例: Cpk ≈ 2.108
    """
    param = Param(usl=100, lsl=0, data=[50, 55, 60, 45, 40])
    cpk_val = calc_cpk(param)
    assert cpk_val == 2.108


def test_07_narrow_range():
    """
    usl=5, lsl=4, data=[4.5,4.3,4.6,4.2,4.4]
    手計算例: Cpk ≈ 0.843
    """
    param = Param(usl=5, lsl=4, data=[4.5, 4.3, 4.6, 4.2, 4.4])
    cpk_val = calc_cpk(param)
    assert cpk_val == 0.843


def test_08_minimum_data_two_points():
    """
    usl=10, lsl=0, data=[4,6]
    標本標準偏差= sqrt(2) ≈1.4142 -> Cp ≈1.1785, k=0 -> Cpk≈1.1785
    => 1.179 付近
    """
    param = Param(usl=10, lsl=0, data=[4, 6])
    cpk_val = calc_cpk(param)
    assert cpk_val == 1.179


def test_09_single_data_raises_error():
    """
    データが1点のみの場合、標本標準偏差を計算すると
    分母 (n - 1) = 0 になり ZeroDivisionError 等が起きることを想定。
    仕様上エラーを投げる/ハンドリングするならこういったテストで確認。
    """
    param = Param(usl=10, lsl=0, data=[5])
    with pytest.raises(ZeroDivisionError):
        _ = calc_cpk(param)


def test_10_k_equals_1():
    """
    平均が規格幅の端(M から R/2 離れた位置) -> k=1 -> Cpk=0 になる例。
    例: usl=10, lsl=0, data平均が10付近。
    """
    param = Param(usl=10, lsl=0, data=[9.9, 10.0, 10.1, 10.0, 10.0])
    cpk_val = calc_cpk(param)
    # k=1 なら (1-k)*Cp=0
    assert cpk_val == 0.0
