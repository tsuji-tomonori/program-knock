package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.Arrays;
import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

class CpkCalculatorTest {

    @Test
    void testSample1() {
        double usl = 10.0;
        double lsl = 2.0;
        List<Double> data = Arrays.asList(4.5, 5.0, 4.8, 5.2, 5.5);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertEquals(2.626, result, 0.001);
    }

    @Test
    void testPerfectCenter() {
        double usl = 10.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(5.0, 5.0, 5.0, 5.0, 5.0);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(Double.isInfinite(result) || result > 1000);
    }

    @Test
    void testSingleValue() {
        double usl = 10.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(5.0);

        assertThrows(ArithmeticException.class, () -> {
            CpkCalculator.calcCpk(usl, lsl, data);
        });
    }

    @Test
    void testLargeSpread() {
        double usl = 10.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(0.0, 2.0, 4.0, 6.0, 8.0, 10.0);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }

    @Test
    void testOffCenter() {
        double usl = 10.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(7.0, 7.5, 8.0, 8.5, 9.0);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }

    @Test
    void testNegativeValues() {
        double usl = 0.0;
        double lsl = -10.0;
        List<Double> data = Arrays.asList(-5.0, -4.5, -5.5, -4.8, -5.2);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }

    @Test
    void testLargeDataset() {
        double usl = 100.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(
            45.0, 50.0, 48.0, 52.0, 55.0, 47.0, 49.0, 51.0, 53.0, 46.0,
            54.0, 48.5, 51.5, 49.5, 52.5, 47.5, 50.5, 53.5, 46.5, 54.5
        );

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }

    @Test
    void testSmallSpread() {
        double usl = 10.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(5.0, 5.01, 4.99, 5.02, 4.98);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 10);
    }

    @Test
    void testEdgeCases() {
        double usl = 1.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }

    @Test
    void testHighVariability() {
        double usl = 20.0;
        double lsl = 0.0;
        List<Double> data = Arrays.asList(1.0, 5.0, 10.0, 15.0, 19.0, 2.0, 8.0, 12.0, 16.0, 18.0);

        double result = CpkCalculator.calcCpk(usl, lsl, data);
        assertTrue(result > 0);
    }
}
