# 問題(12B): 画像の領域塗りつぶし

## 問題文

あなたは 2 次元のグリッド画像を扱っています。この画像は `0` と `1` の 2 値で表されており、`1` は塗りつぶし可能な領域を示します。

特定の座標 `(sr, sc)` を指定すると、そこを起点に **フラッドフィル (Flood Fill)** アルゴリズムを用いて、新しい色 `new_color` で塗りつぶすことができます。

この処理を行う関数 `flood_fill` を実装してください。

## 入力

```python
def flood_fill(image: list[list[int]], sr: int, sc: int, new_color: int) -> list[list[int]]:
    """
    画像内の特定の領域を、新しい色で塗りつぶす。

    Args:
        image (list[list[int]]): 2次元のグリッド画像
        sr (int): 開始行のインデックス
        sc (int): 開始列のインデックス
        new_color (int): 塗りつぶしに使用する色

    Returns:
        list[list[int]]: 塗りつぶし後の画像
    """
    ...
```

### 制約

- `image` のサイズは `1 <= len(image), len(image[0]) <= 50`
- `image[i][j]` は `0` または `1`
- `0 <= sr < len(image)`
- `0 <= sc < len(image[0])`
- `new_color` は `0` または `1`
- `image[sr][sc]` が `new_color` と同じ場合、変更せずそのまま返す

## 出力

- `flood_fill` は、座標 `(sr, sc)` から開始し、同じ値の隣接セル（上下左右）を `new_color` で塗りつぶした 2 次元リストを返す。

## サンプル1
```python
image = [
    [1, 1, 0],
    [1, 0, 1],
    [0, 1, 1]
]
sr, sc, new_color = 1, 2, 0

result = flood_fill(image, sr, sc, new_color)
assert result == [
    [1, 1, 0],
    [1, 0, 0],
    [0, 0, 0]
]
```

**解説**
- `(1,2)` の `1` を `0` に変更
- `(2,2)`, `(2,1)` も `1` なので `0` に変更

## サンプル2
```python
image = [
    [1, 1, 1],
    [1, 1, 0],
    [1, 0, 1]
]
sr, sc, new_color = 1, 1, 2

result = flood_fill(image, sr, sc, new_color)
assert result == [
    [2, 2, 2],
    [2, 2, 0],
    [2, 0, 1]
]
```

**解説**
- `(1,1)` を `2` に変更
- `(0,0)`, `(0,1)`, `(0,2)`, `(1,0)`, `(1,1)` も `2` に変更

## サンプル3
```python
image = [
    [0, 0, 0],
    [0, 1, 1]
]
sr, sc, new_color = 1, 1, 1

result = flood_fill(image, sr, sc, new_color)
assert result == [
    [0, 0, 0],
    [0, 1, 1]
]  # 変更なし
```

**解説**
- `(1,1)` の値がすでに `1` なので、何も変更せずそのまま返す
