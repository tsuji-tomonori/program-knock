<?php

declare(strict_types=1);

namespace ProgramKnock;

class ClosestValueFinder
{
    /**
     * ソート済み配列から目標値に最も近い値を見つける
     *
     * @param array<int> $arr ソート済み整数配列
     * @param int $target 目標値
     * @return int 目標値に最も近い値（複数ある場合は小さい方）
     */
    public static function findClosestValue(array $arr, int $target): int
    {
        // 配列が空の場合は想定外だが、制約では1以上なので対処不要
        if (empty($arr)) {
            throw new \InvalidArgumentException('Array must not be empty');
        }

        // 単一要素の場合
        if (count($arr) === 1) {
            return $arr[0];
        }

        // バイナリサーチで目標値の挿入位置を見つける
        $left = 0;
        $right = count($arr) - 1;

        // 範囲外チェック
        if ($target <= $arr[0]) {
            return $arr[0];
        }
        if ($target >= $arr[$right]) {
            return $arr[$right];
        }

        // バイナリサーチで挿入位置を見つける
        while ($left <= $right) {
            $mid = intval(($left + $right) / 2);

            if ($arr[$mid] === $target) {
                return $arr[$mid];
            }

            if ($arr[$mid] < $target) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        // $left が挿入位置、$right が $left - 1
        // $arr[$right] < $target < $arr[$left] の状態

        $leftDiff = abs($arr[$left] - $target);
        $rightDiff = abs($arr[$right] - $target);

        // 距離が同じ場合は小さい方を返す
        if ($leftDiff === $rightDiff) {
            return $arr[$right]; // 小さい方
        }

        return $leftDiff < $rightDiff ? $arr[$left] : $arr[$right];
    }

    /**
     * 線形探索版（デバッグ用・小さい配列用）
     *
     * @param array<int> $arr ソート済み整数配列
     * @param int $target 目標値
     * @return int 目標値に最も近い値
     */
    public static function findClosestValueLinear(array $arr, int $target): int
    {
        if (empty($arr)) {
            throw new \InvalidArgumentException('Array must not be empty');
        }

        $closest = $arr[0];
        $minDiff = abs($arr[0] - $target);

        foreach ($arr as $value) {
            $diff = abs($value - $target);

            if ($diff < $minDiff || ($diff === $minDiff && $value < $closest)) {
                $closest = $value;
                $minDiff = $diff;
            }
        }

        return $closest;
    }

    /**
     * 複数の目標値に対して最も近い値を一括取得
     *
     * @param array<int> $arr ソート済み整数配列
     * @param array<int> $targets 目標値の配列
     * @return array<int> 各目標値に最も近い値の配列
     */
    public static function findClosestValues(array $arr, array $targets): array
    {
        $results = [];

        foreach ($targets as $target) {
            $results[] = self::findClosestValue($arr, $target);
        }

        return $results;
    }

    /**
     * 入力値の妥当性をチェック
     *
     * @param array<int> $arr チェック対象の配列
     * @return bool ソート済みかつ重複なしならtrue
     */
    public static function validateInput(array $arr): bool
    {
        if (empty($arr)) {
            return false;
        }

        for ($i = 1; $i < count($arr); $i++) {
            if ($arr[$i] <= $arr[$i - 1]) {
                return false; // ソートされていないか重複がある
            }
        }

        return true;
    }
}
