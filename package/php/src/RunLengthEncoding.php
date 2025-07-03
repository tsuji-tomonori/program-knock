<?php

declare(strict_types=1);

namespace ProgramKnock;

class RunLengthEncoding
{
    /**
     * 連長圧縮（Run-Length Encoding）を実行
     *
     * @param string $s 圧縮対象の文字列
     * @return array<array{0: string, 1: int}> (文字, 連続回数)のタプルのリスト
     */
    public static function encode(string $s): array
    {
        if (empty($s)) {
            return [];
        }

        $result = [];
        $currentChar = $s[0];
        $count = 1;

        for ($i = 1; $i < strlen($s); $i++) {
            if ($s[$i] === $currentChar) {
                $count++;
            } else {
                // 現在の文字の連続が終了
                $result[] = [$currentChar, $count];
                $currentChar = $s[$i];
                $count = 1;
            }
        }

        // 最後の文字グループを追加
        $result[] = [$currentChar, $count];

        return $result;
    }

    /**
     * 連長圧縮された結果をデコード（復元）
     *
     * @param array<array{0: string, 1: int}> $encoded エンコードされたデータ
     * @return string 復元された文字列
     */
    public static function decode(array $encoded): string
    {
        $result = '';

        foreach ($encoded as $entry) {
            [$char, $count] = $entry;
            $result .= str_repeat($char, $count);
        }

        return $result;
    }

    /**
     * エンコード後のデータサイズを計算（圧縮率の確認用）
     *
     * @param array<array{0: string, 1: int}> $encoded エンコードされたデータ
     * @return int データサイズ（文字数とカウント数の合計）
     */
    public static function calculateEncodedSize(array $encoded): int
    {
        // 各エントリは文字(1) + カウント(数値)のペア
        return count($encoded) * 2;
    }

    /**
     * 圧縮率を計算
     *
     * @param string $original 元の文字列
     * @param array<array{0: string, 1: int}> $encoded エンコードされたデータ
     * @return float 圧縮率（0.0-1.0、低いほど圧縮されている）
     */
    public static function calculateCompressionRatio(string $original, array $encoded): float
    {
        $originalSize = strlen($original);
        $encodedSize = self::calculateEncodedSize($encoded);

        if ($originalSize === 0) {
            return 0.0;
        }

        return $encodedSize / $originalSize;
    }
}
