package com.programknock;

import org.junit.jupiter.api.Test;
import java.util.regex.Pattern;

import static org.junit.jupiter.api.Assertions.*;

class CalculateNewYearHolidayTest {

    @Test
    void testSample1() {
        // Sample test case 1 (year=2025):
        // Basic period 12/29～1/3 plus previous day 2024-12-28 (Sat) and next days 2025-01-04,05 (Sat-Sun)
        // → Holiday period: 2024-12-28 ～ 2025-01-05, Holiday days: 9
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2025);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2024-12-28", "2025-01-05", 9);
        assertEquals(expected, result);
    }

    @Test
    void testSample2() {
        // Sample test case 2 (year=2026):
        // Basic period 12/29～1/3 plus previous days 2025-12-27,28 (Sat-Sun) and next day 2026-01-04 (Sun)
        // → Holiday period: 2025-12-27 ～ 2026-01-04, Holiday days: 9
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2026);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2025-12-27", "2026-01-04", 9);
        assertEquals(expected, result);
    }

    @Test
    void testYear2023() {
        // Test case: 2023
        // Basic period only (2022-12-29 ～ 2023-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2023);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2022-12-29", "2023-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2024() {
        // Test case: 2024
        // Basic period only (2023-12-29 ～ 2024-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2024);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2023-12-29", "2024-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2027() {
        // Test case: 2027
        // Basic period only (2026-12-29 ～ 2027-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2027);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2026-12-29", "2027-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2021() {
        // Test case: 2021
        // Basic period only (2020-12-29 ～ 2021-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2021);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2020-12-29", "2021-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2020() {
        // Test case: 2020
        // Basic period 2019-12-29 ～ 2020-01-03 plus previous day 2019-12-28 (Sat) and next days 2020-01-04,05 (Sat-Sun)
        // → Holiday period: 2019-12-28 ～ 2020-01-05, Holiday days: 9
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2020);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2019-12-28", "2020-01-05", 9);
        assertEquals(expected, result);
    }

    @Test
    void testYear2019() {
        // Test case: 2019
        // Basic period only (2018-12-29 ～ 2019-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2019);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2018-12-29", "2019-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2000() {
        // Test case: 2000
        // Basic period only (1999-12-29 ～ 2000-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2000);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("1999-12-29", "2000-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testYear2100() {
        // Boundary value test: 2100
        // Basic period only (2099-12-29 ～ 2100-01-03), no extension → Holiday days: 6
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2100);
        CalculateNewYearHoliday.HolidayResult expected = new CalculateNewYearHoliday.HolidayResult("2099-12-29", "2100-01-03", 6);
        assertEquals(expected, result);
    }

    @Test
    void testOutputFormat() {
        // Format test:
        // Verify that the output start date and end date are strings in "YYYY-MM-DD" format,
        // and the holiday days count is an integer
        CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(2025);
        String datePattern = "^\\d{4}-\\d{2}-\\d{2}$";
        assertTrue(Pattern.matches(datePattern, result.startDate));
        assertTrue(Pattern.matches(datePattern, result.endDate));
        assertTrue(result.holidayDays > 0);
    }

    @Test
    void testMultipleYearsConsistency() {
        // Test multiple years to ensure consistency
        for (int year = 2020; year <= 2030; year++) {
            CalculateNewYearHoliday.HolidayResult result = CalculateNewYearHoliday.calculateNewYearHoliday(year);

            // Holiday period should be at least 6 days (basic period)
            assertTrue(result.holidayDays >= 6, "Holiday days should be at least 6 for year " + year);

            // Start date should be in December of previous year
            assertTrue(result.startDate.startsWith(String.valueOf(year - 1) + "-12"),
                      "Start date should be in December of previous year for " + year);

            // End date should be in January of target year (at least)
            assertTrue(result.endDate.startsWith(String.valueOf(year) + "-01"),
                      "End date should be in January of target year for " + year);
        }
    }

    @Test
    void testHolidayResultEquality() {
        CalculateNewYearHoliday.HolidayResult result1 = new CalculateNewYearHoliday.HolidayResult("2024-12-28", "2025-01-05", 9);
        CalculateNewYearHoliday.HolidayResult result2 = new CalculateNewYearHoliday.HolidayResult("2024-12-28", "2025-01-05", 9);
        CalculateNewYearHoliday.HolidayResult result3 = new CalculateNewYearHoliday.HolidayResult("2024-12-29", "2025-01-05", 8);

        assertEquals(result1, result2);
        assertNotEquals(result1, result3);
        assertEquals(result1.hashCode(), result2.hashCode());
    }

    @Test
    void testEarlyYears() {
        // Test earlier years
        CalculateNewYearHoliday.HolidayResult result1990 = CalculateNewYearHoliday.calculateNewYearHoliday(1990);
        assertTrue(result1990.holidayDays >= 6);
        assertTrue(result1990.startDate.startsWith("1989-12"));
        assertTrue(result1990.endDate.startsWith("1990-01"));
    }

    @Test
    void testFutureYears() {
        // Test future years
        CalculateNewYearHoliday.HolidayResult result2050 = CalculateNewYearHoliday.calculateNewYearHoliday(2050);
        assertTrue(result2050.holidayDays >= 6);
        assertTrue(result2050.startDate.startsWith("2049-12"));
        assertTrue(result2050.endDate.startsWith("2050-01"));
    }
}
