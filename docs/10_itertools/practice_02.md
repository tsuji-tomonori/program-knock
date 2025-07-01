# 問題(10B): Hit & Blow

## 問題文

Hit & Blow は、プレイヤーが特定の桁数の数値を推測し、正解との一致度をもとに次の推測を決めるゲームです。
この問題では、指定された数値のリストとプレイヤーの推測を比較し、Hit 数と Blow 数を求める関数を実装してください。

### ルール

- **Hit**: 正解の数値の中で、位置も含めて一致しているものの数
- **Blow**: 正解の数値の中で、位置は異なるが含まれているものの数

例えば、正解が `[1, 2, 3, 4]` の場合:
- 推測 `[1, 3, 2, 5]` → `Hit = 1` (`1` の位置が一致) , `Blow = 2` (`2, 3` は含まれるが位置が違う)

## 入力

```python
from typing import List, Tuple

def hit_and_blow(answer: List[int], guess: List[int]) -> Tuple[int, int]:
    """
    Hit & Blow の判定を行う関数

    Args:
        answer (List[int]): 正解の数値のリスト
        guess (List[int]): プレイヤーの推測リスト (同じ長さ)

    Returns:
        Tuple[int, int]: (Hit数, Blow数)
    """
    ...
```

### 入力条件
- `answer` と `guess` は同じ長さのリスト
- `answer` の要素は `0` 以上 `9` 以下の整数
- `guess` の要素も `0` 以上 `9` 以下の整数
- `answer` の要素には重複がない
- `guess` の要素には重複がない

## 出力
- `(Hit数, Blow数)` の形式でタプルを返す

## サンプル1
```python
def test_case_1():
    assert hit_and_blow([1, 2, 3, 4], [1, 3, 2, 5]) == (1, 2)
```
**解説**
- **Hit**: `1` の位置が一致 → `Hit = 1`
- **Blow**: `2, 3` が含まれるが位置違い → `Blow = 2`

## サンプル2
```python
def test_case_2():
    assert hit_and_blow([5, 6, 7, 8], [8, 7, 6, 5]) == (0, 4)
```
**解説**
- **Hit**: なし (`Hit = 0`)
- **Blow**: `5, 6, 7, 8` は全て含まれるが、位置が違う → `Blow = 4`

## サンプル3
```python
def test_case_3():
    assert hit_and_blow([3, 1, 4, 7], [3, 1, 4, 7]) == (4, 0)
```
**解説**
- **Hit**: すべての位置が一致 → `Hit = 4`
- **Blow**: なし (`Blow = 0`)

## サンプル4
```python
def test_case_4():
    assert hit_and_blow([9, 8, 7, 6], [1, 2, 3, 4]) == (0, 0)
```
**解説**
- **Hit**: なし (`Hit = 0`)
- **Blow**: なし (`Blow = 0`)
