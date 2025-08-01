# 問題1: AWS CLI コマンドの類似サジェスト

## 問題文

AWS CLI (Command Line Interface) では、さまざまな AWS サービスに対してコマンドを実行できます。しかし、誤ったコマンドを入力した場合、適切なサービス名をサジェストできると便利です。

この問題では、AWS CLI のサービス名のリストと誤った入力をもとに、最も類似した正しいサービス名をサジェストする関数を実装してください。

**サポートする AWS サービス一覧**:
- `ec2`
- `s3`
- `lambda`
- `dynamodb`
- `rds`
- `cloudfront`
- `iam`
- `route53`

### 実装のヒント

この問題は **レーベンシュタイン距離** を用いると解きやすいです。

レーベンシュタイン距離（Levenshtein distance）は、ある文字列を別の文字列に変換するために必要な編集操作（挿入、削除、置換）の最小回数を示します。

例えば、"kitten" を "sitting" に変換する場合、以下のような編集が必要になります。

1. "kitten" → "sitten" (k → s の置換)
2. "sitten" → "sittin" (e → i の置換)
3. "sittin" → "sitting" (末尾に g を追加)

この場合のレーベンシュタイン距離は **3** です。

## 入力

- `wrong_service` (文字列): 誤入力された AWS サービス名

### 入力の制約

- `wrong_service` は英小文字 (`a-z`) からなる文字列であり、長さは 1 以上 20 以下。
- `wrong_service` は AWS サービスの名前を誤って入力したもの。

## 出力

- `wrong_service` に最も近い AWS サービス名を一つ返す。

## サンプル1

**入力:** `"lamda"`
**出力:** `"lambda"`

**解説**

- "lamda" は "lambda" のスペルミス
- レーベンシュタイン距離を計算すると `lambda` が最も近い

## サンプル2

**入力:** `"s33"`
**出力:** `"s3"`

**解説**

- "s33" は "s3" のタイプミス（余分な `3`）
- `s3` が最も類似しているため、サジェスト

## サンプル3

**入力:** `"rts"`
**出力:** `"rds"`

**解説**

- "rts" は "rds" に近い
- `rds` をサジェスト

## サンプル4

**入力:** `"cloudfrot"`
**出力:** `"cloudfront"`

**解説**

- "cloudfrot" は "cloudfront" のスペルミス
- `cloudfront` が最も類似しているため、サジェスト
