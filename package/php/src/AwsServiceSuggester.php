<?php

declare(strict_types=1);

namespace ProgramKnock;

class AwsServiceSuggester
{
    /**
     * AWS サービス名のリスト
     */
    private const AWS_SERVICES = [
        'ec2',
        's3',
        'lambda',
        'dynamodb',
        'rds',
        'cloudfront',
        'iam',
        'route53'
    ];

    /**
     * 誤入力されたサービス名に最も近いAWSサービス名をサジェスト
     *
     * @param string $wrongService 誤入力されたサービス名
     * @return string 最も近いAWSサービス名
     */
    public static function suggestService(string $wrongService): string
    {
        $minDistance = PHP_INT_MAX;
        $closestService = self::AWS_SERVICES[0];

        foreach (self::AWS_SERVICES as $service) {
            $distance = self::levenshteinDistance($wrongService, $service);

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestService = $service;
            } elseif ($distance === $minDistance && strcmp($service, $closestService) < 0) {
                // 距離が同じ場合は辞書順で早い方を選択
                $closestService = $service;
            }
        }

        return $closestService;
    }

    /**
     * レーベンシュタイン距離を計算（動的プログラミング）
     *
     * @param string $str1 文字列1
     * @param string $str2 文字列2
     * @return int レーベンシュタイン距離
     */
    public static function levenshteinDistance(string $str1, string $str2): int
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);

        // DPテーブルを作成
        $dp = [];
        for ($i = 0; $i <= $len1; $i++) {
            $dp[$i] = [];
            for ($j = 0; $j <= $len2; $j++) {
                $dp[$i][$j] = 0;
            }
        }

        // 初期化
        for ($i = 0; $i <= $len1; $i++) {
            $dp[$i][0] = $i; // str1のi文字を削除する操作
        }
        for ($j = 0; $j <= $len2; $j++) {
            $dp[0][$j] = $j; // str2のj文字を挿入する操作
        }

        // DPテーブル埋め
        for ($i = 1; $i <= $len1; $i++) {
            for ($j = 1; $j <= $len2; $j++) {
                if ($str1[$i - 1] === $str2[$j - 1]) {
                    // 文字が同じ場合、操作は不要
                    $dp[$i][$j] = $dp[$i - 1][$j - 1];
                } else {
                    // 文字が異なる場合、3つの操作のうち最小コストを選択
                    $dp[$i][$j] = 1 + min(
                        $dp[$i - 1][$j],     // 削除
                        $dp[$i][$j - 1],     // 挿入
                        $dp[$i - 1][$j - 1]  // 置換
                    );
                }
            }
        }

        return $dp[$len1][$len2];
    }

    /**
     * 複数の誤入力に対してサジェストを一括実行
     *
     * @param array<string> $wrongServices 誤入力サービス名のリスト
     * @return array<string> サジェストされたサービス名のリスト
     */
    public static function suggestMultipleServices(array $wrongServices): array
    {
        $results = [];

        foreach ($wrongServices as $wrongService) {
            $results[] = self::suggestService($wrongService);
        }

        return $results;
    }

    /**
     * 距離とサジェストのペアを取得（デバッグ用）
     *
     * @param string $wrongService 誤入力されたサービス名
     * @return array{service: string, distance: int} サジェストとその距離
     */
    public static function suggestWithDistance(string $wrongService): array
    {
        $minDistance = PHP_INT_MAX;
        $closestService = self::AWS_SERVICES[0];

        foreach (self::AWS_SERVICES as $service) {
            $distance = self::levenshteinDistance($wrongService, $service);

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestService = $service;
            } elseif ($distance === $minDistance && strcmp($service, $closestService) < 0) {
                $closestService = $service;
            }
        }

        return [
            'service' => $closestService,
            'distance' => $minDistance
        ];
    }

    /**
     * 全てのサービスに対する距離を計算
     *
     * @param string $wrongService 誤入力されたサービス名
     * @return array<string, int> サービス名 => 距離 のマップ
     */
    public static function calculateAllDistances(string $wrongService): array
    {
        $distances = [];

        foreach (self::AWS_SERVICES as $service) {
            $distances[$service] = self::levenshteinDistance($wrongService, $service);
        }

        return $distances;
    }

    /**
     * サポートされているAWSサービス一覧を取得
     *
     * @return array<string> AWSサービス名のリスト
     */
    public static function getSupportedServices(): array
    {
        return self::AWS_SERVICES;
    }

    /**
     * 入力値の妥当性をチェック
     *
     * @param string $input チェック対象の文字列
     * @return bool 妥当ならtrue
     */
    public static function validateInput(string $input): bool
    {
        // 英小文字のみ、長さ1-20
        return preg_match('/^[a-z]{1,20}$/', $input) === 1;
    }

    /**
     * PHP標準のlevenshtein関数との比較テスト
     *
     * @param string $str1 文字列1
     * @param string $str2 文字列2
     * @return bool 同じ結果ならtrue
     */
    public static function compareWithBuiltIn(string $str1, string $str2): bool
    {
        $ourResult = self::levenshteinDistance($str1, $str2);
        $builtInResult = levenshtein($str1, $str2);

        return $ourResult === $builtInResult;
    }
}
