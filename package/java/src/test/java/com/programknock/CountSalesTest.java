package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.*;

import static org.junit.jupiter.api.Assertions.*;

class CountSalesTest {

    @Test
    void testSample1() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2),
            new CountSales.Sale("Tokyo", "Cash", "Banana", 1),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 4),
            new CountSales.Sale("Osaka", "Credit", "Orange", 3),
            new CountSales.Sale("Tokyo", "Cash", "Apple", 1)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);

        assertEquals(6, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
        assertEquals(1, result.get(new CountSales.SaleKey("Tokyo", "Cash", "Banana")));
        assertEquals(3, result.get(new CountSales.SaleKey("Osaka", "Credit", "Orange")));
        assertEquals(1, result.get(new CountSales.SaleKey("Tokyo", "Cash", "Apple")));
    }

    @Test
    void testEmptyList() {
        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(new ArrayList<>());
        assertTrue(result.isEmpty());
    }

    @Test
    void testSingleSale() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 5)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(1, result.size());
        assertEquals(5, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
    }

    @Test
    void testAllSameKey() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 3),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 1)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(1, result.size());
        assertEquals(6, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
    }

    @Test
    void testDifferentStores() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2),
            new CountSales.Sale("Osaka", "Credit", "Apple", 3),
            new CountSales.Sale("Kyoto", "Credit", "Apple", 1)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(3, result.size());
        assertEquals(2, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
        assertEquals(3, result.get(new CountSales.SaleKey("Osaka", "Credit", "Apple")));
        assertEquals(1, result.get(new CountSales.SaleKey("Kyoto", "Credit", "Apple")));
    }

    @Test
    void testDifferentPaymentMethods() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2),
            new CountSales.Sale("Tokyo", "Cash", "Apple", 3),
            new CountSales.Sale("Tokyo", "Debit", "Apple", 1)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(3, result.size());
        assertEquals(2, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
        assertEquals(3, result.get(new CountSales.SaleKey("Tokyo", "Cash", "Apple")));
        assertEquals(1, result.get(new CountSales.SaleKey("Tokyo", "Debit", "Apple")));
    }

    @Test
    void testDifferentProducts() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2),
            new CountSales.Sale("Tokyo", "Credit", "Banana", 3),
            new CountSales.Sale("Tokyo", "Credit", "Orange", 1)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(3, result.size());
        assertEquals(2, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
        assertEquals(3, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Banana")));
        assertEquals(1, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Orange")));
    }

    @Test
    void testLargeQuantities() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 1000),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 2000),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 500)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(1, result.size());
        assertEquals(3500, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
    }

    @Test
    void testMixedScenario() {
        List<CountSales.Sale> sales = Arrays.asList(
            new CountSales.Sale("Tokyo", "Credit", "Apple", 10),
            new CountSales.Sale("Osaka", "Cash", "Banana", 5),
            new CountSales.Sale("Tokyo", "Credit", "Apple", 15),
            new CountSales.Sale("Kyoto", "Debit", "Orange", 8),
            new CountSales.Sale("Osaka", "Cash", "Banana", 12),
            new CountSales.Sale("Tokyo", "Cash", "Apple", 3)
        );

        Map<CountSales.SaleKey, Integer> result = CountSales.countSales(sales);
        assertEquals(4, result.size());
        assertEquals(25, result.get(new CountSales.SaleKey("Tokyo", "Credit", "Apple")));
        assertEquals(17, result.get(new CountSales.SaleKey("Osaka", "Cash", "Banana")));
        assertEquals(8, result.get(new CountSales.SaleKey("Kyoto", "Debit", "Orange")));
        assertEquals(3, result.get(new CountSales.SaleKey("Tokyo", "Cash", "Apple")));
    }
}
