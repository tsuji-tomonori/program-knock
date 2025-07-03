<?php

declare(strict_types=1);

namespace ProgramKnock;

class WordFrequency
{
    /**
     * 単語の出現回数をカウント
     *
     * @param string $text 空白区切りの単語を含む文字列
     * @return array<string, int> 各単語の出現回数の辞書（出現回数降順、同じ場合は辞書順）
     */
    public static function count(string $text): array
    {
        if (trim($text) === '') {
            return [];
        }

        // 単語に分割
        $words = explode(' ', trim($text));

        // 出現回数をカウント
        $wordCounts = [];
        foreach ($words as $word) {
            if ($word !== '') {
                if (isset($wordCounts[$word])) {
                    $wordCounts[$word]++;
                } else {
                    $wordCounts[$word] = 1;
                }
            }
        }

        // 出現回数の降順、同じ場合は辞書順でソート
        uksort($wordCounts, function($a, $b) use ($wordCounts) {
            if ($wordCounts[$a] !== $wordCounts[$b]) {
                return $wordCounts[$b] - $wordCounts[$a]; // 降順
            }
            return strcmp($a, $b); // 辞書順
        });

        return $wordCounts;
    }
}
