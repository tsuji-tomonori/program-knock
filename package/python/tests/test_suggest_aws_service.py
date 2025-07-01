from src.suggest_aws_service import suggest_aws_service


# -------------------------------
# サンプルテストケースおよび境界値テスト
# -------------------------------
def test_sample1():
    # "lamda" は "lambda" のスペルミス
    assert suggest_aws_service("lamda") == "lambda"


def test_sample2():
    # "s33" は "s3" のタイプミス（余分な 3）
    assert suggest_aws_service("s33") == "s3"


def test_sample3():
    # "rts" は "rds" に近い
    assert suggest_aws_service("rts") == "rds"


def test_sample4():
    # "cloudfrot" は "cloudfront" のスペルミス
    assert suggest_aws_service("cloudfrot") == "cloudfront"


def test_exact_match():
    # 正しいサービス名の場合、そのまま返すはず
    assert suggest_aws_service("ec2") == "ec2"


def test_min_length():
    # 入力が最小長（1文字）の場合のテスト: "s" から "s3" をサジェスト
    assert suggest_aws_service("s") == "s3"


def test_max_length():
    # 入力が最大長（20文字）の場合のテスト
    input_str = "cloudfronntttttttttt"  # "cloudfront" に余分な文字が付いている
    assert len(input_str) == 20
    assert suggest_aws_service(input_str) == "cloudfront"


def test_dynamo():
    # "dynamo" は "dynamodb" に近い
    assert suggest_aws_service("dynamo") == "dynamodb"


def test_iamm():
    # "iamm" は "iam" へのスペルミスとみなされる
    assert suggest_aws_service("iamm") == "iam"


def test_lambda_exact():
    # 正しい "lambda" が入力された場合はそのまま返す
    assert suggest_aws_service("lambda") == "lambda"


def test_no_similarity():
    # どのサービスとも大きく類似していない場合、tieが発生してサービス一覧順の先頭と仮定
    assert suggest_aws_service("zzz") == "ec2"


# -------------------------------
# 各サービスごとの追加テストケース（各サービス5ケースずつ）
# -------------------------------


# ec2 のテスト
def test_ec2_case1():
    # 正しい文字列
    assert suggest_aws_service("ec2") == "ec2"


def test_ec2_case2():
    # 数字が抜けている
    assert suggest_aws_service("ec") == "ec2"


def test_ec2_case3():
    # 余分な文字が挿入されている
    assert suggest_aws_service("eec2") == "ec2"


def test_ec2_case4():
    # 数字が重複している
    assert suggest_aws_service("ec22") == "ec2"


def test_ec2_case5():
    # 最後の文字が別の数字に置換されている
    assert suggest_aws_service("ec3") == "ec2"


# s3 のテスト
def test_s3_case1():
    # 正しい文字列
    assert suggest_aws_service("s3") == "s3"


def test_s3_case2():
    # 数字が抜けている
    assert suggest_aws_service("s") == "s3"


def test_s3_case3():
    # 余分な数字がある（既にサンプル）
    assert suggest_aws_service("s33") == "s3"


def test_s3_case4():
    # 余分な文字が挿入されている
    assert suggest_aws_service("ss3") == "s3"


def test_s3_case5():
    # 文字の順序が入れ替わっている
    assert suggest_aws_service("3s") == "s3"


# lambda のテスト
def test_lambda_case1():
    # 正しい文字列
    assert suggest_aws_service("lambda") == "lambda"


def test_lambda_case2():
    # 一部文字が抜けている（サンプル）
    assert suggest_aws_service("lamda") == "lambda"


def test_lambda_case3():
    # 末尾の文字が抜けている
    assert suggest_aws_service("lambd") == "lambda"


def test_lambda_case4():
    # 余分な文字が挿入されている
    assert suggest_aws_service("lambdda") == "lambda"


def test_lambda_case5():
    # 途中の文字が抜けている
    assert suggest_aws_service("labda") == "lambda"


# dynamodb のテスト
def test_dynamodb_case1():
    # 正しい文字列
    assert suggest_aws_service("dynamodb") == "dynamodb"


def test_dynamodb_case2():
    # 部分的な入力（不足）
    assert suggest_aws_service("dynamo") == "dynamodb"


def test_dynamodb_case3():
    # 末尾が抜けている
    assert suggest_aws_service("dynamod") == "dynamodb"


def test_dynamodb_case4():
    # 文字の順序が入れ替わっている
    assert suggest_aws_service("dynmodb") == "dynamodb"


def test_dynamodb_case5():
    # 余分な文字が挿入されている
    assert suggest_aws_service("dynamodbb") == "dynamodb"


# rds のテスト
def test_rds_case1():
    # 正しい文字列
    assert suggest_aws_service("rds") == "rds"


def test_rds_case2():
    # 一部文字が入れ替わっている（サンプル）
    assert suggest_aws_service("rts") == "rds"


def test_rds_case3():
    # 末尾の文字が不足している
    assert suggest_aws_service("rd") == "rds"


def test_rds_case4():
    # 余分な文字が付加されている
    assert suggest_aws_service("rdsd") == "rds"


def test_rds_case5():
    # 数字が置換されている
    assert suggest_aws_service("r3s") == "rds"


# cloudfront のテスト
def test_cloudfront_case1():
    # 正しい文字列
    assert suggest_aws_service("cloudfront") == "cloudfront"


def test_cloudfront_case2():
    # 一部文字が入れ替わっている（サンプル）
    assert suggest_aws_service("cloudfrot") == "cloudfront"


def test_cloudfront_case3():
    # 文字が不足している
    assert suggest_aws_service("clodfront") == "cloudfront"


def test_cloudfront_case4():
    # 中央の文字が抜けている
    assert suggest_aws_service("cloudfrnt") == "cloudfront"


def test_cloudfront_case5():
    # 余分な文字が末尾にある
    assert suggest_aws_service("cloudfronte") == "cloudfront"


# iam のテスト
def test_iam_case1():
    # 正しい文字列
    assert suggest_aws_service("iam") == "iam"


def test_iam_case2():
    # 余分な文字が付加されている
    assert suggest_aws_service("iamm") == "iam"


def test_iam_case3():
    # 文字が不足している
    assert suggest_aws_service("im") == "iam"


def test_iam_case4():
    # 先頭の文字が置換されている
    assert suggest_aws_service("aim") == "iam"


def test_iam_case5():
    # 途中に余分な文字がある
    assert suggest_aws_service("iaam") == "iam"


# route53 のテスト
def test_route53_case1():
    # 正しい文字列
    assert suggest_aws_service("route53") == "route53"


def test_route53_case2():
    # 文字が不足している
    assert suggest_aws_service("rout53") == "route53"


def test_route53_case3():
    # 余分な文字が付加されている
    assert suggest_aws_service("rout533") == "route53"


def test_route53_case4():
    # 末尾の数字が不足している
    assert suggest_aws_service("route5") == "route53"


def test_route53_case5():
    # 中央の文字が置換されている
    assert suggest_aws_service("rouet53") == "route53"
