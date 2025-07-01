# 問題(07A): 疑似スコープの実装

## 問題文

Python では、変数のスコープ (scope) により、変数の有効範囲が決まります。
この問題では、**スコープの概念を模倣するデータ構造** を実装し、擬似的にスコープの動作を再現してください。

具体的には、**ネスト可能なスコープ管理システム** を作成し、以下の機能を実装してください。

### 要件

`ScopeManager` クラスを実装してください。このクラスは **スコープの管理** を行い、ネストされたスコープ内での変数の保存や取得を模倣します。

### クラス `ScopeManager` の仕様

1. `enter_scope() -> None`
   - **新しいスコープを作成し、現在のスコープを一段深くする**。

2. `exit_scope() -> None`
   - **現在のスコープを破棄し、1 つ上のスコープに戻る**。
   - 最上位のスコープ (`global` スコープ) を削除しようとした場合は **何もしない**。

3. `set_variable(name: str, value: int) -> None`
   - **現在のスコープに `name` をキーとして `value` を保存する**。

4. `get_variable(name: str) -> int | None`
   - **現在のスコープから順に `name` を探し、見つかった場合はその値を返す**。
   - **現在のスコープになければ外側のスコープを順に遡って探す**（レキシカルスコープの動作）。
   - 変数がどのスコープにも存在しない場合は `None` を返す。

## サンプル1

**操作手順:**
1. ScopeManagerを作成
2. `set_variable("x", 10)` - グローバルスコープにxを設定
3. `get_variable("x")` → 出力: `10`
4. `enter_scope()` - 新しいスコープに移行
5. `get_variable("x")` → 出力: `10` (外側のスコープの値を取得)
6. `set_variable("x", 20)` - 内側のスコープでxを上書き
7. `get_variable("x")` → 出力: `20` (内側のスコープの値)
8. `exit_scope()` - スコープを戻す
9. `get_variable("x")` → 出力: `10` (外側のスコープの値に戻る)
**解説**
- `set_variable("x", 10)` で **グローバルスコープ** に変数 `x` を 10 で設定。
- `enter_scope()` で **新しいスコープに移行** するが、外側の `x` は取得可能。
- `set_variable("x", 20)` で **新しいスコープ内の `x` を 20 に変更**。
- `exit_scope()` で **スコープを戻すと、`x` の値は再び 10 になる**。

## サンプル2

**操作手順:**
1. ScopeManagerを作成
2. `set_variable("y", 5)` - グローバルスコープにyを設定
3. `enter_scope()` - 1段目のスコープに移行
4. `enter_scope()` - 2段目のスコープに移行
5. `set_variable("z", 30)` - 最も内側のスコープにzを設定
6. `get_variable("z")` → 出力: `30`
7. `get_variable("y")` → 出力: `5` (外側のスコープから取得)
8. `exit_scope()` - 2段目のスコープを削除
9. `get_variable("z")` → 出力: `None` (zのスコープが削除された)

**解説**
- `set_variable("y", 5)` で **グローバルスコープ** に `y` を 5 で設定。
- `enter_scope()` を 2 回実行し、 **2 段ネストしたスコープ** を作成。
- `set_variable("z", 30)` で **最も内側のスコープ** に `z` を追加。
- `get_variable("y")` は、 **スコープを遡って取得可能** なので 5 を返す。
- `exit_scope()` すると、 `z` のスコープが削除され、 `get_variable("z")` は `None` を返す。

## サンプル3
```python
sm = ScopeManager()
assert sm.get_variable("unknown") == None  # 未設定の変数

sm.enter_scope()
assert sm.get_variable("unknown") == None  # 内側でも変数が見つからない
```

**解説**
- `get_variable("unknown")` は、 **どのスコープにも存在しないため `None` を返す**。
- `enter_scope()` をしても、 **新しいスコープに `unknown` は存在しないため `None`** 。

## サンプル4
```python
sm = ScopeManager()
sm.set_variable("a", 100)
sm.set_variable("b", 200)

sm.enter_scope()
sm.set_variable("b", 300)  # "b" を新しいスコープで上書き
assert sm.get_variable("a") == 100
assert sm.get_variable("b") == 300

sm.exit_scope()
assert sm.get_variable("b") == 200  # 内側の "b" は破棄される
```

**解説**
- `set_variable("a", 100)` で `a = 100` を設定、 `b = 200` も同様。
- `enter_scope()` で **新しいスコープ** を作成し、 `b = 300` に上書き。
- `exit_scope()` で **スコープを戻すと、上書きされた `b = 300` は削除され、外側の `b = 200` に戻る**。

## サンプル5
```python
sm = ScopeManager()
sm.exit_scope()  # 最上位スコープでの exit は何もしない
sm.set_variable("x", 5)
sm.exit_scope()  # これも無視される
assert sm.get_variable("x") == 5  # "x" はまだ存在する
```

**解説**
- **グローバルスコープでは `exit_scope()` を呼んでも何も起こらない**。
- `set_variable("x", 5)` で `x` を設定後、 `exit_scope()` を呼んでも **グローバルスコープは削除されないため、`x` はそのまま残る**。
