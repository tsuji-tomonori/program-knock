# テスト仕様書: 04A_word_count

## 概要
単語の出現回数カウント機能のテスト仕様書

## 基本機能テスト

### No.1: サンプルケース1検証
- **テスト概要**: 問題文のサンプルケース1を検証
- **input**: `"apple banana apple orange banana apple"`
- **期待値**: `{"apple": 3, "banana": 2, "orange": 1}`
- **テストの目的**: 基本的な単語カウントと出現回数順ソートの正確性を確認

### No.2: サンプルケース2検証
- **テスト概要**: 問題文のサンプルケース2を検証（単一単語）
- **input**: `"python"`
- **期待値**: `{"python": 1}`
- **テストの目的**: 単一単語での処理を確認

### No.3: サンプルケース3検証
- **テスト概要**: 問題文のサンプルケース3を検証（同頻度の辞書順）
- **input**: `"dog cat bird cat dog bird"`
- **期待値**: `{"bird": 2, "cat": 2, "dog": 2}`
- **テストの目的**: 同頻度単語の辞書順ソートを確認

## エッジケーステスト

### No.4: 空文字列処理
- **テスト概要**: 問題文のサンプルケース4を検証（空文字列）
- **input**: `""`
- **期待値**: `{}`
- **テストの目的**: 空文字列での処理を確認

### No.5: 単一文字単語
- **テスト概要**: 1文字の単語のみで構成
- **input**: `"a b c a b a"`
- **期待値**: `{"a": 3, "b": 2, "c": 1}`
- **テストの目的**: 最短単語での処理精度

### No.6: 同じ単語の連続
- **テスト概要**: 同じ単語が連続で出現
- **input**: `"hello hello hello world world"`
- **期待値**: `{"hello": 3, "world": 2}`
- **テストの目的**: 連続同一単語の正確なカウント

### No.7: 長い単語
- **テスト概要**: 非常に長い単語を含む
- **input**: `"supercalifragilisticexpialidocious test supercalifragilisticexpialidocious"`
- **期待値**: `{"supercalifragilisticexpialidocious": 2, "test": 1}`
- **テストの目的**: 長い単語での処理確認

## 精度・境界値テスト

### No.8: アルファベット順境界
- **テスト概要**: アルファベット順の境界文字
- **input**: `"aaa aaaa aaab aaaa aaa aaab"`
- **期待値**: `{"aaa": 2, "aaaa": 2, "aaab": 2}`
- **テストの目的**: 辞書順ソートの境界値確認

### No.9: 大量単語データ
- **テスト概要**: 多数の異なる単語
- **input**: `100個の異なる単語（各1回ずつ）`
- **期待値**: 辞書順にソートされた100個のエントリー
- **テストの目的**: 大量データでの辞書順ソート精度

### No.10: 同一頻度大量単語
- **テスト概要**: 同じ頻度の大量単語
- **input**: `50個の単語（各2回ずつ）`
- **期待値**: すべて頻度2で辞書順にソート
- **テストの目的**: 同頻度大量データでの辞書順精度

## 特殊機能テスト

### No.11: 頻度混合パターン
- **テスト概要**: 様々な頻度が混在
- **input**: `"a a a b b c d d d d e e e e e"`
- **期待値**: `{"e": 5, "d": 4, "a": 3, "b": 2, "c": 1}`
- **テストの目的**: 複雑な頻度パターンでのソート精度

### No.12: 最長単語制限
- **テスト概要**: 長い単語の処理限界
- **input**: `26文字の単語（abcdefghijklmnopqrstuvwxyz）を含む`
- **期待値**: 正確なカウント結果
- **テストの目的**: 最長単語での処理確認

### No.13: 頻度逆転パターン
- **テスト概要**: 途中で頻度順序が変わる
- **input**: `"z z y y y x x x x"`
- **期待値**: `{"x": 4, "y": 3, "z": 2}`
- **テストの目的**: 頻度集計の正確性確認

## パフォーマンステスト

### No.14: 大規模テキスト処理
- **テスト概要**: 最大長テキストでの処理性能
- **input**: `100万文字程度のテキスト（重複単語含む）`
- **期待値**: 正確なカウント結果（処理時間3秒以内）
- **テストの目的**: 大規模データでの処理効率と正確性
