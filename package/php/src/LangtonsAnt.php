<?php

declare(strict_types=1);

namespace ProgramKnock;

class LangtonsAnt
{
    /**
     * ラングトンのアリのシミュレーション
     *
     * @param int $steps アリが移動するステップ数
     * @return array<array{0: int, 1: int}> 黒いマスの座標のリスト（ソート済み）
     */
    public static function simulate(int $steps): array
    {
        // 方向: 0=上(北), 1=右(東), 2=下(南), 3=左(西)
        $directions = [
            [0, 1],   // 上: y+1
            [1, 0],   // 右: x+1
            [0, -1],  // 下: y-1
            [-1, 0]   // 左: x-1
        ];

        $antX = 0;
        $antY = 0;
        $antDirection = 0; // 初期方向は上（北）

        // 黒いマスを記録するセット（連想配列で実装）
        $blackCells = [];

        for ($i = 0; $i < $steps; $i++) {
            $key = "$antX,$antY";

            if (isset($blackCells[$key])) {
                // 黒いマスの場合
                unset($blackCells[$key]); // 白に変更
                $antDirection = ($antDirection + 3) % 4; // 左に90度回転（-1を+3で表現）
            } else {
                // 白いマスの場合
                $blackCells[$key] = true; // 黒に変更
                $antDirection = ($antDirection + 1) % 4; // 右に90度回転
            }

            // 1マス前進
            $antX += $directions[$antDirection][0];
            $antY += $directions[$antDirection][1];
        }

        // 黒いマスの座標をリストに変換
        $result = [];
        foreach (array_keys($blackCells) as $key) {
            list($x, $y) = explode(',', $key);
            $result[] = [(int)$x, (int)$y];
        }

        // (x, y) の昇順でソート
        usort($result, function($a, $b) {
            if ($a[0] !== $b[0]) {
                return $a[0] - $b[0];
            }
            return $a[1] - $b[1];
        });

        return $result;
    }
}
