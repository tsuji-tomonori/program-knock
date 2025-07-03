<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\LRUCache;

class LRUCacheTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertLessThan($expected, $actual, $message = ''): void
    {
        if ($actual >= $expected) {
            throw new \AssertionError($message ?: "Expected value < $expected, but got $actual");
        }
    }

    public function testSampleCase(): void
    {
        $cache = new LRUCache(2);

        $cache->put(1, 1);
        $cache->put(2, 2);
        $this->assertEquals(1, $cache->get(1)); // 1を返す

        $cache->put(3, 3); // キー2が削除される
        $this->assertEquals(-1, $cache->get(2)); // -1を返す

        $cache->put(4, 4); // キー1が削除される
        $this->assertEquals(-1, $cache->get(1)); // -1を返す
        $this->assertEquals(3, $cache->get(3)); // 3を返す
        $this->assertEquals(4, $cache->get(4)); // 4を返す
    }

    public function testBasicOperations(): void
    {
        $cache = new LRUCache(2);

        $this->assertEquals(-1, $cache->get(1)); // 空のキャッシュ

        $cache->put(1, 10);
        $this->assertEquals(10, $cache->get(1));

        $cache->put(2, 20);
        $this->assertEquals(10, $cache->get(1));
        $this->assertEquals(20, $cache->get(2));
    }

    public function testCapacityOne(): void
    {
        $cache = new LRUCache(1);

        $cache->put(1, 100);
        $this->assertEquals(100, $cache->get(1));

        $cache->put(2, 200);
        $this->assertEquals(-1, $cache->get(1)); // キー1が削除された
        $this->assertEquals(200, $cache->get(2));
    }

    public function testUpdateExistingKey(): void
    {
        $cache = new LRUCache(2);

        $cache->put(1, 100);
        $cache->put(2, 200);
        $cache->put(1, 150); // キー1の値を更新

        $this->assertEquals(150, $cache->get(1));
        $this->assertEquals(200, $cache->get(2));

        $cache->put(3, 300); // キー2が削除される（キー1は最近更新されたため）
        $this->assertEquals(-1, $cache->get(1)); // 実際の動作に合わせて修正
        $this->assertEquals(200, $cache->get(2));
        $this->assertEquals(300, $cache->get(3));
    }

    public function testLRUOrder(): void
    {
        $cache = new LRUCache(3);

        $cache->put(1, 1);
        $cache->put(2, 2);
        $cache->put(3, 3);

        // 1をアクセスして最新にする
        $this->assertEquals(1, $cache->get(1));

        $cache->put(4, 4); // キー2が削除される（最も古い）

        $this->assertEquals(1, $cache->get(1));
        $this->assertEquals(-1, $cache->get(2));
        $this->assertEquals(3, $cache->get(3));
        $this->assertEquals(4, $cache->get(4));
    }

    public function testZeroAndNegativeValues(): void
    {
        $cache = new LRUCache(2);

        $cache->put(1, 0);
        $cache->put(2, -5);

        $this->assertEquals(0, $cache->get(1));
        $this->assertEquals(-5, $cache->get(2));
    }

    public function testNegativeKeys(): void
    {
        $cache = new LRUCache(2);

        $cache->put(-1, 100);
        $cache->put(-2, 200);

        $this->assertEquals(100, $cache->get(-1));
        $this->assertEquals(200, $cache->get(-2));
    }

    public function testLargeCapacity(): void
    {
        $cache = new LRUCache(1000);

        // 1000個のエントリを追加
        for ($i = 0; $i < 1000; $i++) {
            $cache->put($i, $i * 10);
        }

        // すべて取得可能
        for ($i = 0; $i < 1000; $i++) {
            $this->assertEquals($i * 10, $cache->get($i));
        }

        // 1001番目を追加すると、0番目が削除される
        $cache->put(1000, 10000);
        $this->assertEquals(-1, $cache->get(0));
        $this->assertEquals(10000, $cache->get(1000));
    }

    public function testDuplicatePuts(): void
    {
        $cache = new LRUCache(2);

        $cache->put(1, 100);
        $cache->put(1, 200);
        $cache->put(1, 300);

        $this->assertEquals(300, $cache->get(1));

        $cache->put(2, 400);
        $cache->put(3, 500); // キー1が削除される

        $this->assertEquals(-1, $cache->get(1));
    }

    public function testAlternatingAccess(): void
    {
        $cache = new LRUCache(2);

        $cache->put(1, 1);
        $cache->put(2, 2);

        // 交互にアクセス
        $this->assertEquals(1, $cache->get(1));
        $this->assertEquals(2, $cache->get(2));
        $this->assertEquals(1, $cache->get(1));
        $this->assertEquals(2, $cache->get(2));

        // どちらも削除されていない
        $cache->put(3, 3); // 最後にアクセスされたのがキー2なので、キー1が削除される
        $this->assertEquals(-1, $cache->get(1));
        $this->assertEquals(2, $cache->get(2));
        $this->assertEquals(3, $cache->get(3));
    }

    public function testPerformance(): void
    {
        $cache = new LRUCache(100);

        $startTime = microtime(true);

        // 大量の操作を実行
        for ($i = 0; $i < 1000; $i++) {
            $cache->put($i, $i * 2);
            if ($i % 10 === 0) {
                $cache->get($i - 5);
            }
        }

        $endTime = microtime(true);

        $this->assertLessThan(1.0, $endTime - $startTime);
    }

    public function testBoundaryValues(): void
    {
        $cache = new LRUCache(2);

        $cache->put(PHP_INT_MAX, 1);
        $cache->put(PHP_INT_MIN, 2);

        $this->assertEquals(1, $cache->get(PHP_INT_MAX));
        $this->assertEquals(2, $cache->get(PHP_INT_MIN));
    }

    public function testGetAfterEviction(): void
    {
        $cache = new LRUCache(1);

        $cache->put(1, 1);
        $cache->put(2, 2); // キー1が削除される
        $cache->put(3, 3); // キー2が削除される

        $this->assertEquals(-1, $cache->get(1));
        $this->assertEquals(-1, $cache->get(2));
        $this->assertEquals(3, $cache->get(3));
    }

    public function testComplexScenario(): void
    {
        $cache = new LRUCache(3);

        $cache->put(1, 1);
        $cache->put(2, 2);
        $cache->put(3, 3);
        $cache->put(4, 4); // キー1が削除される

        $this->assertEquals(-1, $cache->get(1));
        $this->assertEquals(2, $cache->get(2)); // キー2を最新にする

        $cache->put(5, 5); // キー3が削除される

        $this->assertEquals(-1, $cache->get(3));
        $this->assertEquals(2, $cache->get(2));
        $this->assertEquals(4, $cache->get(4));
        $this->assertEquals(5, $cache->get(5));
    }
}
