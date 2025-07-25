# テスト仕様書: 06A_customer_deduplication

## 概要
顧客データの重複排除（順序保持）機能のテスト仕様書

## 基本機能テスト

### No.1: サンプルケース1検証
- **テスト概要**: 問題文のサンプルケース1を検証
- **input**: `[101, 202, 303, 101, 404, 202, 505]`
- **期待値**: `(101, 202, 303, 404, 505)`
- **テストの目的**: 基本的な重複排除と順序保持の正確性を確認

### No.2: サンプルケース2検証
- **テスト概要**: 問題文のサンプルケース2を検証（重複なし）
- **input**: `[1, 2, 3, 4, 5, 6, 7, 8, 9]`
- **期待値**: `(1, 2, 3, 4, 5, 6, 7, 8, 9)`
- **テストの目的**: 重複がない場合の処理を確認

### No.3: サンプルケース3検証
- **テスト概要**: 問題文のサンプルケース3を検証（全て同じ）
- **input**: `[42, 42, 42, 42, 42]`
- **期待値**: `(42,)`
- **テストの目的**: 全て同じ値の場合の処理を確認

## エッジケーステスト

### No.4: 空リストの処理
- **テスト概要**: 問題文のサンプルケース4を検証（空リスト）
- **input**: `[]`
- **期待値**: `()`
- **テストの目的**: 空リストでの処理を確認

### No.5: 負の値を含む処理
- **テスト概要**: 問題文のサンプルケース5を検証（負の値含む）
- **input**: `[500, -1, 500, -1, 200, 300, 200, -100]`
- **期待値**: `(500, -1, 200, 300, -100)`
- **テストの目的**: 負の値での処理を確認

### No.6: 単一要素
- **テスト概要**: 要素が1つのみの場合
- **input**: `[999]`
- **期待値**: `(999,)`
- **テストの目的**: 最小データでの処理確認

### No.7: 2要素の重複
- **テスト概要**: 2要素が重複する最小ケース
- **input**: `[10, 10]`
- **期待値**: `(10,)`
- **テストの目的**: 最小重複での処理確認

## 精度・境界値テスト

### No.8: 最大値の処理
- **テスト概要**: 制約の最大値での処理
- **input**: `[1000000, 1000000, -1000000, -1000000]`
- **期待値**: `(1000000, -1000000)`
- **テストの目的**: 境界値での処理確認

### No.9: 大量重複データ
- **テスト概要**: 同じ値が大量に重複
- **input**: `[5] * 1000`（5が1000個）
- **期待値**: `(5,)`
- **テストの目的**: 大量重複での処理効率

### No.10: 逆順パターン
- **テスト概要**: 逆順で重複が発生
- **input**: `[5, 4, 3, 2, 1, 5, 4, 3, 2, 1]`
- **期待値**: `(5, 4, 3, 2, 1)`
- **テストの目的**: 逆順パターンでの順序保持

## 特殊機能テスト

### No.11: 交互重複パターン
- **テスト概要**: 複数の値が交互に重複
- **input**: `[1, 2, 1, 3, 2, 4, 1, 3, 2, 4]`
- **期待値**: `(1, 2, 3, 4)`
- **テストの目的**: 複雑な交互重複での順序保持

### No.12: 連続重複パターン
- **テスト概要**: 連続する同じ値の塊
- **input**: `[1, 1, 1, 2, 2, 3, 3, 3, 3, 4]`
- **期待値**: `(1, 2, 3, 4)`
- **テストの目的**: 連続重複での処理確認

### No.13: ゼロを含む処理
- **テスト概要**: 0を含むデータでの処理
- **input**: `[0, 1, 2, 0, -1, 2, 0]`
- **期待値**: `(0, 1, 2, -1)`
- **テストの目的**: 0値での処理確認

## パフォーマンステスト

### No.14: 大規模データ処理
- **テスト概要**: 最大規模のデータでの処理性能
- **input**: `100万件のランダムな顧客ID（重複率50%）`
- **期待値**: 重複排除された順序保持タプル（処理時間3秒以内）
- **テストの目的**: 大規模データでの処理効率と正確性
