import { suggestAwsService } from './suggestAwsService';

describe('SuggestAwsService', () => {
  // サンプルテストケースおよび境界値テスト
  test('sample1', () => {
    // "lamda" は "lambda" のスペルミス
    expect(suggestAwsService("lamda")).toBe("lambda");
  });

  test('sample2', () => {
    // "s33" は "s3" のタイプミス（余分な 3）
    expect(suggestAwsService("s33")).toBe("s3");
  });

  test('sample3', () => {
    // "rts" は "rds" に近い
    expect(suggestAwsService("rts")).toBe("rds");
  });

  test('sample4', () => {
    // "cloudfrot" は "cloudfront" のスペルミス
    expect(suggestAwsService("cloudfrot")).toBe("cloudfront");
  });

  test('exact_match', () => {
    // 正しいサービス名の場合、そのまま返すはず
    expect(suggestAwsService("ec2")).toBe("ec2");
  });

  test('min_length', () => {
    // 入力が最小長（1文字）の場合のテスト: "s" から "s3" をサジェスト
    expect(suggestAwsService("s")).toBe("s3");
  });

  test('max_length', () => {
    // 入力が最大長（20文字）の場合のテスト
    const inputStr = "cloudfronntttttttttt"; // "cloudfront" に余分な文字が付いている
    expect(inputStr).toHaveLength(20);
    expect(suggestAwsService(inputStr)).toBe("cloudfront");
  });

  test('dynamo', () => {
    // "dynamo" は "dynamodb" に近い
    expect(suggestAwsService("dynamo")).toBe("dynamodb");
  });

  test('iamm', () => {
    // "iamm" は "iam" へのスペルミスとみなされる
    expect(suggestAwsService("iamm")).toBe("iam");
  });

  test('lambda_exact', () => {
    // 正しい "lambda" が入力された場合はそのまま返す
    expect(suggestAwsService("lambda")).toBe("lambda");
  });

  test('no_similarity', () => {
    // どのサービスとも大きく類似していない場合、tieが発生してサービス一覧順の先頭と仮定
    expect(suggestAwsService("zzz")).toBe("ec2");
  });

  // 各サービスごとの追加テストケース（各サービス5ケースずつ）

  // ec2 のテスト
  describe('ec2 tests', () => {
    test('ec2_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("ec2")).toBe("ec2");
    });

    test('ec2_case2', () => {
      // 数字が抜けている
      expect(suggestAwsService("ec")).toBe("ec2");
    });

    test('ec2_case3', () => {
      // 余分な文字が挿入されている
      expect(suggestAwsService("eec2")).toBe("ec2");
    });

    test('ec2_case4', () => {
      // 数字が重複している
      expect(suggestAwsService("ec22")).toBe("ec2");
    });

    test('ec2_case5', () => {
      // 最後の文字が別の数字に置換されている
      expect(suggestAwsService("ec3")).toBe("ec2");
    });
  });

  // s3 のテスト
  describe('s3 tests', () => {
    test('s3_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("s3")).toBe("s3");
    });

    test('s3_case2', () => {
      // 数字が抜けている
      expect(suggestAwsService("s")).toBe("s3");
    });

    test('s3_case3', () => {
      // 余分な数字がある（既にサンプル）
      expect(suggestAwsService("s33")).toBe("s3");
    });

    test('s3_case4', () => {
      // 余分な文字が挿入されている
      expect(suggestAwsService("ss3")).toBe("s3");
    });

    test('s3_case5', () => {
      // 文字の順序が入れ替わっている
      expect(suggestAwsService("3s")).toBe("s3");
    });
  });

  // lambda のテスト
  describe('lambda tests', () => {
    test('lambda_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("lambda")).toBe("lambda");
    });

    test('lambda_case2', () => {
      // 一部文字が抜けている（サンプル）
      expect(suggestAwsService("lamda")).toBe("lambda");
    });

    test('lambda_case3', () => {
      // 末尾の文字が抜けている
      expect(suggestAwsService("lambd")).toBe("lambda");
    });

    test('lambda_case4', () => {
      // 余分な文字が挿入されている
      expect(suggestAwsService("lambdda")).toBe("lambda");
    });

    test('lambda_case5', () => {
      // 途中の文字が抜けている
      expect(suggestAwsService("labda")).toBe("lambda");
    });
  });

  // dynamodb のテスト
  describe('dynamodb tests', () => {
    test('dynamodb_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("dynamodb")).toBe("dynamodb");
    });

    test('dynamodb_case2', () => {
      // 部分的な入力（不足）
      expect(suggestAwsService("dynamo")).toBe("dynamodb");
    });

    test('dynamodb_case3', () => {
      // 末尾が抜けている
      expect(suggestAwsService("dynamod")).toBe("dynamodb");
    });

    test('dynamodb_case4', () => {
      // 文字の順序が入れ替わっている
      expect(suggestAwsService("dynmodb")).toBe("dynamodb");
    });

    test('dynamodb_case5', () => {
      // 余分な文字が挿入されている
      expect(suggestAwsService("dynamodbb")).toBe("dynamodb");
    });
  });

  // rds のテスト
  describe('rds tests', () => {
    test('rds_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("rds")).toBe("rds");
    });

    test('rds_case2', () => {
      // 一部文字が入れ替わっている（サンプル）
      expect(suggestAwsService("rts")).toBe("rds");
    });

    test('rds_case3', () => {
      // 末尾の文字が不足している
      expect(suggestAwsService("rd")).toBe("rds");
    });

    test('rds_case4', () => {
      // 余分な文字が付加されている
      expect(suggestAwsService("rdsd")).toBe("rds");
    });

    test('rds_case5', () => {
      // 数字が置換されている
      expect(suggestAwsService("r3s")).toBe("rds");
    });
  });

  // cloudfront のテスト
  describe('cloudfront tests', () => {
    test('cloudfront_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("cloudfront")).toBe("cloudfront");
    });

    test('cloudfront_case2', () => {
      // 一部文字が入れ替わっている（サンプル）
      expect(suggestAwsService("cloudfrot")).toBe("cloudfront");
    });

    test('cloudfront_case3', () => {
      // 文字が不足している
      expect(suggestAwsService("clodfront")).toBe("cloudfront");
    });

    test('cloudfront_case4', () => {
      // 中央の文字が抜けている
      expect(suggestAwsService("cloudfrnt")).toBe("cloudfront");
    });

    test('cloudfront_case5', () => {
      // 余分な文字が末尾にある
      expect(suggestAwsService("cloudfronte")).toBe("cloudfront");
    });
  });

  // iam のテスト
  describe('iam tests', () => {
    test('iam_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("iam")).toBe("iam");
    });

    test('iam_case2', () => {
      // 余分な文字が付加されている
      expect(suggestAwsService("iamm")).toBe("iam");
    });

    test('iam_case3', () => {
      // 文字が不足している
      expect(suggestAwsService("im")).toBe("iam");
    });

    test('iam_case4', () => {
      // 先頭の文字が置換されている
      expect(suggestAwsService("aim")).toBe("iam");
    });

    test('iam_case5', () => {
      // 途中に余分な文字がある
      expect(suggestAwsService("iaam")).toBe("iam");
    });
  });

  // route53 のテスト
  describe('route53 tests', () => {
    test('route53_case1', () => {
      // 正しい文字列
      expect(suggestAwsService("route53")).toBe("route53");
    });

    test('route53_case2', () => {
      // 文字が不足している
      expect(suggestAwsService("rout53")).toBe("route53");
    });

    test('route53_case3', () => {
      // 余分な文字が付加されている
      expect(suggestAwsService("rout533")).toBe("route53");
    });

    test('route53_case4', () => {
      // 末尾の数字が不足している
      expect(suggestAwsService("route5")).toBe("route53");
    });

    test('route53_case5', () => {
      // 中央の文字が置換されている
      expect(suggestAwsService("rouet53")).toBe("route53");
    });
  });
});
