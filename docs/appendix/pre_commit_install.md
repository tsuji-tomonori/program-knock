# Pre-commit導入手順

このドキュメントでは、リポジトリにpre-commitを導入する手順を説明します。

## 1. pre-commitのインストール

```bash
# pre-commitをインストール
pip install pre-commit
```

## 2. git-secretsのセットアップ

リポジトリ内の`.pre-commit-config.yaml`でgit-secretsを使用しているため、以下の手順でセットアップします。

```bash
# リポジトリ内のtoolsディレクトリにgit-secretsを配置
mkdir -p tools/git-secrets
cd tools/git-secrets
wget https://raw.githubusercontent.com/awslabs/git-secrets/master/git-secrets
chmod +x git-secrets
```

## 3. pre-commitフックのインストール

```bash
# pre-commitフックをインストール
pre-commit install
```

## 4. 動作確認

### 全ファイルをチェック（コミットなし）

```bash
# すべてのファイルに対してpre-commitチェックを実行
pre-commit run --all-files
```

### 個別のフックを実行

```bash
# git-secretsのみ実行
pre-commit run git-secrets --all-files

# trailing-whitespaceのみ実行
pre-commit run trailing-whitespace --all-files
```

## 設定されているフック

現在の`.pre-commit-config.yaml`には以下のフックが設定されています：

### ローカルフック
- **git-secrets**: 機密情報の検出

### 標準フック（pre-commit-hooks）
- **trailing-whitespace**: 行末の空白文字除去
- **end-of-file-fixer**: ファイル末尾の改行修正
- **check-yaml**: YAML構文チェック
- **check-added-large-files**: 大きなファイルの検出
- **check-merge-conflict**: マージコンフリクトマーカーの検出
- **debug-statements**: デバッグ文の検出
- **mixed-line-ending**: 改行コードの統一（LFに固定）

## トラブルシューティング

### git-secretsが見つからない場合

```bash
# git-secretsの実行権限を確認
ls -la tools/git-secrets/git-secrets

# 権限がない場合は実行権限を付与
chmod +x tools/git-secrets/git-secrets
```

### pre-commitが失敗する場合

```bash
# pre-commitのキャッシュをクリア
pre-commit clean

# フックを再インストール
pre-commit install --install-hooks
```
