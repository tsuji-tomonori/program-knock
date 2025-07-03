<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\SalesAggregation;

class SalesAggregationTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertCount($expected, $actual, $message = ''): void
    {
        $count = count($actual);
        if ($count !== $expected) {
            throw new \AssertionError($message ?: "Expected count $expected, but got $count");
        }
    }

    public function testSampleCase1(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 3],
            ['store' => 'Tokyo', 'payment_method' => 'Cash', 'product' => 'Apple', 'quantity' => 2],
            ['store' => 'Osaka', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 5],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 1],
            ['store' => 'Osaka', 'payment_method' => 'Credit', 'product' => 'Orange', 'quantity' => 4],
            ['store' => 'Tokyo', 'payment_method' => 'Cash', 'product' => 'Banana', 'quantity' => 2],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 2]
        ];

        $result = SalesAggregation::aggregate($sales);

        // 期待される集計結果を確認
        $this->assertCount(5, $result);

        $key1 = json_encode(['Tokyo', 'Credit', 'Apple']);
        $this->assertEquals(6, $result[$key1]);

        $key2 = json_encode(['Tokyo', 'Cash', 'Apple']);
        $this->assertEquals(2, $result[$key2]);

        $key3 = json_encode(['Osaka', 'Credit', 'Apple']);
        $this->assertEquals(5, $result[$key3]);

        $key4 = json_encode(['Osaka', 'Credit', 'Orange']);
        $this->assertEquals(4, $result[$key4]);

        $key5 = json_encode(['Tokyo', 'Cash', 'Banana']);
        $this->assertEquals(2, $result[$key5]);
    }

    public function testSampleCase2(): void
    {
        $sales = [];
        $result = SalesAggregation::aggregate($sales);
        $this->assertEquals([], $result);
    }

    public function testSampleCase3(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 4],
            ['store' => 'Tokyo', 'payment_method' => 'Cash', 'product' => 'Apple', 'quantity' => 5],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 3],
            ['store' => 'Tokyo', 'payment_method' => 'MobilePay', 'product' => 'Apple', 'quantity' => 2]
        ];

        $result = SalesAggregation::aggregate($sales);

        $this->assertCount(3, $result);

        $key1 = json_encode(['Tokyo', 'Credit', 'Apple']);
        $this->assertEquals(7, $result[$key1]);

        $key2 = json_encode(['Tokyo', 'Cash', 'Apple']);
        $this->assertEquals(5, $result[$key2]);

        $key3 = json_encode(['Tokyo', 'MobilePay', 'Apple']);
        $this->assertEquals(2, $result[$key3]);
    }

    public function testSingleSale(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 10]
        ];

        $result = SalesAggregation::aggregate($sales);
        $this->assertCount(1, $result);

        $key = json_encode(['Tokyo', 'Credit', 'Apple']);
        $this->assertEquals(10, $result[$key]);
    }

    public function testMultipleStores(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 1],
            ['store' => 'Osaka', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 2],
            ['store' => 'Kyoto', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 3]
        ];

        $result = SalesAggregation::aggregate($sales);
        $this->assertCount(3, $result);
    }

    public function testMultiplePaymentMethods(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 1],
            ['store' => 'Tokyo', 'payment_method' => 'Cash', 'product' => 'Apple', 'quantity' => 2],
            ['store' => 'Tokyo', 'payment_method' => 'MobilePay', 'product' => 'Apple', 'quantity' => 3],
            ['store' => 'Tokyo', 'payment_method' => 'PayPay', 'product' => 'Apple', 'quantity' => 4]
        ];

        $result = SalesAggregation::aggregate($sales);
        $this->assertCount(4, $result);
    }

    public function testMultipleProducts(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 1],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Banana', 'quantity' => 2],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Orange', 'quantity' => 3],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Grape', 'quantity' => 4]
        ];

        $result = SalesAggregation::aggregate($sales);
        $this->assertCount(4, $result);
    }

    public function testLargeQuantities(): void
    {
        $sales = [
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 999999],
            ['store' => 'Tokyo', 'payment_method' => 'Credit', 'product' => 'Apple', 'quantity' => 1]
        ];

        $result = SalesAggregation::aggregate($sales);

        $key = json_encode(['Tokyo', 'Credit', 'Apple']);
        $this->assertEquals(1000000, $result[$key]);
    }

    public function testSpecialCharactersInNames(): void
    {
        $sales = [
            ['store' => 'Tokyo\'s Store', 'payment_method' => 'Credit & Cash', 'product' => 'Apple/Orange', 'quantity' => 5],
            ['store' => 'Tokyo\'s Store', 'payment_method' => 'Credit & Cash', 'product' => 'Apple/Orange', 'quantity' => 3]
        ];

        $result = SalesAggregation::aggregate($sales);

        $key = json_encode(['Tokyo\'s Store', 'Credit & Cash', 'Apple/Orange']);
        $this->assertEquals(8, $result[$key]);
    }

    public function testJapaneseCharacters(): void
    {
        $sales = [
            ['store' => '東京', 'payment_method' => 'クレジット', 'product' => 'りんご', 'quantity' => 5],
            ['store' => '東京', 'payment_method' => 'クレジット', 'product' => 'りんご', 'quantity' => 3]
        ];

        $result = SalesAggregation::aggregate($sales);

        $key = json_encode(['東京', 'クレジット', 'りんご']);
        $this->assertEquals(8, $result[$key]);
    }

    public function testKeyParsingUtility(): void
    {
        $key = json_encode(['Tokyo', 'Credit', 'Apple']);
        $parsed = SalesAggregation::parseKey($key);

        $this->assertEquals(['Tokyo', 'Credit', 'Apple'], $parsed);
    }

    public function testLargeDataset(): void
    {
        $sales = [];

        // 1000個の売上データを生成
        for ($i = 0; $i < 1000; $i++) {
            $sales[] = [
                'store' => 'Store' . ($i % 10),
                'payment_method' => 'Method' . ($i % 5),
                'product' => 'Product' . ($i % 20),
                'quantity' => ($i % 10) + 1
            ];
        }

        $startTime = microtime(true);
        $result = SalesAggregation::aggregate($sales);
        $endTime = microtime(true);

        // パフォーマンスチェック
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // 結果の妥当性チェック（10店舗 × 5支払い方法 × 20商品 = 最大1000組み合わせ）
        if (count($result) > 1000) {
            throw new \AssertionError("Too many combinations in result");
        }
    }

    public function testComplexAggregation(): void
    {
        $sales = [
            ['store' => 'A', 'payment_method' => 'X', 'product' => 'P1', 'quantity' => 10],
            ['store' => 'A', 'payment_method' => 'Y', 'product' => 'P1', 'quantity' => 20],
            ['store' => 'B', 'payment_method' => 'X', 'product' => 'P1', 'quantity' => 30],
            ['store' => 'A', 'payment_method' => 'X', 'product' => 'P2', 'quantity' => 40],
            ['store' => 'A', 'payment_method' => 'X', 'product' => 'P1', 'quantity' => 50]
        ];

        $result = SalesAggregation::aggregate($sales);

        $key1 = json_encode(['A', 'X', 'P1']);
        $this->assertEquals(60, $result[$key1]); // 10 + 50

        $key2 = json_encode(['A', 'Y', 'P1']);
        $this->assertEquals(20, $result[$key2]);

        $key3 = json_encode(['B', 'X', 'P1']);
        $this->assertEquals(30, $result[$key3]);

        $key4 = json_encode(['A', 'X', 'P2']);
        $this->assertEquals(40, $result[$key4]);
    }
}
