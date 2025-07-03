<?php

declare(strict_types=1);

namespace ProgramKnock;

class ProductListDeduplication
{
    /**
     * 商品リストの重複除去と価格順ソート
     *
     * @param array<array{0: string, 1: int}> $products 商品名と価格のタプルのリスト
     * @return array<array{0: string, 1: int}> 重複除去後、価格降順でソートした商品リスト
     */
    public static function deduplicateAndSort(array $products): array
    {
        if (empty($products)) {
            return [];
        }

        // 商品名をキーとして、最高価格を保持
        $productMap = [];
        $orderMap = [];
        $orderIndex = 0;

        foreach ($products as $product) {
            $name = $product[0];
            $price = $product[1];

            if (!isset($productMap[$name])) {
                $productMap[$name] = $price;
                $orderMap[$name] = $orderIndex++;
            } else {
                // より高い価格で更新
                if ($price > $productMap[$name]) {
                    $productMap[$name] = $price;
                }
            }
        }

        // 結果配列を作成
        $result = [];
        foreach ($productMap as $name => $price) {
            $result[] = [$name, $price, $orderMap[$name]];
        }

        // 価格降順でソート（価格が同じ場合は元の順序を維持）
        usort($result, function($a, $b) {
            if ($a[1] === $b[1]) {
                return $a[2] <=> $b[2]; // 元の順序を維持
            }
            return $b[1] <=> $a[1]; // 価格降順
        });

        // orderIndexを除去して結果を返す
        return array_map(function($item) {
            return [$item[0], $item[1]];
        }, $result);
    }
}
