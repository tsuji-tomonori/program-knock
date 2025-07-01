# 問題(03B): クラスタリングの実装

## 問題文

データのクラスタリングは、機械学習やデータ分析において重要な手法の一つです。特に、**K-means クラスタリング**は広く使われる手法です。

K-means クラスタリングは以下の手順で動作します。

1. **K個のクラスタの重心（centroids）をランダムに初期化する**
2. **各データ点を最も近いクラスタに割り当てる**
3. **各クラスタの重心を再計算する**
4. **クラスタの割り当てが収束するか、最大反復回数に達するまで繰り返す**

2次元平面上のデータを対象にしたK-meansクラスタリングを行う関数を実装してください。

## 入力

```python
from typing import List, Tuple

def k_means_clustering(points: List[Tuple[float, float]], k: int, max_iter: int = 100) -> List[int]:
    """
    K-meansクラスタリングを実装する関数。

    Args:
        points (List[Tuple[float, float]]): 2次元のデータ点のリスト。
        k (int): クラスタ数（1以上）。
        max_iter (int): 最大反復回数（デフォルト100）。

    Returns:
        List[int]: 各データ点が属するクラスタのインデックス（0 以上 k-1 の整数）。
    """
    ...
```

### 入力値の条件

- `points`:
  - `points[i]` は `(x, y)` の形式で、`x, y` は実数 (`float`)。
  - `1 <= len(points) <= 1000`
- `k`:
  - `1 <= k <= min(10, len(points))`
- `max_iter`:
  - `1 <= max_iter <= 1000`

## 出力

- **List[int]**:
  - 各データ点が属するクラスタのインデックス（`0` 以上 `k-1` の整数）をリストで返します。

## サンプル1

```python
from mymodule import k_means_clustering

def test_basic():
    points = [(1.0, 2.0), (1.5, 1.8), (5.0, 8.0), (8.0, 8.0), (1.0, 0.6), (9.0, 11.0)]
    k = 2
    result = k_means_clustering(points, k)
    assert len(result) == len(points)
    assert all(0 <= cluster < k for cluster in result)
```

**解説**

- 2つのクラスタ (`k=2`) に分類される。
- すべての点にクラスタのインデックス (`0` または `1`) が割り当てられていることを確認。

## サンプル2

```python
def test_single_cluster():
    points = [(1.0, 2.0), (2.0, 3.0), (3.0, 4.0)]
    k = 1
    result = k_means_clustering(points, k)
    assert result == [0, 0, 0]  # すべての点がクラスタ0に属する
```

**解説**

- クラスタ数 `k=1` の場合、すべての点は `0` に分類される。

## サンプル3

```python
def test_three_clusters():
    points = [(1.0, 1.0), (2.0, 2.0), (10.0, 10.0), (11.0, 11.0), (50.0, 50.0)]
    k = 3
    result = k_means_clustering(points, k)
    assert len(set(result)) == 3  # 3つのクラスタに分類される
```

**解説**

- 離れた3つのグループがあるため、`k=3` では3つの異なるクラスタに分類される。
