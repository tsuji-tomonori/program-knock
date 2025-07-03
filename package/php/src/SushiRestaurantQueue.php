<?php

declare(strict_types=1);

namespace ProgramKnock;

class SushiRestaurantQueue
{
    /**
     * 回転寿司屋の席案内システム
     *
     * @param array<string> $commands コマンドのリスト
     * @return array<string> 席に案内された顧客名のリスト
     */
    public static function processCommands(array $commands): array
    {
        $waitingQueue = [];
        $seatedCustomers = [];
        $inQueue = []; // 重複チェック用

        foreach ($commands as $command) {
            $parts = explode(' ', $command, 2);
            $action = $parts[0];

            if ($action === 'arrive') {
                $customerName = $parts[1];

                // 同じ名前の顧客が既にキューにいる場合は追加しない
                if (!in_array($customerName, $inQueue)) {
                    $waitingQueue[] = $customerName;
                    $inQueue[] = $customerName;
                }
            } elseif ($action === 'seat') {
                $seatsAvailable = (int)$parts[1];

                // 利用可能な席数分、または待機中の顧客数分（少ない方）案内する
                $customersToSeat = min($seatsAvailable, count($waitingQueue));

                for ($i = 0; $i < $customersToSeat; $i++) {
                    $customer = array_shift($waitingQueue);
                    $seatedCustomers[] = $customer;

                    // キューから削除されたので重複チェック配列からも削除
                    $inQueueIndex = array_search($customer, $inQueue);
                    if ($inQueueIndex !== false) {
                        array_splice($inQueue, $inQueueIndex, 1);
                    }
                }
            }
        }

        return $seatedCustomers;
    }
}
