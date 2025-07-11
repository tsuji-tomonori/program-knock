# 問題(04B): LRUキャッシュの実装

## 問題文

LRU（Least Recently Used）キャッシュは、使用頻度の低いデータを削除しながら、一定のサイズ内でデータを保持するデータ構造です。

この問題では、LRU キャッシュを実装してください。

## 入力

### LRUキャッシュのコンストラクタ
- `capacity` (整数): キャッシュの最大容量（1以上）

### getメソッド
- `key` (整数): 検索するキー
- 戻り値: キーに対応する値、または -1

### putメソッド
- `key` (整数): 格納するキー
- `value` (整数): 格納する値

### 挙動

- `get(key)`:
  - キーがキャッシュ内にあれば、対応する値を返し、そのキーを最近使用されたものとして更新する。
  - キーがなければ `-1` を返す。

- `put(key, value)`:
  - キーがすでに存在する場合は、値を更新し、そのキーを最近使用されたものとして更新する。
  - 新しいキーを追加し、容量を超える場合は、最も長い間使われていないキーを削除する。

## サンプル1

**操作手順:**
1. 容量2のLRUキャッシュを作成
2. `put(1, 1)` - キー1に値1を格納
3. `put(2, 2)` - キー2に値2を格納
4. `get(1)` → 出力: `1`
5. `put(3, 3)` - キー3に値3を格納（キー2が削除される）
6. `get(2)` → 出力: `-1`
7. `put(4, 4)` - キー4に値4を格納（キー1が削除される）
8. `get(1)` → 出力: `-1`
9. `get(3)` → 出力: `3`
10. `get(4)` → 出力: `4`
