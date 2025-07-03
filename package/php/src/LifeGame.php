<?php

declare(strict_types=1);

namespace ProgramKnock;

class LifeGame
{
    /**
     * ライフゲームの次の世代を計算
     *
     * @param array<array<int>> $board 現在の盤面（m × n の二次元配列）
     * @return array<array<int>> 次の世代の盤面
     */
    public static function nextGeneration(array $board): array
    {
        if (empty($board) || empty($board[0])) {
            return $board;
        }

        $rows = count($board);
        $cols = count($board[0]);
        $nextBoard = array_fill(0, $rows, array_fill(0, $cols, 0));

        // 各セルについて次の状態を計算
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $liveNeighbors = self::countLiveNeighbors($board, $i, $j, $rows, $cols);
                $currentCell = $board[$i][$j];

                if ($currentCell === 1) {
                    // 生存セルの場合
                    if ($liveNeighbors === 2 || $liveNeighbors === 3) {
                        $nextBoard[$i][$j] = 1; // 生存継続
                    } else {
                        $nextBoard[$i][$j] = 0; // 死滅
                    }
                } else {
                    // 死滅セルの場合
                    if ($liveNeighbors === 3) {
                        $nextBoard[$i][$j] = 1; // 誕生
                    } else {
                        $nextBoard[$i][$j] = 0; // 死滅継続
                    }
                }
            }
        }

        return $nextBoard;
    }

    /**
     * 指定されたセルの周囲8マスの生存セル数をカウント
     *
     * @param array<array<int>> $board 盤面
     * @param int $row 行インデックス
     * @param int $col 列インデックス
     * @param int $rows 総行数
     * @param int $cols 総列数
     * @return int 周囲の生存セル数
     */
    private static function countLiveNeighbors(array $board, int $row, int $col, int $rows, int $cols): int
    {
        $count = 0;
        $directions = [
            [-1, -1], [-1, 0], [-1, 1],
            [0, -1],           [0, 1],
            [1, -1],  [1, 0],  [1, 1]
        ];

        foreach ($directions as $dir) {
            $newRow = $row + $dir[0];
            $newCol = $col + $dir[1];

            // 境界チェック
            if ($newRow >= 0 && $newRow < $rows && $newCol >= 0 && $newCol < $cols) {
                $count += $board[$newRow][$newCol];
            }
        }

        return $count;
    }
}
