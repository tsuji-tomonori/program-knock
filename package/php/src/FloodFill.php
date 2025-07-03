<?php

declare(strict_types=1);

namespace ProgramKnock;

class FloodFill
{
    /**
     * フラッドフィルアルゴリズムで画像を塗りつぶす
     *
     * @param array<array<int>> $image 2次元画像配列
     * @param int $sr 開始行インデックス
     * @param int $sc 開始列インデックス
     * @param int $newColor 新しい色
     * @return array<array<int>> 塗りつぶされた画像
     */
    public static function floodFill(array $image, int $sr, int $sc, int $newColor): array
    {
        if (empty($image) || empty($image[0])) {
            return $image;
        }

        $rows = count($image);
        $cols = count($image[0]);

        // 範囲外チェック
        if ($sr < 0 || $sr >= $rows || $sc < 0 || $sc >= $cols) {
            return $image;
        }

        $originalColor = $image[$sr][$sc];

        // 既に新しい色と同じ場合は何もしない
        if ($originalColor === $newColor) {
            return $image;
        }

        // 結果用の配列をコピー
        $result = [];
        for ($i = 0; $i < $rows; $i++) {
            $result[$i] = [];
            for ($j = 0; $j < $cols; $j++) {
                $result[$i][$j] = $image[$i][$j];
            }
        }

        // 再帰的にフラッドフィル実行
        self::floodFillRecursive($result, $sr, $sc, $originalColor, $newColor);

        return $result;
    }

    /**
     * 再帰的フラッドフィル実装
     *
     * @param array<array<int>> $image 画像配列（参照渡し）
     * @param int $row 現在の行
     * @param int $col 現在の列
     * @param int $originalColor 元の色
     * @param int $newColor 新しい色
     */
    private static function floodFillRecursive(array &$image, int $row, int $col, int $originalColor, int $newColor): void
    {
        $rows = count($image);
        $cols = count($image[0]);

        // 範囲外チェック
        if ($row < 0 || $row >= $rows || $col < 0 || $col >= $cols) {
            return;
        }

        // 既に新しい色に塗られているか、元の色でない場合は処理しない
        if ($image[$row][$col] !== $originalColor) {
            return;
        }

        // 現在のセルを新しい色で塗る
        $image[$row][$col] = $newColor;

        // 4方向（上下左右）に再帰的に適用
        self::floodFillRecursive($image, $row - 1, $col, $originalColor, $newColor); // 上
        self::floodFillRecursive($image, $row + 1, $col, $originalColor, $newColor); // 下
        self::floodFillRecursive($image, $row, $col - 1, $originalColor, $newColor); // 左
        self::floodFillRecursive($image, $row, $col + 1, $originalColor, $newColor); // 右
    }

    /**
     * 反復的フラッドフィル実装（スタック使用）
     *
     * @param array<array<int>> $image 2次元画像配列
     * @param int $sr 開始行インデックス
     * @param int $sc 開始列インデックス
     * @param int $newColor 新しい色
     * @return array<array<int>> 塗りつぶされた画像
     */
    public static function floodFillIterative(array $image, int $sr, int $sc, int $newColor): array
    {
        if (empty($image) || empty($image[0])) {
            return $image;
        }

        $rows = count($image);
        $cols = count($image[0]);

        // 範囲外チェック
        if ($sr < 0 || $sr >= $rows || $sc < 0 || $sc >= $cols) {
            return $image;
        }

        $originalColor = $image[$sr][$sc];

        // 既に新しい色と同じ場合は何もしない
        if ($originalColor === $newColor) {
            return $image;
        }

        // 結果用の配列をコピー
        $result = [];
        for ($i = 0; $i < $rows; $i++) {
            $result[$i] = [];
            for ($j = 0; $j < $cols; $j++) {
                $result[$i][$j] = $image[$i][$j];
            }
        }

        // スタックを使用した反復処理
        $stack = [[$sr, $sc]];

        while (!empty($stack)) {
            [$row, $col] = array_pop($stack);

            // 範囲外チェック
            if ($row < 0 || $row >= $rows || $col < 0 || $col >= $cols) {
                continue;
            }

            // 既に新しい色に塗られているか、元の色でない場合はスキップ
            if ($result[$row][$col] !== $originalColor) {
                continue;
            }

            // 現在のセルを新しい色で塗る
            $result[$row][$col] = $newColor;

            // 4方向をスタックに追加
            $stack[] = [$row - 1, $col]; // 上
            $stack[] = [$row + 1, $col]; // 下
            $stack[] = [$row, $col - 1]; // 左
            $stack[] = [$row, $col + 1]; // 右
        }

        return $result;
    }

