<?php

declare(strict_types=1);

namespace ProgramKnock;

class NewYearHolidayCalculator
{
    /**
     * 年末年始休暇の日付と日数を計算
     *
     * @param int $year 対象年
     * @return array{0: string, 1: string, 2: int} [開始日, 終了日, 日数]
     */
    public static function calculate(int $year): array
    {
        // 基本期間: 12月29日〜1月3日
        $baseStart = self::createDate($year - 1, 12, 29);
        $baseEnd = self::createDate($year, 1, 3);

        $startDate = $baseStart;
        $endDate = $baseEnd;

        // 前方向に拡張（土日祝日が隣接している場合）
        $currentDate = clone $startDate;
        $currentDate->modify('-1 day');

        while (self::isWeekendOrHoliday($currentDate)) {
            $startDate = clone $currentDate;
            $currentDate->modify('-1 day');
        }

        // 後方向に拡張（土日祝日が隣接している場合）
        $currentDate = clone $endDate;
        $currentDate->modify('+1 day');

        while (self::isWeekendOrHoliday($currentDate)) {
            $endDate = clone $currentDate;
            $currentDate->modify('+1 day');
        }

        // 日数を計算
        $days = (int)$startDate->diff($endDate)->format('%a') + 1;

        return [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
            $days
        ];
    }

    /**
     * 指定日付が土日または祝日かどうかを判定
     */
    private static function isWeekendOrHoliday(\DateTime $date): bool
    {
        $dayOfWeek = (int)$date->format('w');

        // 土日の場合
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            return true;
        }

        // 祝日の場合
        return self::isHoliday($date);
    }

    /**
     * 指定日付が祝日かどうかを判定
     */
    private static function isHoliday(\DateTime $date): bool
    {
        $year = (int)$date->format('Y');
        $month = (int)$date->format('n');
        $day = (int)$date->format('j');

        switch ($month) {
            case 1:
                // 元日
                if ($day === 1) return true;
                // 成人の日（1月第2月曜日）
                if ($day === self::getNthMondayOfMonth($year, 1, 2)) return true;
                break;

            case 2:
                // 天皇誕生日
                if ($day === 23) return true;
                break;

            case 5:
                // 憲法記念日
                if ($day === 3) return true;
                // みどりの日
                if ($day === 4) return true;
                // こどもの日
                if ($day === 5) return true;
                break;

            case 7:
                // 海の日（7月第3月曜日）
                if ($day === self::getNthMondayOfMonth($year, 7, 3)) return true;
                break;

            case 8:
                // 山の日
                if ($day === 11) return true;
                break;

            case 9:
                // 敬老の日（9月第3月曜日）
                if ($day === self::getNthMondayOfMonth($year, 9, 3)) return true;
                // 秋分の日
                if ($day === self::getAutumnalEquinoxDay($year)) return true;
                break;

            case 10:
                // スポーツの日（10月第2月曜日）
                if ($day === self::getNthMondayOfMonth($year, 10, 2)) return true;
                break;

            case 11:
                // 文化の日
                if ($day === 3) return true;
                // 勤労感謝の日
                if ($day === 23) return true;
                break;
        }

        return false;
    }

    /**
     * 指定月のN番目の月曜日の日付を取得
     */
    private static function getNthMondayOfMonth(int $year, int $month, int $nth): int
    {
        $firstDay = self::createDate($year, $month, 1);
        $firstDayOfWeek = (int)$firstDay->format('w');

        // 最初の月曜日を見つける
        $firstMondayOffset = (8 - $firstDayOfWeek) % 7;
        if ($firstDayOfWeek === 1) {
            $firstMondayOffset = 0;
        }

        // N番目の月曜日の日付
        $nthMondayDay = 1 + $firstMondayOffset + ($nth - 1) * 7;

        return $nthMondayDay;
    }

    /**
     * 秋分の日を計算（簡易版）
     */
    private static function getAutumnalEquinoxDay(int $year): int
    {
        // 簡易計算式による秋分の日の推定
        // 実際の計算はより複雑ですが、この範囲では22日または23日
        if ($year % 4 === 0) {
            return 22;
        } else {
            return 23;
        }
    }

    /**
     * DateTime オブジェクトを作成
     */
    private static function createDate(int $year, int $month, int $day): \DateTime
    {
        return new \DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
    }
}
