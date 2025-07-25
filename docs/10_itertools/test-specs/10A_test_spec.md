# テスト仕様書: 10A_run_length_encoding

## 概要
ランレングス符号化（Run-Length Encoding）のテスト仕様書

## 基本機能テスト

### No.1: サンプルケース1検証
- **テスト概要**: 問題文のサンプルケース1を検証
- **input**: `"aaabbcdddd"`
- **期待値**: `[("a", 3), ("b", 2), ("c", 1), ("d", 4)]`
- **テストの目的**: 基本的なランレングス符号化の正確性を確認

### No.2: サンプルケース2検証
- **テスト概要**: 問題文のサンプルケース2を検証（全て異なる文字）
- **input**: `"abc"`
- **期待値**: `[("a", 1), ("b", 1), ("c", 1)]`
- **テストの目的**: 連続しない文字での処理を確認

### No.3: サンプルケース3検証
- **テスト概要**: 問題文のサンプルケース3を検証（同一文字のみ）
- **input**: `"aaaaaaa"`
- **期待値**: `[("a", 7)]`
- **テストの目的**: 同一文字の連続での処理を確認

## エッジケーステスト

### No.4: 単一文字
- **テスト概要**: 1文字のみの文字列
- **input**: `"a"`
- **期待値**: `[("a", 1)]`
- **テストの目的**: 最小入力での処理確認

### No.5: 交互パターン
- **テスト概要**: 文字が交互に現れるパターン
- **input**: `"abababab"`
- **期待値**: `[("a", 1), ("b", 1), ("a", 1), ("b", 1), ("a", 1), ("b", 1), ("a", 1), ("b", 1)]`
- **テストの目的**: 交互パターンでの処理確認

### No.6: 長い連続と短い連続の混合
- **テスト概要**: 長い連続と短い連続が混在
- **input**: `"aabbbbbbccdeeeee"`
- **期待値**: `[("a", 2), ("b", 6), ("c", 2), ("d", 1), ("e", 5)]`
- **テストの目的**: 様々な長さの連続での処理確認

### No.7: アルファベット全種類
- **テスト概要**: a-zの全文字を含む
- **input**: `"abcdefghijklmnopqrstuvwxyz"`
- **期待値**: `[("a", 1), ("b", 1), ..., ("z", 1)]`（26個のタプル）
- **テストの目的**: 全アルファベットでの処理確認

## 精度・境界値テスト

### No.8: 最大長の単一文字
- **テスト概要**: 制約の最大文字数での単一文字
- **input**: `"a" * 100000`（10万個のa）
- **期待値**: `[("a", 100000)]`
- **テストの目的**: 最大長での処理確認

### No.9: 最大長の交互パターン
- **テスト概要**: 最大長での交互パターン
- **input**: `"ab" * 50000`（10万文字の交互パターン）
- **期待値**: `[("a", 1), ("b", 1)] * 50000`
- **テストの目的**: 最大長での複雑パターン処理

### No.10: 大きな連続数
- **テスト概要**: 大きな数での連続
- **input**: `"a" * 50000 + "b" * 50000`
- **期待値**: `[("a", 50000), ("b", 50000)]`
- **テストの目的**: 大きな連続数での処理確認

## 特殊機能テスト

### No.11: 対称パターン
- **テスト概要**: 対称的な文字パターン
- **input**: `"abccba"`
- **期待値**: `[("a", 1), ("b", 1), ("c", 2), ("b", 1), ("a", 1)]`
- **テストの目的**: 対称パターンでの処理確認

### No.12: 複雑な連続パターン
- **テスト概要**: 複雑な連続の組み合わせ
- **input**: `"aaabbbaaacccaaabbb"`
- **期待値**: `[("a", 3), ("b", 3), ("a", 3), ("c", 3), ("a", 3), ("b", 3)]`
- **テストの目的**: 複雑パターンでの正確性

### No.13: 昇順・降順パターン
- **テスト概要**: アルファベット順のパターン
- **input**: `"aaabbcccdddeeee"`
- **期待値**: `[("a", 3), ("b", 2), ("c", 3), ("d", 3), ("e", 4)]`
- **テストの目的**: 順序パターンでの処理確認

## パフォーマンステスト

### No.14: 大規模複雑パターン処理
- **テスト概要**: 最大制約での複雑パターン
- **input**: `10万文字の複雑なランレングスパターン`
- **期待値**: 正確な符号化結果（処理時間2秒以内）
- **テストの目的**: 大規模データでの処理効率と正確性
