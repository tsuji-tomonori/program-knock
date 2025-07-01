# 問題(04A): 単語の出現回数のカウント

## 問題文

文章の中に含まれる各単語の出現回数をカウントする関数を実装してください。

この関数は、入力されたテキストを単語ごとに分割し、各単語が何回登場するかを辞書の形で返します。

## 入力

```python
def count_word_frequencies(text: str) -> dict[str, int]:
    """
    入力されたテキスト中の各単語の出現回数をカウントする関数.

    Args:
        text (str): 空白区切りの単語を含む文字列。

    Returns:
        dict[str, int]: 各単語をキーとし、その出現回数を値とする辞書。
    """
    ...
```

### 入力値の条件

- `text` (str): 英小文字のみからなる単語が、スペースで区切られた形の文字列
  - 各単語は `a` から `z` のみを含む（特殊文字や大文字は含まれない）。
  - 単語間は必ず 1 つのスペースで区切られている。
  - `text` の長さは `0 <= len(text) <= 10^6`。

## 出力

- **辞書型 (`dict[str, int]`)**:
  - 各単語をキーとし、その出現回数を値とする辞書を返します。
  - 出現回数が高い順、同じ場合は辞書のキーの辞書順で並べる。

## サンプル1

```python
def test_basic():
    text = "apple banana apple orange banana apple"
    assert count_word_frequencies(text) == {
        "apple": 3,
        "banana": 2,
        "orange": 1
    }
```

**解説**

- `apple` は 3 回出現
- `banana` は 2 回出現
- `orange` は 1 回出現
- 出現回数の降順で並べるため、`{"apple": 3, "banana": 2, "orange": 1}` となる

## サンプル2

```python
def test_single_word():
    text = "python"
    assert count_word_frequencies(text) == {
        "python": 1
    }
```

**解説**

- `python` は 1 回のみ出現
- `{"python": 1}` を返す

## サンプル3

```python
def test_tied_frequencies():
    text = "dog cat bird cat dog bird"
    assert count_word_frequencies(text) == {
        "bird": 2,
        "cat": 2,
        "dog": 2
    }
```

**解説**

- `dog`, `cat`, `bird` はそれぞれ 2 回出現
- 同じ回数の場合は辞書順にするため、`{"bird": 2, "cat": 2, "dog": 2}` となる

## サンプル4

```python
def test_empty_string():
    text = ""
    assert count_word_frequencies(text) == {}
```

**解説**

- 空の文字列が入力された場合、単語は存在しないため `{}` を返す