    /**
     * 画像の表示（デバッグ用）
     *
     * @param array<array<int>> $image 画像配列
     * @return string 画像の文字列表現
     */
    public static function imageToString(array $image): string
    {
        $result = "";
        foreach ($image as $row) {
            $result .= implode(" ", $row) . "\n";
        }
        return rtrim($result);
    }

    /**
     * 画像のサイズを取得
     *
     * @param array<array<int>> $image 画像配列
     * @return array{rows: int, cols: int} 行数と列数
     */
    public static function getImageSize(array $image): array
    {
        if (empty($image)) {
            return ['rows' => 0, 'cols' => 0];
        }

        return [
            'rows' => count($image),
            'cols' => count($image[0])
        ];
    }

    /**
     * 画像の妥当性をチェック
     *
     * @param array<array<int>> $image 画像配列
     * @return bool 妥当ならtrue
     */
    public static function validateImage(array $image): bool
    {
        if (empty($image)) {
            return false;
        }

        $expectedCols = count($image[0]);

        foreach ($image as $row) {
            // 各行の長さが一致しているかチェック
            if (count($row) !== $expectedCols) {
                return false;
            }

            // 各セルが0または1かチェック
            foreach ($row as $cell) {
                if (!is_int($cell) || ($cell !== 0 && $cell !== 1)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 座標の妥当性をチェック
     *
     * @param array<array<int>> $image 画像配列
     * @param int $sr 行インデックス
     * @param int $sc 列インデックス
     * @return bool 妥当ならtrue
     */
    public static function validateCoordinates(array $image, int $sr, int $sc): bool
    {
        if (empty($image) || empty($image[0])) {
            return false;
        }

        $rows = count($image);
        $cols = count($image[0]);

        return $sr >= 0 && $sr < $rows && $sc >= 0 && $sc < $cols;
    }

    /**
     * 塗りつぶし領域のサイズを計算
     *
     * @param array<array<int>> $image 画像配列
     * @param int $sr 開始行インデックス
     * @param int $sc 開始列インデックス
     * @return int 塗りつぶし可能なセル数
     */
    public static function calculateFillableArea(array $image, int $sr, int $sc): int
    {
        if (!self::validateCoordinates($image, $sr, $sc)) {
            return 0;
        }

        $originalColor = $image[$sr][$sc];
        $visited = [];

        // visited配列を初期化
        for ($i = 0; $i < count($image); $i++) {
            $visited[$i] = array_fill(0, count($image[0]), false);
        }

        return self::countFillableRecursive($image, $sr, $sc, $originalColor, $visited);
    }

    /**
     * 塗りつぶし可能領域を再帰的にカウント
     *
     * @param array<array<int>> $image 画像配列
     * @param int $row 現在の行
     * @param int $col 現在の列
     * @param int $targetColor 対象色
     * @param array<array<bool>> $visited 訪問済みフラグ
     * @return int カウント数
     */
    private static function countFillableRecursive(array $image, int $row, int $col, int $targetColor, array &$visited): int
    {
        $rows = count($image);
        $cols = count($image[0]);

        // 範囲外または既に訪問済み
        if ($row < 0 || $row >= $rows || $col < 0 || $col >= $cols || $visited[$row][$col]) {
            return 0;
        }

        // 色が一致しない
        if ($image[$row][$col] !== $targetColor) {
            return 0;
        }

        $visited[$row][$col] = true;
        $count = 1;

        // 4方向に再帰
        $count += self::countFillableRecursive($image, $row - 1, $col, $targetColor, $visited);
        $count += self::countFillableRecursive($image, $row + 1, $col, $targetColor, $visited);
        $count += self::countFillableRecursive($image, $row, $col - 1, $targetColor, $visited);
        $count += self::countFillableRecursive($image, $row, $col + 1, $targetColor, $visited);

        return $count;
    }

    /**
     * 複数の座標でフラッドフィルを実行
     *
     * @param array<array<int>> $image 画像配列
     * @param array<array{0: int, 1: int, 2: int}> $operations [sr, sc, newColor] の配列
     * @return array<array<int>> 全ての操作を適用した画像
     */
    public static function floodFillMultiple(array $image, array $operations): array
    {
        $result = $image;

        foreach ($operations as $operation) {
            [$sr, $sc, $newColor] = $operation;
            $result = self::floodFill($result, $sr, $sc, $newColor);
        }

        return $result;
    }
}
