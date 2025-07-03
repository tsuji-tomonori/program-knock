<?php

declare(strict_types=1);

namespace ProgramKnock;

class AgeStatistics
{
    /**
     * 年齢分布の集計を行う
     *
     * @param array<int> $ages 社員の年齢リスト
     * @return array{0: int, 1: int, 2: float, 3: int} [最大年齢, 最小年齢, 平均年齢, 平均年齢以下の人数]
     * @throws \InvalidArgumentException 年齢リストが空の場合
     */
    public static function calculate(array $ages): array
    {
        if (empty($ages)) {
            throw new \InvalidArgumentException('年齢リストは空にできません');
        }

        // 最大年齢と最小年齢を取得
        $maxAge = max($ages);
        $minAge = min($ages);

        // 平均年齢を計算
        $sumAge = array_sum($ages);
        $count = count($ages);
        $avgAge = round($sumAge / $count, 2);

        // 平均年齢以下の人数をカウント
        $countBelowAvg = 0;
        foreach ($ages as $age) {
            if ($age <= $avgAge) {
                $countBelowAvg++;
            }
        }

        return [$maxAge, $minAge, $avgAge, $countBelowAvg];
    }
}
