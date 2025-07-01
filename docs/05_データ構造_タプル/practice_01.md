# 問題(05A): 商品ごとの売上集計

## 問題文

ある店舗での売上データが記録されています。各売上データは、どの商品がいくつ売れたのかを表しています。
異なる店舗や異なる支払い方法によって、商品の売上を集計したいと考えています。

売上データは以下の形式で与えられます。

- **店舗名 (store)**: 商品が販売された店舗の名前（文字列）
- **支払い方法 (payment_method)**: 現金・クレジットカードなどの支払い方法（文字列）
- **商品名 (product)**: 売れた商品の名前（文字列）
- **数量 (quantity)**: 売れた商品の数（正の整数）

売上データのリストを受け取り、各 **(店舗名, 支払い方法, 商品名)** ごとの売上数量を集計し、辞書で返す関数を実装してください。

## 入力

### 売上データ（Sale）
- `store` (文字列): 店舗名
- `payment_method` (文字列): 支払い方法
- `product` (文字列): 商品名
- `quantity` (整数): 売上数量（1以上の整数）

### パラメータ
- `sales` (リスト): 売上データのリスト

### 入力値の条件

- `sales` は `Sale` のリストであり、各要素は以下の形式を持ちます。
  - `store` (str): 任意の文字列
  - `payment_method` (str): 任意の文字列
  - `product` (str): 任意の文字列
  - `quantity` (int): `1 以上` の整数

## 出力

- **タプルと整数の辞書**: `(店舗名, 支払い方法, 商品名)` のタプルをキーとし、売上数量の合計を値とする辞書を返します。

## サンプル1

**入力:**
売上データのリスト:
- (「Tokyo」, 「Credit」, 「Apple」, 3)
- (「Tokyo」, 「Cash」, 「Apple」, 2)
- (「Osaka」, 「Credit」, 「Apple」, 5)
- (「Tokyo」, 「Credit」, 「Apple」, 1)
- (「Osaka」, 「Credit」, 「Orange」, 4)
- (「Tokyo」, 「Cash」, 「Banana」, 2)
- (「Tokyo」, 「Credit」, 「Apple」, 2)

**出力:**
```
{("Tokyo", "Credit", "Apple"): 6,
 ("Tokyo", "Cash", "Apple"): 2,
 ("Osaka", "Credit", "Apple"): 5,
 ("Osaka", "Credit", "Orange"): 4,
 ("Tokyo", "Cash", "Banana"): 2}
```

**解説**

- **`("Tokyo", "Credit", "Apple")`**:
  - 3 + 1 + 2 = **6**
- **`("Tokyo", "Cash", "Apple")`**:
  - 2 = **2**
- **`("Osaka", "Credit", "Apple")`**:
  - 5 = **5**
- **`("Osaka", "Credit", "Orange")`**:
  - 4 = **4**
- **`("Tokyo", "Cash", "Banana")`**:
  - 2 = **2**

## サンプル2

**入力:** 空のリスト

**出力:** `{}`

**解説**

- 入力が空リストの場合、辞書も空である `{}` を返します。

## サンプル3

**入力:**
売上データのリスト:
- (「Tokyo」, 「Credit」, 「Apple」, 4)
- (「Tokyo」, 「Cash」, 「Apple」, 5)
- (「Tokyo」, 「Credit」, 「Apple」, 3)
- (「Tokyo」, 「MobilePay」, 「Apple」, 2)

**出力:**
```
{("Tokyo", "Credit", "Apple"): 7,
 ("Tokyo", "Cash", "Apple"): 5,
 ("Tokyo", "MobilePay", "Apple"): 2}
```

**解説**

- **`("Tokyo", "Credit", "Apple")`**:
  - 4 + 3 = **7**
- **`("Tokyo", "Cash", "Apple")`**:
  - 5 = **5**
- **`("Tokyo", "MobilePay", "Apple")`**:
  - 2 = **2**
