<?php

declare(strict_types=1);

namespace ProgramKnock;

class CustomerDataDeduplication
{
    /**
     * 顧客IDの重複を除去し、最初の出現順を保持
     *
     * @param array<int> $customerIds 顧客IDのリスト
     * @return array<int> 重複を除去した顧客IDのタプル（配列で表現）
     */
    public static function removeDuplicates(array $customerIds): array
    {
        $seen = [];
        $result = [];

        foreach ($customerIds as $customerId) {
            if (!isset($seen[$customerId])) {
                $seen[$customerId] = true;
                $result[] = $customerId;
            }
        }

        return $result;
    }
}
