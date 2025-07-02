package com.programknock;

import java.time.*;
import java.time.format.DateTimeFormatter;

public class CalculateNewYearHoliday {

    public static class HolidayResult {
        public final String startDate;
        public final String endDate;
        public final int holidayDays;

        public HolidayResult(String startDate, String endDate, int holidayDays) {
            this.startDate = startDate;
            this.endDate = endDate;
            this.holidayDays = holidayDays;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            HolidayResult that = (HolidayResult) obj;
            return holidayDays == that.holidayDays &&
                   startDate.equals(that.startDate) &&
                   endDate.equals(that.endDate);
        }

        @Override
        public int hashCode() {
            return java.util.Objects.hash(startDate, endDate, holidayDays);
        }

        @Override
        public String toString() {
            return String.format("HolidayResult{startDate='%s', endDate='%s', holidayDays=%d}",
                               startDate, endDate, holidayDays);
        }
    }

    /**
     * Find the nth occurrence of a specific weekday in a given month
     * @param year the year
     * @param month the month (1-12)
     * @param dayOfWeek the day of week (1=Monday, 7=Sunday)
     * @param n the nth occurrence (1st, 2nd, 3rd, etc.)
     * @return the date of the nth weekday
     */
    private static LocalDate nthWeekdayOfMonth(int year, int month, DayOfWeek dayOfWeek, int n) {
        LocalDate date = LocalDate.of(year, month, 1);
        int count = 0;

        while (date.getMonthValue() == month) {
            if (date.getDayOfWeek() == dayOfWeek) {
                count++;
                if (count == n) {
                    return date;
                }
            }
            date = date.plusDays(1);
        }

        throw new IllegalArgumentException(
            String.format("No %d%s %s found in %d/%d", n, getOrdinalSuffix(n), dayOfWeek, year, month));
    }

    private static String getOrdinalSuffix(int n) {
        if (n >= 11 && n <= 13) return "th";
        switch (n % 10) {
            case 1: return "st";
            case 2: return "nd";
            case 3: return "rd";
            default: return "th";
        }
    }

    /**
     * Check if a given date is a holiday (weekend or national holiday)
     */
    private static boolean isHoliday(LocalDate date) {
        // Weekend check (Saturday=6, Sunday=7)
        if (date.getDayOfWeek() == DayOfWeek.SATURDAY || date.getDayOfWeek() == DayOfWeek.SUNDAY) {
            return true;
        }

        int month = date.getMonthValue();
        int day = date.getDayOfMonth();

        // Fixed holidays
        if (month == 1 && day == 1) return true;  // New Year's Day
        if (month == 2 && day == 23) return true; // Emperor's Birthday
        if (month == 5 && (day == 3 || day == 4 || day == 5)) return true; // Golden Week
        if (month == 8 && day == 11) return true; // Mountain Day
        if (month == 11 && (day == 3 || day == 23)) return true; // Culture Day, Labor Thanksgiving Day

        // Variable holidays
        if (month == 1) {
            // Coming of Age Day: 2nd Monday of January
            LocalDate comingOfAge = nthWeekdayOfMonth(date.getYear(), 1, DayOfWeek.MONDAY, 2);
            if (date.equals(comingOfAge)) return true;
        }
        if (month == 7) {
            // Marine Day: 3rd Monday of July
            LocalDate marineDay = nthWeekdayOfMonth(date.getYear(), 7, DayOfWeek.MONDAY, 3);
            if (date.equals(marineDay)) return true;
        }
        if (month == 9) {
            // Respect for the Aged Day: 3rd Monday of September
            LocalDate respectedAgedDay = nthWeekdayOfMonth(date.getYear(), 9, DayOfWeek.MONDAY, 3);
            if (date.equals(respectedAgedDay)) return true;
            // Autumnal Equinox Day: September 22 or 23 (simplified)
            if (day == 22 || day == 23) return true;
        }
        if (month == 10) {
            // Sports Day: 2nd Monday of October
            LocalDate sportsDay = nthWeekdayOfMonth(date.getYear(), 10, DayOfWeek.MONDAY, 2);
            if (date.equals(sportsDay)) return true;
        }

        return false;
    }

    /**
     * Calculate the New Year holiday period for a given year
     * @param year the target year
     * @return HolidayResult containing start date, end date, and number of holiday days
     */
    public static HolidayResult calculateNewYearHoliday(int year) {
        // Base period: December 29 (previous year) to January 3 (target year)
        LocalDate startDate = LocalDate.of(year - 1, 12, 29);
        LocalDate endDate = LocalDate.of(year, 1, 3);

        // Extend start date backwards if previous days are holidays
        while (true) {
            LocalDate prevDay = startDate.minusDays(1);
            if (isHoliday(prevDay)) {
                startDate = prevDay;
            } else {
                break;
            }
        }

        // Extend end date forwards if next days are holidays
        while (true) {
            LocalDate nextDay = endDate.plusDays(1);
            if (isHoliday(nextDay)) {
                endDate = nextDay;
            } else {
                break;
            }
        }

        int holidayDays = (int) (endDate.toEpochDay() - startDate.toEpochDay()) + 1;

        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd");
        return new HolidayResult(
            startDate.format(formatter),
            endDate.format(formatter),
            holidayDays
        );
    }
}
