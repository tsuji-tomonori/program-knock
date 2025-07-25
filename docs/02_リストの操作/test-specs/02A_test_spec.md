# テスト仕様書: 02A_langtons_ant

## 概要
ラングトンのアリ(Langton's Ant)シミュレーションのテスト仕様書

## 基本機能テスト

### No.1: サンプルケース検証(0ステップ)
- **テスト概要**: 0ステップ後の初期状態確認
- **input**: `steps=0`
- **期待値**: `[(0, 0)]`
- **テストの目的**: 初期状態でのアリの位置確認

### No.2: サンプルケース検証(1ステップ)
- **テスト概要**: 1ステップ後の状態確認
- **input**: `steps=1`
- **期待値**: `[(0, 0), (0, 1)]`
- **テストの目的**: 基本的な移動と色変更ロジックの確認

### No.3: サンプルケース検証(5ステップ)
- **テスト概要**: 5ステップ後の状態確認
- **input**: `steps=5`
- **期待値**: `[(0, 0), (0, 1), (1, 0), (1, 1)]`
- **テストの目的**: 複数ステップでのパス追跡の正確性確認

## エッジケーステスト

### No.4: 長期シミュレーション
- **テスト概要**: 大量ステップでのシミュレーション
- **input**: `steps=1000`
- **期待値**: 正しい座標リスト(ソート済み)
- **テストの目的**: 長期実行での安定性とパフォーマンス確認

### No.5: 方向転換確認
- **テスト概要**: 各方向での転換動作確認
- **input**: `steps=4` (完全な回転を確認)
- **期待値**: 方向変換の正確性
- **テストの目的**: 右折・左折ロジックの正確性確認

### No.6: 色状態管理
- **テスト概要**: 白→黒→白の色変化確認
- **input**: 同じ座標を複数回通るケース
- **期待値**: 正しい色状態管理
- **テストの目的**: セル状態の管理とトグル動作確認

### No.7: 座標範囲確認
- **テスト概要**: 負の座標への移動確認
- **input**: `steps=50` (負座標に移動するまで)
- **期待値**: 負座標を含む正しいリスト
- **テストの目的**: 座標系の制限なし動作確認

## アルゴリズム精度テスト

### No.8: パス重複確認
- **テスト概要**: 同じ座標を複数回通った場合の処理
- **input**: `steps=100`
- **期待値**: 重複なしの座標リスト
- **テストの目的**: 座標重複排除の正確性確認

### No.9: ソート順序確認
- **テスト概要**: 結果座標のソート順確認
- **input**: `steps=20`
- **期待値**: `(x, y)`の昇順ソート済みリスト
- **テストの目的**: 出力フォーマットの正確性確認

### No.10: 初期方向設定
- **テスト概要**: 初期方向(上)からの動作確認
- **input**: `steps=8` (2周分)
- **期待値**: 期待されるパターン
- **テストの目的**: 初期状態設定の正確性確認

## 特殊パターンテスト

### No.11: 周期パターン確認
- **テスト概要**: 既知の周期パターンの検証
- **input**: `steps=特定の周期数`
- **期待値**: 理論的に期待されるパターン
- **テストの目的**: アルゴリズムの理論的正確性確認

### No.12: 対称性確認
- **テスト概要**: パターンの対称性検証
- **input**: `steps=104` (既知の対称パターン)
- **期待値**: 対称的な座標分布
- **テストの目的**: 長期実行でのパターン形成確認

### No.13: 境界動作確認
- **テスト概要**: 大きな座標値での動作確認
- **input**: `steps=10000`
- **期待値**: 正しい大座標値処理
- **テストの目的**: 大きな数値での計算精度確認

## パフォーマンステスト

### No.14: 大規模ステップ実行
- **テスト概要**: 最大ステップ数での処理性能
- **input**: `steps=100000`
- **期待値**: 正しい結果 (実行時間10秒以内)
- **テストの目的**: 大規模データでの処理効率とメモリ使用量確認
