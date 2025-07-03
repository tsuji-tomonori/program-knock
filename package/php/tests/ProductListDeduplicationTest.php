<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\ProductListDeduplication;

class ProductListDeduplicationTest
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
        $products = [["apple", 300], ["banana", 200], ["apple", 250], ["orange", 400]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["orange", 400], ["apple", 300], ["banana", 200]];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase2(): void
    {
        $products = [["watch", 5000], ["watch", 5000], ["ring", 7000], ["ring", 6500]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["ring", 7000], ["watch", 5000]];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase3(): void
    {
        $products = [["pen", 100], ["notebook", 200], ["eraser", 50], ["pen", 150]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["notebook", 200], ["pen", 150], ["eraser", 50]];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase4(): void
    {
        $products = [];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [];
        $this->assertEquals($expected, $result);
    }

    public function testSampleCase5(): void
    {
        $products = [["bag", 1200], ["shoes", 3000], ["bag", 1000], ["hat", 2500]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["shoes", 3000], ["hat", 2500], ["bag", 1200]];
        $this->assertEquals($expected, $result);
    }

    public function testSingleProduct(): void
    {
        $products = [["laptop", 80000]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["laptop", 80000]];
        $this->assertEquals($expected, $result);
    }

    public function testNoDuplicates(): void
    {
        $products = [["mouse", 2000], ["keyboard", 5000], ["monitor", 30000]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["monitor", 30000], ["keyboard", 5000], ["mouse", 2000]];
        $this->assertEquals($expected, $result);
    }

    public function testAllSameName(): void
    {
        $products = [["phone", 50000], ["phone", 80000], ["phone", 60000], ["phone", 70000]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["phone", 80000]];
        $this->assertEquals($expected, $result);
    }

    public function testAllSamePrice(): void
    {
        $products = [["tablet", 50000], ["camera", 50000], ["speaker", 50000]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["tablet", 50000], ["camera", 50000], ["speaker", 50000]];
        $this->assertEquals($expected, $result);
    }

    public function testLowerPriceFirst(): void
    {
        $products = [["book", 500], ["book", 1000], ["book", 800]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["book", 1000]];
        $this->assertEquals($expected, $result);
    }

    public function testHigherPriceFirst(): void
    {
        $products = [["book", 1000], ["book", 500], ["book", 800]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["book", 1000]];
        $this->assertEquals($expected, $result);
    }

    public function testMultipleDuplicateGroups(): void
    {
        $products = [
            ["A", 100], ["B", 200], ["A", 150], ["C", 300],
            ["B", 250], ["A", 120], ["C", 280]
        ];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["C", 300], ["B", 250], ["A", 150]];
        $this->assertEquals($expected, $result);
    }

    public function testOrderPreservationWithSamePrice(): void
    {
        $products = [["first", 100], ["second", 200], ["third", 100], ["fourth", 200]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["second", 200], ["fourth", 200], ["first", 100], ["third", 100]];
        $this->assertEquals($expected, $result);
    }

    public function testSpecialCharactersInNames(): void
    {
        $products = [["item-1", 500], ["item_2", 600], ["item@3", 400], ["item-1", 550]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["item_2", 600], ["item-1", 550], ["item@3", 400]];
        $this->assertEquals($expected, $result);
    }

    public function testLongProductNames(): void
    {
        $products = [
            ["very_long_product_name_with_many_characters_123456", 1000],
            ["short", 2000],
            ["very_long_product_name_with_many_characters_123456", 1500]
        ];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [
            ["short", 2000],
            ["very_long_product_name_with_many_characters_123456", 1500]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testNumericStringsAsNames(): void
    {
        $products = [["123", 400], ["456", 300], ["123", 500], ["789", 600]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [[789, 600], [123, 500], [456, 300]];
        $this->assertEquals($expected, $result);
    }

    public function testMinMaxPrices(): void
    {
        $products = [["cheap", 1], ["expensive", 1000000], ["cheap", 2], ["expensive", 999999]];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [["expensive", 1000000], ["cheap", 2]];
        $this->assertEquals($expected, $result);
    }

    public function testManyProductsWithDuplicates(): void
    {
        $products = [
            ["product1", 100], ["product2", 200], ["product3", 300],
            ["product1", 150], ["product2", 180], ["product4", 400],
            ["product3", 320], ["product5", 500], ["product1", 120]
        ];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [
            ["product5", 500], ["product4", 400], ["product3", 320],
            ["product2", 200], ["product1", 150]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testLargeDataset(): void
    {
        $products = [];

        // Generate 1000 products with duplicates
        for ($i = 0; $i < 1000; $i++) {
            $name = "product" . ($i % 100); // 100 unique product names
            $price = ($i % 50) * 100 + 100; // Prices from 100 to 5000
            $products[] = [$name, $price];
        }

        $startTime = microtime(true);
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Should have exactly 100 unique products
        $this->assertCount(100, $result);

        // Verify sorting (prices should be in descending order)
        for ($i = 0; $i < count($result) - 1; $i++) {
            if ($result[$i][1] < $result[$i + 1][1]) {
                throw new \AssertionError("Result is not sorted by price descending");
            }
        }
    }

    public function testComplexScenario(): void
    {
        $products = [
            ["apple", 300], ["banana", 200], ["cherry", 500],
            ["apple", 350], ["banana", 150], ["date", 400],
            ["apple", 280], ["elderberry", 600], ["banana", 250],
            ["cherry", 480], ["fig", 320], ["date", 450]
        ];
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $expected = [
            ["elderberry", 600], ["cherry", 500], ["date", 450],
            ["apple", 350], ["fig", 320], ["banana", 250]
        ];
        $this->assertEquals($expected, $result);
    }

    public function testPerformanceWithManyDuplicates(): void
    {
        $products = [];

        // Generate 10000 products but only 10 unique names
        for ($i = 0; $i < 10000; $i++) {
            $name = "item" . ($i % 10);
            $price = $i + 1; // Each product has a unique price
            $products[] = [$name, $price];
        }

        $startTime = microtime(true);
        $result = ProductListDeduplication::deduplicateAndSort($products);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 2.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        // Should have exactly 10 unique products with highest prices
        $this->assertCount(10, $result);

        // Verify each product has the maximum price for its name
        foreach ($result as $item) {
            $name = $item[0];
            $price = $item[1];

            // Extract the expected maximum price for this name
            $itemIndex = (int)substr($name, 4); // Extract number from "itemX"
            // Last occurrence of "itemX" is at index 9990 + X, so price is 9991 + X
            $expectedMaxPrice = 9991 + $itemIndex;

            $this->assertEquals($expectedMaxPrice, $price);
        }
    }

    public function testStabilityOfSort(): void
    {
        // Test that products with same price maintain their relative order
        $products = [
            ["first", 100],
            ["second", 200],
            ["third", 100],
            ["fourth", 200],
            ["fifth", 100]
        ];
        $result = ProductListDeduplication::deduplicateAndSort($products);

        // All with price 200 should come first, maintaining order: second, fourth
        // All with price 100 should come after, maintaining order: first, third, fifth
        $expected = [
            ["second", 200], ["fourth", 200],
            ["first", 100], ["third", 100], ["fifth", 100]
        ];
        $this->assertEquals($expected, $result);
    }
}
