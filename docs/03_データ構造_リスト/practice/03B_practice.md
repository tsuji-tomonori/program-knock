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

- `points` (リスト): 2次元のデータ点のリスト。各点は (x, y) の形式で、x, y は実数。
- `k` (整数): クラスタ数（1以上）。
- `max_iter` (整数): 最大反復回数（デフォルト100）。

### 入力値の条件

- `points`:
  - `points[i]` は `(x, y)` の形式で、`x, y` は実数。
  - `1 <= len(points) <= 1000`
- `k`:
  - `1 <= k <= min(10, len(points))`
- `max_iter`:
  - `1 <= max_iter <= 1000`

## 出力

- **整数のリスト**: 各データ点が属するクラスタのインデックス（`0` 以上 `k-1` の整数）をリストで返します。

## サンプル1

**入力:**
- `points`: [(1.0, 2.0), (1.5, 1.8), (5.0, 8.0), (8.0, 8.0), (1.0, 0.6), (9.0, 11.0)]
- `k`: 2

**出力:** 長さ6のリスト（各要素は0または1）

**解説**

- 2つのクラスタ (`k=2`) に分類される。
- すべての点にクラスタのインデックス (`0` または `1`) が割り当てられていることを確認。

## サンプル2

**入力:**
- `points`: [(1.0, 2.0), (2.0, 3.0), (3.0, 4.0)]
- `k`: 1

**出力:** `[0, 0, 0]`

**解説**

- クラスタ数 `k=1` の場合、すべての点は `0` に分類される。

## サンプル3

**入力:**
- `points`: [(1.0, 1.0), (2.0, 2.0), (10.0, 10.0), (11.0, 11.0), (50.0, 50.0)]
- `k`: 3

**出力:** 3つの異なるクラスタに分類される整数のリスト

**解説**

- 離れた3つのグループがあるため、`k=3` では3つの異なるクラスタに分類される。
