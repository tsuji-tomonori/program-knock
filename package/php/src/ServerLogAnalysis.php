<?php

declare(strict_types=1);

namespace ProgramKnock;

class ServerLogAnalysis
{
    /**
     * ステータスコードが200のリクエストをすべて抽出
     *
     * @param array<array{0: string, 1: string, 2: int}> $logs ログデータのリスト
     * @return array<array{0: string, 1: string, 2: int}> ステータスコードが200のログのリスト
     */
    public static function filterSuccessfulRequests(array $logs): array
    {
        $successfulRequests = [];

        foreach ($logs as $log) {
            if ($log[2] === 200) {
                $successfulRequests[] = $log;
            }
        }

        return $successfulRequests;
    }

    /**
     * 各IPアドレスのリクエスト回数を集計
     *
     * @param array<array{0: string, 1: string, 2: int}> $logs ログデータのリスト
     * @return array<string, int> IPアドレスごとのリクエスト回数の辞書
     */
    public static function countRequestsByIp(array $logs): array
    {
        $ipCounts = [];

        foreach ($logs as $log) {
            $ip = $log[0];

            if (isset($ipCounts[$ip])) {
                $ipCounts[$ip]++;
            } else {
                $ipCounts[$ip] = 1;
            }
        }

        return $ipCounts;
    }
}
