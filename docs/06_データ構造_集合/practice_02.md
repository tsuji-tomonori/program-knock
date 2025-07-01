# 問題(06B): 商品リストの重複排除

## 問題文

あなたは、オンラインショップのデータ処理を担当しています。
このショップでは、顧客が購入した商品のリストを記録していますが、**同じ商品が複数回登場することがあります**。

データ分析チームから、**購入商品のリストから重複を排除し、価格の高い順に並べたリストを作成してほしい** という依頼を受けました。

購入商品のリストを受け取り、**商品名が重複しないようにしつつ、価格の高い順に並べたリストを作成する関数** を実装してください。

## 入力

以下の `remove_duplicate_products` 関数を実装してください。

```python
def remove_duplicate_products(products: list[tuple[str, int]]) -> list[tuple[str, int]]:
    """
    購入商品のリストから重複を排除し、価格の高い順に並べたリストを返す関数。

    Args:
        products (list[tuple[str, int]]): 商品名と価格のタプルのリスト

    Returns:
        list[tuple[str, int]]: 重複を除いた上で価格の高い順に並べた商品リスト
    """
    ...
```

### 入力値の条件

- `products (list[tuple[str, int]])`: 商品名と価格のリスト
  - `0 <= len(products) <= 10^6`
  - 各商品は `(商品名: str, 価格: int)` のタプルで表される
  - `商品名` は英数字を含む長さ 1 以上 50 以下の文字列
  - `価格` は `1 <= 価格 <= 10^6`

## 出力

- **list[tuple[str, int]]**:
  - `products` から **同じ商品名の重複を排除** し、**価格が高い順にソート** したリストを返してください。
  - 価格が同じ場合、**先に登場した順序** を保持してください。

## サンプル1
```python
assert remove_duplicate_products([("apple", 300), ("banana", 200), ("apple", 250), ("orange", 400)]) == [("orange", 400), ("apple", 300), ("banana", 200)]
```


**解説**
- `"apple"` が2回登場 (`300` と `250`) しているが、高い方の `300` を採用。
- `"orange"` が `400` で最も高価なので、最上位に。
- `"apple"` の次に `"banana"` の順番で並ぶ。

## サンプル2
```python
assert remove_duplicate_products([("watch", 5000), ("watch", 5000), ("ring", 7000), ("ring", 6500)]) == [("ring", 7000), ("watch", 5000)]
```


**解説**
- `"watch"` は同じ価格 `5000` で2回登場 → 1つだけ残す。
- `"ring"` は `7000` と `6500` があるが、高い方の `7000` を採用。

## サンプル3
```python
assert remove_duplicate_products([("pen", 100), ("notebook", 200), ("eraser", 50), ("pen", 150)]) == [("pen", 150), ("notebook", 200), ("eraser", 50)]
```


**解説**
- `"pen"` は `100` と `150` で2回登場 → `150` を採用。
- `"notebook"` の `200` は変更なし。
- `"eraser"` の `50` も変更なし。
- **価格の降順に並べ替え** るため `"pen"` が `"notebook"` より上位になる。

## サンプル4
```python
assert remove_duplicate_products([]) == []
```


**解説**
- 入力リストが空なので、空のリストを返す。

## サンプル5
```python
assert remove_duplicate_products([("bag", 1200), ("shoes", 3000), ("bag", 1000), ("hat", 2500)]) == [("shoes", 3000), ("hat", 2500), ("bag", 1200)]
```


**解説**
- `"bag"` は `1200` と `1000` があるが、高い方の `1200` を採用。
- `"shoes"` (`3000`) → `"hat"` (`2500`) → `"bag"` (`1200`) の順で並ぶ。
