<?php

declare(strict_types=1);

namespace ProgramKnock;

class SalesAggregation
{
    /**
     * 商品ごとの売上集計
     *
     * @param array<array{store: string, payment_method: string, product: string, quantity: int}> $sales 売上データのリスト
     * @return array<string, int> (店舗名, 支払い方法, 商品名)のタプルをキーとした売上数量の辞書
     */
    public static function aggregate(array $sales): array
    {
        $aggregated = [];

        foreach ($sales as $sale) {
            // タプルキーを文字列として作成
            $key = self::createKey($sale['store'], $sale['payment_method'], $sale['product']);

            if (isset($aggregated[$key])) {
                $aggregated[$key] += $sale['quantity'];
            } else {
                $aggregated[$key] = $sale['quantity'];
            }
        }

        return $aggregated;
    }

    /**
     * タプルキーを文字列として作成
     *
     * @param string $store 店舗名
     * @param string $paymentMethod 支払い方法
     * @param string $product 商品名
     * @return string タプルキーの文字列表現
     */
    private static function createKey(string $store, string $paymentMethod, string $product): string
    {
        // PHPではタプルをキーにできないため、JSONエンコードして文字列キーを作成
        return json_encode([$store, $paymentMethod, $product]);
    }

    /**
     * キーからタプルを復元（テスト用）
     *
     * @param string $key タプルキーの文字列表現
     * @return array{0: string, 1: string, 2: string} タプル
     */
    public static function parseKey(string $key): array
    {
        return json_decode($key, true);
    }
}
