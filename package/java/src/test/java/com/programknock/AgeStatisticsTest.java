package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static org.junit.jupiter.api.Assertions.*;

class AgeStatisticsTest {

    @Test
    void testSample1() {
        List<Integer> ages = Arrays.asList(25, 30, 35, 40, 45, 50);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(50, result.maxAge);
        assertEquals(25, result.minAge);
        assertEquals(37.5, result.avgAge, 0.001);
        assertEquals(3, result.countBelowAvg);
    }

    @Test
    void testSample2() {
        List<Integer> ages = Arrays.asList(18, 22, 22, 24, 29, 35, 40, 50, 60);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(60, result.maxAge);
        assertEquals(18, result.minAge);
        assertEquals(33.33, result.avgAge, 0.001);
        assertEquals(5, result.countBelowAvg);
    }

    @Test
    void testSingleAge() {
        List<Integer> ages = Arrays.asList(30);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(30, result.maxAge);
        assertEquals(30, result.minAge);
        assertEquals(30.0, result.avgAge, 0.001);
        assertEquals(1, result.countBelowAvg);
    }

    @Test
    void testAllSameAge() {
        List<Integer> ages = Arrays.asList(25, 25, 25, 25);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(25, result.maxAge);
        assertEquals(25, result.minAge);
        assertEquals(25.0, result.avgAge, 0.001);
        assertEquals(4, result.countBelowAvg);
    }

    @Test
    void testTwoAges() {
        List<Integer> ages = Arrays.asList(20, 40);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(40, result.maxAge);
        assertEquals(20, result.minAge);
        assertEquals(30.0, result.avgAge, 0.001);
        assertEquals(1, result.countBelowAvg);
    }

    @Test
    void testEdgeCaseYoung() {
        List<Integer> ages = Arrays.asList(0, 1, 2, 3, 4);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(4, result.maxAge);
        assertEquals(0, result.minAge);
        assertEquals(2.0, result.avgAge, 0.001);
        assertEquals(3, result.countBelowAvg);
    }

    @Test
    void testEdgeCaseOld() {
        List<Integer> ages = Arrays.asList(110, 115, 120);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(120, result.maxAge);
        assertEquals(110, result.minAge);
        assertEquals(115.0, result.avgAge, 0.001);
        assertEquals(2, result.countBelowAvg);
    }

    @Test
    void testLargeDataset() {
        List<Integer> ages = IntStream.rangeClosed(20, 70)
            .boxed()
            .collect(Collectors.toList());
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(70, result.maxAge);
        assertEquals(20, result.minAge);
        assertEquals(45.0, result.avgAge, 0.001);
        assertEquals(26, result.countBelowAvg);
    }

    @Test
    void testMixedAges() {
        List<Integer> ages = Arrays.asList(18, 25, 32, 45, 52, 60, 65, 70, 22, 28);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(70, result.maxAge);
        assertEquals(18, result.minAge);
        assertEquals(41.7, result.avgAge, 0.001);
        assertEquals(5, result.countBelowAvg);
    }

    @Test
    void testPrecisionRounding() {
        List<Integer> ages = Arrays.asList(33, 34, 35);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(35, result.maxAge);
        assertEquals(33, result.minAge);
        assertEquals(34.0, result.avgAge, 0.001);
        assertEquals(2, result.countBelowAvg);
    }

    @Test
    void testPrecisionRounding2() {
        List<Integer> ages = Arrays.asList(10, 20, 30, 40, 50, 60, 70);
        AgeStatistics.AgeAnalysisResult result = AgeStatistics.analyzeAgeDistribution(ages);

        assertEquals(70, result.maxAge);
        assertEquals(10, result.minAge);
        assertEquals(40.0, result.avgAge, 0.001);
        assertEquals(4, result.countBelowAvg);
    }

    @Test
    void testEmptyList() {
        List<Integer> ages = Arrays.asList();

        assertThrows(IllegalArgumentException.class, () -> {
            AgeStatistics.analyzeAgeDistribution(ages);
        });
    }
}
