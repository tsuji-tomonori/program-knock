<?php

declare(strict_types=1);

namespace ProgramKnock;

class FakeChineseChecker
{
    /**
     * 偽中国語チェッカー（漢字のみかチェック）
     *
     * @param string $text チェック対象の文字列
     * @return string "正"（漢字のみ）または"誤"（漢字以外を含む）
     */
    public static function check(string $text): string
    {
        if (empty($text)) {
            return '誤';
        }

        // 文字列を1文字ずつチェック
        $chars = self::mbStrSplit($text);

        foreach ($chars as $char) {
            if (!self::isKanji($char)) {
                return '誤';
            }
        }

        return '正';
    }

    /**
     * 文字列を1文字ずつ分割（UTF-8対応）
     *
     * @param string $text 分割対象の文字列
     * @return array<string> 1文字ずつの配列
     */
    private static function mbStrSplit(string $text): array
    {
        // UTF-8文字列をpreg_splitで1文字ずつ分割
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return $chars ?: [];
    }

    /**
     * 指定された文字が漢字かどうかをチェック
     *
     * @param string $char チェック対象の文字
     * @return bool 漢字の場合true、そうでなければfalse
     */
    private static function isKanji(string $char): bool
    {
        // UTF-8文字のコードポイントを取得
        $bytes = unpack('C*', $char);
        if (!$bytes) {
            return false;
        }

        $codePoint = self::utf8ToCodePoint($char);

        // 漢字のUnicodeブロック範囲をチェック
        return (
            // CJK統合漢字（基本ブロック）
            ($codePoint >= 0x4E00 && $codePoint <= 0x9FFF) ||
            // CJK統合漢字拡張A
            ($codePoint >= 0x3400 && $codePoint <= 0x4DBF) ||
            // CJK統合漢字拡張B
            ($codePoint >= 0x20000 && $codePoint <= 0x2A6DF) ||
            // CJK統合漢字拡張C
            ($codePoint >= 0x2A700 && $codePoint <= 0x2B73F) ||
            // CJK統合漢字拡張D
            ($codePoint >= 0x2B740 && $codePoint <= 0x2B81F) ||
            // CJK統合漢字拡張E
            ($codePoint >= 0x2B820 && $codePoint <= 0x2CEAF) ||
            // CJK統合漢字拡張F
            ($codePoint >= 0x2CEB0 && $codePoint <= 0x2EBEF) ||
            // CJK統合漢字拡張G
            ($codePoint >= 0x30000 && $codePoint <= 0x3134F)
        );
    }

    /**
     * UTF-8文字列からUnicodeコードポイントを取得
     *
     * @param string $char UTF-8文字
     * @return int Unicodeコードポイント
     */
    private static function utf8ToCodePoint(string $char): int
    {
        $bytes = unpack('C*', $char);
        if (!$bytes) {
            return 0;
        }

        $byte1 = $bytes[1];

        // 1バイト文字 (ASCII)
        if ($byte1 <= 0x7F) {
            return $byte1;
        }

        // 2バイト文字
        if (($byte1 & 0xE0) === 0xC0 && count($bytes) >= 2) {
            return (($byte1 & 0x1F) << 6) | ($bytes[2] & 0x3F);
        }

        // 3バイト文字
        if (($byte1 & 0xF0) === 0xE0 && count($bytes) >= 3) {
            return (($byte1 & 0x0F) << 12) | (($bytes[2] & 0x3F) << 6) | ($bytes[3] & 0x3F);
        }

        // 4バイト文字
        if (($byte1 & 0xF8) === 0xF0 && count($bytes) >= 4) {
            return (($byte1 & 0x07) << 18) | (($bytes[2] & 0x3F) << 12) | (($bytes[3] & 0x3F) << 6) | ($bytes[4] & 0x3F);
        }

        return 0;
    }
}
