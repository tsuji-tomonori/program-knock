# 問題(04B): LRUキャッシュの実装

## 問題文

LRU（Least Recently Used）キャッシュは、使用頻度の低いデータを削除しながら、一定のサイズ内でデータを保持するデータ構造です。

この問題では、LRU キャッシュを実装してください。

## 入力

```python
class LRUCache:
    def __init__(self, capacity: int):
        """
        LRU キャッシュを初期化する。

        Args:
            capacity (int): キャッシュの最大容量（1以上）
        """
        ...

    def get(self, key: int) -> int:
        """
        指定されたキーに対応する値を取得する。
        キーがキャッシュに存在しない場合は -1 を返す。

        Args:
            key (int): 検索するキー

        Returns:
            int: キーに対応する値、または -1
        """
        ...

    def put(self, key: int, value: int) -> None:
        """
        指定されたキーと値をキャッシュに格納する。
        すでにキーが存在する場合は値を更新する。
        容量を超えた場合は、最も長い間使われていないキーを削除する。

        Args:
            key (int): 格納するキー
            value (int): 格納する値
        """
        ...
```

### 挙動

- `get(key)`:
  - キーがキャッシュ内にあれば、対応する値を返し、そのキーを最近使用されたものとして更新する。
  - キーがなければ `-1` を返す。

- `put(key, value)`:
  - キーがすでに存在する場合は、値を更新し、そのキーを最近使用されたものとして更新する。
  - 新しいキーを追加し、容量を超える場合は、最も長い間使われていないキーを削除する。

## サンプル1

```python
import pytest
from src.lru_cache import LRUCache

def test_lru_cache():
    cache = LRUCache(2)  # 容量 2 の LRU キャッシュを作成

    cache.put(1, 1)
    cache.put(2, 2)
    assert cache.get(1) == 1  # 1 はキャッシュに存在する

    cache.put(3, 3)  # 2 が削除される（LRU ルールによる）
    assert cache.get(2) == -1  # 2 は削除されたため取得できない

    cache.put(4, 4)  # 1 が削除される（LRU ルールによる）
    assert cache.get(1) == -1  # 1 は削除されたため取得できない
    assert cache.get(3) == 3  # 3 はキャッシュに残っている
    assert cache.get(4) == 4  # 4 はキャッシュに残っている
```
