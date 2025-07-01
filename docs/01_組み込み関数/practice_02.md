# 問題(01B): 年齢分布の集計

## 問題文

ある企業では、社員の年齢分布を分析するために、年齢データを基に集計を行いたいと考えています。与えられた社員の年齢リストに対して、以下の集計を行う関数を実装してください。

1. **最大年齢と最小年齢の取得**
2. **平均年齢の計算**
3. **平均年齢以下の社員の人数をカウント**

## 入力

```python
from typing import NamedTuple


class Param(NamedTuple):
    ages: list[int]  # 社員の年齢リスト (0 <= ages[i] <= 120)


def count_age_statistics(param: Param) -> tuple[int, int, float, int]:
    """
    年齢分布を集計する関数

    Args:
        param (Param): 社員の年齢データ

    Returns:
        tuple[int, int, float, int]: (最大年齢, 最小年齢, 平均年齢, 平均年齢以下の社員の人数)
    """
    ...
```

### 入力値の条件

- `ages`:
  - 社員の年齢を表す整数のリスト。
  - 各年齢は `0` 以上 `120` 以下。
  - リストは空ではない。

## 出力

- `(max_age, min_age, avg_age, count_below_avg)` の形式でタプルを返す。
  - `max_age`: 最大年齢 (int)
  - `min_age`: 最小年齢 (int)
  - `avg_age`: 平均年齢 (float, 小数第2位まで丸める)
  - `count_below_avg`: 平均年齢以下の社員の人数 (int)

## サンプル1

```python
import src.age_statistics as t

def test_basic():
    param = t.Param(
        ages=[25, 30, 35, 40, 45, 50]
    )
    assert t.count_age_statistics(param) == (50, 25, 37.5, 3)
```

**解説**

- 最大年齢: `50`
- 最小年齢: `25`
- 平均年齢: `(25 + 30 + 35 + 40 + 45 + 50) / 6 = 37.5`
- 平均年齢以下の人数: `25, 30, 35` の **3 人**

出力: `(50, 25, 37.5, 3)`

## サンプル2

```python
def test_varied_ages():
    param = t.Param(
        ages=[18, 22, 22, 24, 29, 35, 40, 50, 60]
    )
    assert t.count_age_statistics(param) == (60, 18, 33.33, 5)
```

**解説**

- 最大年齢: `60`
- 最小年齢: `18`
- 平均年齢: `(18 + 22 + 22 + 24 + 29 + 35 + 40 + 50 + 60) / 9 = 33.3333...`
  - 小数第2位まで丸めて `33.33`
- 平均年齢以下の人数: `18, 22, 22, 24, 29` の **5 人**

出力: `(60, 18, 33.33, 5)`
