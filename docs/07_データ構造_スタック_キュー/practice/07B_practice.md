# 問題(07B): 回転寿司屋の席案内システム

## 問題文

回転寿司屋では、お客さんが来店すると **受付で番号を取得し、順番に席へ案内** されます。
また、満席の場合は **席が空くまで待つ** 必要があります。
このシステムを実装してください。

お客さんの動作は以下の 2 種類です：

1. **"arrive name"**:
   - `name` という名前のお客さんが来店し、待機リストに追加される。
   - ただし、すでに `name` が待機リストに存在する場合は追加しない。

2. **"seat N"**:
   - `N` 席が空いたため、**待機リストの先頭から順に `N` 人が席へ案内** される。
   - もし待機リストが `N` 人未満の場合は、**全員が席へ案内** される。

## 入力

- `commands` (文字列のリスト): 到着・席案内のコマンドリスト

### 入力値の条件

- `commands` の各要素は以下の形式のいずれかである：
  - `"arrive name"` (`name` は英数字の文字列)
  - `"seat N"` (`N` は `1 <= N <= 100`)
- `1 <= len(commands) <= 1000`
- 同じ `name` のお客さんが **複数回 "arrive" することはない**
- `"seat"` コマンドは待機リストが空でも実行可能（この場合は何もしない）

## 出力

- 席へ案内されたお客さんの名前を、案内順に **リスト** で出力してください。

# サンプル1

**入力:**
```
commands = [
    "arrive Alice",
    "arrive Bob",
    "seat 1",
    "arrive Charlie",
    "seat 2",
    "arrive Dave",
    "arrive Eve",
    "seat 3"
]
```

**出力:**
```
["Alice", "Bob", "Charlie", "Dave", "Eve"]
```

**解説**

- `"arrive Alice"` → 待機リスト: `[Alice]`
- `"arrive Bob"` → 待機リスト: `[Alice, Bob]`
- `"seat 1"` → `Alice` を席へ案内 → 出力 `["Alice"]`
- `"arrive Charlie"` → 待機リスト: `[Bob, Charlie]`
- `"seat 2"` → `Bob, Charlie` を席へ案内 → 出力 `["Alice", "Bob", "Charlie"]`
- `"arrive Dave"` → 待機リスト: `[Dave]`
- `"arrive Eve"` → 待機リスト: `[Dave, Eve]`
- `"seat 3"` → `Dave, Eve` を席へ案内 → 出力 `["Alice", "Bob", "Charlie", "Dave", "Eve"]`

## サンプル2

**入力:**
```
commands = [
    "arrive Tom",
    "arrive Jerry",
    "arrive Spike",
    "seat 2",
    "arrive Butch",
    "seat 2"
]
```

**出力:**
```
["Tom", "Jerry", "Spike", "Butch"]
```

**解説**

- `"arrive Tom"` → `[Tom]`
- `"arrive Jerry"` → `[Tom, Jerry]`
- `"arrive Spike"` → `[Tom, Jerry, Spike]`
- `"seat 2"` → `Tom, Jerry` を案内 → 出力 `["Tom", "Jerry"]`
- `"arrive Butch"` → `[Spike, Butch]`
- `"seat 2"` → `Spike, Butch` を案内 → 出力 `["Tom", "Jerry", "Spike", "Butch"]`

## サンプル3

**入力:**
```
commands = [
    "arrive Anna",
    "arrive Elsa",
    "seat 5"
]
```

**出力:**
```
["Anna", "Elsa"]
```

**解説**

- `"arrive Anna"` → `[Anna]`
- `"arrive Elsa"` → `[Anna, Elsa]`
- `"seat 5"` → `Anna, Elsa` を案内（5 席空いたが 2 人しか待機していない） → 出力 `["Anna", "Elsa"]`
