package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class ProductDeduplicationTest {

    @Test
    void testSample1() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100),
            new ProductDeduplication.Product("Banana", 80),
            new ProductDeduplication.Product("Apple", 120),
            new ProductDeduplication.Product("Orange", 90),
            new ProductDeduplication.Product("Banana", 70)
        );

        List<ProductDeduplication.Product> expected = Arrays.asList(
            new ProductDeduplication.Product("Apple", 120),
            new ProductDeduplication.Product("Orange", 90),
            new ProductDeduplication.Product("Banana", 80)
        );

        assertEquals(expected, ProductDeduplication.deduplicateProducts(products));
    }

    @Test
    void testEmptyList() {
        assertEquals(new ArrayList<>(), ProductDeduplication.deduplicateProducts(new ArrayList<>()));
    }

    @Test
    void testNoDuplicates() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100),
            new ProductDeduplication.Product("Banana", 80),
            new ProductDeduplication.Product("Orange", 90)
        );

        List<ProductDeduplication.Product> expected = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100),
            new ProductDeduplication.Product("Orange", 90),
            new ProductDeduplication.Product("Banana", 80)
        );

        assertEquals(expected, ProductDeduplication.deduplicateProducts(products));
    }

    @Test
    void testAllSameProduct() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100),
            new ProductDeduplication.Product("Apple", 120),
            new ProductDeduplication.Product("Apple", 80)
        );

        List<ProductDeduplication.Product> expected = Arrays.asList(
            new ProductDeduplication.Product("Apple", 120)
        );

        assertEquals(expected, ProductDeduplication.deduplicateProducts(products));
    }

    @Test
    void testSingleProduct() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100)
        );

        List<ProductDeduplication.Product> expected = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100)
        );

        assertEquals(expected, ProductDeduplication.deduplicateProducts(products));
    }

    @Test
    void testSamePrices() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Apple", 100),
            new ProductDeduplication.Product("Banana", 100),
            new ProductDeduplication.Product("Orange", 100)
        );

        List<ProductDeduplication.Product> result = ProductDeduplication.deduplicateProducts(products);
        assertEquals(3, result.size());

        for (ProductDeduplication.Product product : result) {
            assertEquals(100, product.price);
        }
    }

    @Test
    void testLargeDataset() {
        List<ProductDeduplication.Product> products = new ArrayList<>();

        for (int i = 0; i < 100; i++) {
            products.add(new ProductDeduplication.Product("Product" + (i % 10), i));
        }

        List<ProductDeduplication.Product> result = ProductDeduplication.deduplicateProducts(products);
        assertEquals(10, result.size());

        for (int i = 0; i < result.size() - 1; i++) {
            assertTrue(result.get(i).price >= result.get(i + 1).price);
        }
    }

    @Test
    void testMaxIntPrices() {
        List<ProductDeduplication.Product> products = Arrays.asList(
            new ProductDeduplication.Product("Expensive", Integer.MAX_VALUE),
            new ProductDeduplication.Product("Cheap", 1),
            new ProductDeduplication.Product("Expensive", Integer.MAX_VALUE - 1)
        );

        List<ProductDeduplication.Product> expected = Arrays.asList(
            new ProductDeduplication.Product("Expensive", Integer.MAX_VALUE),
            new ProductDeduplication.Product("Cheap", 1)
        );

        assertEquals(expected, ProductDeduplication.deduplicateProducts(products));
    }

    @Test
    void testNullInput() {
        assertEquals(new ArrayList<>(), ProductDeduplication.deduplicateProducts(null));
    }
}
