<?php

declare(strict_types=1);

namespace ProgramKnock;

class ConnectionAggregation
{
    /**
     * 時間単位での接続数の集計
     *
     * @param int $endTime 集計終了時刻
     * @param int $period 集計間隔
     * @param array<array{time: int, n_connect: int, n_disconnect: int}> $logs ログデータのリスト
     * @return array<int> 各期間の接続数のリスト
     */
    public static function calculate(int $endTime, int $period, array $logs): array
    {
        // ログを時刻でソート
        usort($logs, function($a, $b) {
            return $a['time'] - $b['time'];
        });

        $connections = 0;
        $result = [];
        $logIndex = 0;

        // 時刻0からend_timeまでperiod間隔で集計
        for ($time = 0; $time <= $endTime; $time += $period) {
            // 現在の時刻までのログを処理
            while ($logIndex < count($logs) && $logs[$logIndex]['time'] <= $time) {
                $log = $logs[$logIndex];
                $connections += $log['n_connect'] - $log['n_disconnect'];
                $logIndex++;
            }

            $result[] = $connections;
        }

        return $result;
    }
}
