<?php

declare(strict_types=1);

namespace ProgramKnock;

class RangeCounter
{
    /**
     * ソート済み配列から指定された範囲内の要素数をカウント
     *
     * @param array<int> $arr ソート済み整数配列
     * @param int $l 範囲の下限（含む）
     * @param int $r 範囲の上限（含む）
     * @return int 範囲内の要素数
     */
    public static function countInRange(array $arr, int $l, int $r): int
    {
        if (empty($arr) || $l > $r) {
            return 0;
        }

        // バイナリサーチで左端と右端のインデックスを見つける
        $leftIndex = self::findLeftBound($arr, $l);
        $rightIndex = self::findRightBound($arr, $r);

        if ($leftIndex > $rightIndex) {
            return 0;
        }

        return $rightIndex - $leftIndex + 1;
    }

    /**
     * 指定値以上の最初のインデックスを見つける（lower_bound）
     *
     * @param array<int> $arr ソート済み配列
     * @param int $value 検索値
     * @return int インデックス（見つからない場合は配列長）
     */
    private static function findLeftBound(array $arr, int $value): int
    {
        $left = 0;
        $right = count($arr);

        while ($left < $right) {
            $mid = intval(($left + $right) / 2);

            if ($arr[$mid] >= $value) {
                $right = $mid;
            } else {
                $left = $mid + 1;
            }
        }

        return $left;
    }

    /**
     * 指定値以下の最後のインデックスを見つける（upper_bound - 1）
     *
     * @param array<int> $arr ソート済み配列
     * @param int $value 検索値
     * @return int インデックス（見つからない場合は-1）
     */
    private static function findRightBound(array $arr, int $value): int
    {
        $left = 0;
        $right = count($arr);

        while ($left < $right) {
            $mid = intval(($left + $right) / 2);

            if ($arr[$mid] <= $value) {
                $left = $mid + 1;
            } else {
                $right = $mid;
            }
        }

        return $left - 1;
    }

    /**
     * 線形探索版（デバッグ用・小さい配列用）
     *
     * @param array<int> $arr ソート済み整数配列
     * @param int $l 範囲の下限（含む）
     * @param int $r 範囲の上限（含む）
     * @return int 範囲内の要素数
     */
    public static function countInRangeLinear(array $arr, int $l, int $r): int
    {
        $count = 0;

        foreach ($arr as $value) {
            if ($value >= $l && $value <= $r) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * 複数の範囲に対して要素数を一括カウント
     *
     * @param array<int> $arr ソート済み整数配列
     * @param array<array{0: int, 1: int}> $ranges 範囲の配列 [l, r]
     * @return array<int> 各範囲の要素数の配列
     */
    public static function countMultipleRanges(array $arr, array $ranges): array
    {
        $results = [];

        foreach ($ranges as $range) {
            [$l, $r] = $range;
            $results[] = self::countInRange($arr, $l, $r);
        }

        return $results;
    }

    /**
     * 指定値の存在をチェック
     *
     * @param array<int> $arr ソート済み配列
     * @param int $value 検索値
     * @return bool 存在するならtrue
     */
    public static function contains(array $arr, int $value): bool
    {
        return self::countInRange($arr, $value, $value) > 0;
    }

    /**
     * 入力値の妥当性をチェック
     *
     * @param array<int> $arr チェック対象の配列
     * @return bool ソート済みならtrue
     */
    public static function validateInput(array $arr): bool
    {
        for ($i = 1; $i < count($arr); $i++) {
            if ($arr[$i] < $arr[$i - 1]) {
                return false; // ソートされていない
            }
        }

        return true;
    }

    /**
     * パフォーマンステスト用の大量データ処理
     *
     * @param array<int> $arr ソート済み配列
     * @param int $queryCount クエリ数
     * @return array<int> ランダムクエリの結果
     */
    public static function performanceTest(array $arr, int $queryCount): array
    {
        $results = [];
        $minVal = empty($arr) ? 0 : $arr[0];
        $maxVal = empty($arr) ? 0 : $arr[count($arr) - 1];

        for ($i = 0; $i < $queryCount; $i++) {
            $l = rand($minVal - 10, $maxVal + 10);
            $r = rand($l, $maxVal + 10);
            $results[] = self::countInRange($arr, $l, $r);
        }

        return $results;
    }
}
