<?php

declare(strict_types=1);

namespace ProgramKnock;

class CalcCpk
{
    public static function calcCpk(float $usl, float $lsl, array $data): float
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Data array cannot be empty');
        }

        $n = count($data);
        $mean = array_sum($data) / $n;

        if ($n === 1) {
            $stdDev = 0.0;
        } else {
            $variance = array_sum(array_map(fn($x) => ($x - $mean) ** 2, $data)) / ($n - 1);
            $stdDev = sqrt($variance);
        }

        if ($stdDev === 0.0) {
            throw new \InvalidArgumentException('Standard deviation cannot be zero');
        }

        $cp = ($usl - $lsl) / (6 * $stdDev);

        $center = ($usl + $lsl) / 2;
        $range = $usl - $lsl;
        $k = abs($mean - $center) / ($range / 2);

        $cpk = (1 - $k) * $cp;

        return round($cpk, 3);
    }
}
