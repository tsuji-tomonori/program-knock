/**
 * 2つの文字列 s, t のレーベンシュタイン距離（編集距離）を動的プログラミングで求める関数。
 */
function levenshteinDistance(s: string, t: string): number {
  const m = s.length;
  const n = t.length;

  // dp[i][j] は s[0...i-1] と t[0...j-1] の編集距離
  const dp = Array.from({ length: m + 1 }, () => Array(n + 1).fill(0));

  // ベースケース: 空文字列からの距離
  for (let i = 0; i <= m; i++) {
    dp[i][0] = i;
  }
  for (let j = 0; j <= n; j++) {
    dp[0][j] = j;
  }

  // DPテーブルを埋める
  for (let i = 1; i <= m; i++) {
    for (let j = 1; j <= n; j++) {
      if (s[i - 1] === t[j - 1]) {
        dp[i][j] = dp[i - 1][j - 1]; // 文字が同じ場合はコストなし
      } else {
        dp[i][j] = 1 + Math.min(
          dp[i - 1][j],     // 削除
          dp[i][j - 1],     // 挿入
          dp[i - 1][j - 1]  // 置換
        );
      }
    }
  }

  return dp[m][n];
}

/**
 * 誤った AWS サービス名を受け取り、最も類似する正しいサービス名をサジェストする。
 *
 * @param wrongService - 誤入力されたサービス名
 * @returns 最も類似する AWS サービス名
 */
export function suggestAwsService(wrongService: string): string {
  // サポートする AWS サービス一覧
  const services = [
    "ec2",
    "s3",
    "lambda",
    "dynamodb",
    "rds",
    "cloudfront",
    "iam",
    "route53",
  ];

  // 最小のレーベンシュタイン距離と候補のサービス名を初期化
  let minDistance = Infinity;
  let suggestion = services[0];

  // 各サービスと誤入力文字列間の距離を計算し、最も近いものを選択
  for (const service of services) {
    const distance = levenshteinDistance(wrongService, service);
    if (distance < minDistance) {
      minDistance = distance;
      suggestion = service;
    }
  }

  return suggestion;
}
