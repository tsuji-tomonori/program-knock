<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\SushiRestaurantQueue;

class SushiRestaurantQueueTest
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

    public function testBasicQueueOperations(): void
    {
        $commands = [
            "arrive Alice",
            "arrive Bob",
            "seat 1",
            "arrive Charlie",
            "seat 2"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Alice", "Bob", "Charlie"];
        $this->assertEquals($expected, $result);
    }

    public function testEmptyCommands(): void
    {
        $commands = [];
        $result = SushiRestaurantQueue::processCommands($commands);
        $this->assertEquals([], $result);
    }

    public function testOnlyArrivals(): void
    {
        $commands = [
            "arrive Alice",
            "arrive Bob",
            "arrive Charlie"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $this->assertEquals([], $result);
    }

    public function testOnlySeating(): void
    {
        $commands = [
            "seat 5"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $this->assertEquals([], $result);
    }

    public function testSingleCustomer(): void
    {
        $commands = [
            "arrive Alice",
            "seat 1"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $this->assertEquals(["Alice"], $result);
    }

    public function testMoreSeatsThanCustomers(): void
    {
        $commands = [
            "arrive Alice",
            "arrive Bob",
            "seat 10"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $this->assertEquals(["Alice", "Bob"], $result);
    }

    public function testFIFOOrder(): void
    {
        $commands = [
            "arrive First",
            "arrive Second",
            "arrive Third",
            "arrive Fourth",
            "seat 2",
            "arrive Fifth",
            "seat 3"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["First", "Second", "Third", "Fourth", "Fifth"];
        $this->assertEquals($expected, $result);
    }

    public function testDuplicateCustomers(): void
    {
        $commands = [
            "arrive Alice",
            "arrive Bob",
            "arrive Alice", // 重複 - 無視される
            "seat 3"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Alice", "Bob"];
        $this->assertEquals($expected, $result);
    }

    public function testDuplicateAfterSeating(): void
    {
        $commands = [
            "arrive Alice",
            "seat 1",
            "arrive Alice", // 一度席に着いた後なので再度追加可能
            "seat 1"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Alice", "Alice"];
        $this->assertEquals($expected, $result);
    }

    public function testMultipleSeatingRounds(): void
    {
        $commands = [
            "arrive A",
            "arrive B",
            "arrive C",
            "arrive D",
            "arrive E",
            "seat 2",
            "seat 1",
            "seat 2"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B", "C", "D", "E"];
        $this->assertEquals($expected, $result);
    }

    public function testZeroSeats(): void
    {
        $commands = [
            "arrive Alice",
            "arrive Bob",
            "seat 0",
            "seat 2"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Alice", "Bob"];
        $this->assertEquals($expected, $result);
    }

    public function testComplexScenario(): void
    {
        $commands = [
            "arrive Customer1",
            "arrive Customer2",
            "arrive Customer3",
            "seat 1",
            "arrive Customer4",
            "arrive Customer5",
            "arrive Customer1", // 重複
            "seat 2",
            "arrive Customer6",
            "seat 10"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Customer1", "Customer2", "Customer3", "Customer4", "Customer5", "Customer1", "Customer6"];
        $this->assertEquals($expected, $result);
    }

    public function testLargeQueue(): void
    {
        $commands = [];

        // 100人の顧客を追加
        for ($i = 1; $i <= 100; $i++) {
            $commands[] = "arrive Customer$i";
        }

        // 50人ずつ2回に分けて席に案内
        $commands[] = "seat 50";
        $commands[] = "seat 50";

        $result = SushiRestaurantQueue::processCommands($commands);

        $expected = [];
        for ($i = 1; $i <= 100; $i++) {
            $expected[] = "Customer$i";
        }

        $this->assertEquals($expected, $result);
    }

    public function testSpecialCharacterNames(): void
    {
        $commands = [
            "arrive Alice-Smith",
            "arrive Bob_Jones",
            "arrive Charlie O'Connor",
            "arrive David@email.com",
            "seat 4"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["Alice-Smith", "Bob_Jones", "Charlie O'Connor", "David@email.com"];
        $this->assertEquals($expected, $result);
    }

    public function testUnicodeNames(): void
    {
        $commands = [
            "arrive 田中",
            "arrive 佐藤",
            "arrive 高橋",
            "seat 3"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["田中", "佐藤", "高橋"];
        $this->assertEquals($expected, $result);
    }

    public function testPartialSeating(): void
    {
        $commands = [
            "arrive A",
            "arrive B",
            "arrive C",
            "arrive D",
            "arrive E",
            "seat 2", // A, B が着席
            "arrive F",
            "seat 1", // C が着席
            "seat 3"  // D, E, F が着席
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B", "C", "D", "E", "F"];
        $this->assertEquals($expected, $result);
    }

    public function testAlternatingCommands(): void
    {
        $commands = [
            "arrive A",
            "seat 1",
            "arrive B",
            "seat 1",
            "arrive C",
            "seat 1"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B", "C"];
        $this->assertEquals($expected, $result);
    }

    public function testDuplicateInLargeQueue(): void
    {
        $commands = [
            "arrive A",
            "arrive B",
            "arrive C",
            "arrive A", // 重複
            "arrive D",
            "arrive B", // 重複
            "seat 10"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B", "C", "D"];
        $this->assertEquals($expected, $result);
    }

    public function testEmptyStringName(): void
    {
        $commands = [
            "arrive ",
            "arrive A",
            "seat 2"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["", "A"];
        $this->assertEquals($expected, $result);
    }

    public function testLongCustomerName(): void
    {
        $longName = str_repeat("Very", 50) . "LongName";
        $commands = [
            "arrive $longName",
            "arrive ShortName",
            "seat 2"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = [$longName, "ShortName"];
        $this->assertEquals($expected, $result);
    }

    public function testLargeSeatNumber(): void
    {
        $commands = [
            "arrive A",
            "arrive B",
            "seat 1000000"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B"];
        $this->assertEquals($expected, $result);
    }

    public function testPerformanceTest(): void
    {
        $commands = [];

        // 大量の顧客を追加
        for ($i = 1; $i <= 10000; $i++) {
            $commands[] = "arrive Customer$i";
        }

        // 全員を席に案内
        $commands[] = "seat 10000";

        $startTime = microtime(true);
        $result = SushiRestaurantQueue::processCommands($commands);
        $endTime = microtime(true);

        // パフォーマンスチェック
        if ($endTime - $startTime >= 2.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertCount(10000, $result);

        // 順序確認
        for ($i = 0; $i < 10000; $i++) {
            $this->assertEquals("Customer" . ($i + 1), $result[$i]);
        }
    }

    public function testMixedDuplicatesAndSeating(): void
    {
        $commands = [
            "arrive A",
            "arrive B",
            "arrive A", // 重複
            "seat 1",   // A が着席
            "arrive A", // 再度追加可能
            "arrive C",
            "arrive B", // B はまだキューにいるので重複
            "seat 3"
        ];

        $result = SushiRestaurantQueue::processCommands($commands);
        $expected = ["A", "B", "A", "C"];
        $this->assertEquals($expected, $result);
    }
}
