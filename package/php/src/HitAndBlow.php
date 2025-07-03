<?php

declare(strict_types=1);

namespace ProgramKnock;

class HitAndBlow
{
    /**
     * Hit & Blowゲームの判定
     *
     * @param array<int> $answer 正解の数列
     * @param array<int> $guess 推測の数列
     * @return array{0: int, 1: int} [Hit数, Blow数]
     */
    public static function calculate(array $answer, array $guess): array
    {
        $hits = 0;
        $blows = 0;

        // 位置と値が一致するものをカウント（Hit）
        $answerForBlows = [];
        $guessForBlows = [];

        for ($i = 0; $i < count($answer); $i++) {
            if ($answer[$i] === $guess[$i]) {
                $hits++;
            } else {
                // Hitしなかった要素は後でBlow判定に使用
                $answerForBlows[] = $answer[$i];
                $guessForBlows[] = $guess[$i];
            }
        }

        // 値は合っているが位置が違うものをカウント（Blow）
        $answerCounts = array_count_values($answerForBlows);
        $guessCounts = array_count_values($guessForBlows);

        foreach ($guessCounts as $value => $guessCount) {
            if (isset($answerCounts[$value])) {
                $blows += min($guessCount, $answerCounts[$value]);
            }
        }

        return [$hits, $blows];
    }

    /**
     * ゲームクリア判定
     *
     * @param array<int> $answer 正解の数列
     * @param array<int> $guess 推測の数列
     * @return bool 全てHitならtrue
     */
    public static function isGameClear(array $answer, array $guess): bool
    {
        [$hits, $blows] = self::calculate($answer, $guess);
        return $hits === count($answer) && $blows === 0;
    }

    /**
     * スコアの文字列表現を取得
     *
     * @param array<int> $answer 正解の数列
     * @param array<int> $guess 推測の数列
     * @return string "Hit: X, Blow: Y" 形式の文字列
     */
    public static function getScoreString(array $answer, array $guess): string
    {
        [$hits, $blows] = self::calculate($answer, $guess);
        return "Hit: {$hits}, Blow: {$blows}";
    }

    /**
     * 複数の推測を一括判定
     *
     * @param array<int> $answer 正解の数列
     * @param array<array<int>> $guesses 推測のリスト
     * @return array<array{0: int, 1: int}> 各推測のHit・Blow数の配列
     */
    public static function calculateMultiple(array $answer, array $guesses): array
    {
        $results = [];

        foreach ($guesses as $guess) {
            $results[] = self::calculate($answer, $guess);
        }

        return $results;
    }

    /**
     * 入力値の妥当性をチェック
     *
     * @param array<int> $numbers チェック対象の数列
     * @param int $expectedLength 期待される長さ
     * @return bool 妥当ならtrue
     */
    public static function validateInput(array $numbers, int $expectedLength): bool
    {
        // 長さチェック
        if (count($numbers) !== $expectedLength) {
            return false;
        }

        // 重複チェック
        if (count($numbers) !== count(array_unique($numbers))) {
            return false;
        }

        // 範囲チェック（0-9）
        foreach ($numbers as $number) {
            if (!is_int($number) || $number < 0 || $number > 9) {
                return false;
            }
        }

        return true;
    }
}
