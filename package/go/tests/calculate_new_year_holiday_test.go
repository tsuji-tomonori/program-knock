package tests

import (
	"fmt"
	"regexp"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCalculateNewYearHoliday(t *testing.T) {
	t.Run("Sample1_2025", func(t *testing.T) {
		// Sample test case 1 (year=2025):
		// Basic period 12/29~1/3 plus previous day 2024-12-28 (Sat) and next days 2025-01-04,05 (Sat・Sun)
		// → Holiday period: 2024-12-28 ~ 2025-01-05, 9 days
		result := src.CalculateNewYearHoliday(2025)
		expected := src.NewYearHolidayResult{
			StartDate: "2024-12-28",
			EndDate:   "2025-01-05",
			Days:      9,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Sample2_2026", func(t *testing.T) {
		// Sample test case 2 (year=2026):
		// Basic period 12/29~1/3 plus previous days 2025-12-27,28 (Sat・Sun) and next day 2026-01-04 (Sun)
		// → Holiday period: 2025-12-27 ~ 2026-01-04, 9 days
		result := src.CalculateNewYearHoliday(2026)
		expected := src.NewYearHolidayResult{
			StartDate: "2025-12-27",
			EndDate:   "2026-01-04",
			Days:      9,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2023", func(t *testing.T) {
		// Test case: 2023
		// Basic period only (2022-12-29 ~ 2023-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2023)
		expected := src.NewYearHolidayResult{
			StartDate: "2022-12-29",
			EndDate:   "2023-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2024", func(t *testing.T) {
		// Test case: 2024
		// Basic period only (2023-12-29 ~ 2024-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2024)
		expected := src.NewYearHolidayResult{
			StartDate: "2023-12-29",
			EndDate:   "2024-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2027", func(t *testing.T) {
		// Test case: 2027
		// Basic period only (2026-12-29 ~ 2027-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2027)
		expected := src.NewYearHolidayResult{
			StartDate: "2026-12-29",
			EndDate:   "2027-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2021", func(t *testing.T) {
		// Test case: 2021
		// Basic period only (2020-12-29 ~ 2021-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2021)
		expected := src.NewYearHolidayResult{
			StartDate: "2020-12-29",
			EndDate:   "2021-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2020", func(t *testing.T) {
		// Test case: 2020
		// Basic period 2019-12-29 ~ 2020-01-03 plus previous day 2019-12-28 (Sat) and next days 2020-01-04,05 (Sat・Sun)
		// → Holiday period: 2019-12-28 ~ 2020-01-05, 9 days
		result := src.CalculateNewYearHoliday(2020)
		expected := src.NewYearHolidayResult{
			StartDate: "2019-12-28",
			EndDate:   "2020-01-05",
			Days:      9,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2019", func(t *testing.T) {
		// Test case: 2019
		// Basic period only (2018-12-29 ~ 2019-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2019)
		expected := src.NewYearHolidayResult{
			StartDate: "2018-12-29",
			EndDate:   "2019-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2000", func(t *testing.T) {
		// Test case: 2000
		// Basic period only (1999-12-29 ~ 2000-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2000)
		expected := src.NewYearHolidayResult{
			StartDate: "1999-12-29",
			EndDate:   "2000-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("Year2100", func(t *testing.T) {
		// Boundary test: 2100
		// Basic period only (2099-12-29 ~ 2100-01-03), no extension → 6 days
		result := src.CalculateNewYearHoliday(2100)
		expected := src.NewYearHolidayResult{
			StartDate: "2099-12-29",
			EndDate:   "2100-01-03",
			Days:      6,
		}
		assert.Equal(t, expected, result)
	})

	t.Run("OutputFormat", func(t *testing.T) {
		// Format test:
		// Verify that start date and end date are in "YYYY-MM-DD" format strings
		// and the number of days is an integer
		result := src.CalculateNewYearHoliday(2025)
		datePattern := regexp.MustCompile(`^\d{4}-\d{2}-\d{2}$`)
		assert.True(t, datePattern.MatchString(result.StartDate))
		assert.True(t, datePattern.MatchString(result.EndDate))
		assert.IsType(t, 0, result.Days)
	})

	t.Run("MultipleYearConsistency", func(t *testing.T) {
		// Test consistency across multiple years
		years := []int{2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030}
		for _, year := range years {
			result := src.CalculateNewYearHoliday(year)
			// All results should have at least 6 days (basic period)
			assert.GreaterOrEqual(t, result.Days, 6)
			// All results should have reasonable upper bound (no more than 15 days)
			assert.LessOrEqual(t, result.Days, 15)
			// Start date should be in December of previous year
			assert.Contains(t, result.StartDate, fmt.Sprintf("%d-12", year-1))
			// End date should be in January of given year
			assert.Contains(t, result.EndDate, fmt.Sprintf("%d-01", year))
		}
	})

	t.Run("EdgeCaseVeryOldYear", func(t *testing.T) {
		// Test with very old year
		result := src.CalculateNewYearHoliday(1900)
		assert.GreaterOrEqual(t, result.Days, 6)
		assert.LessOrEqual(t, result.Days, 15)
	})

	t.Run("EdgeCaseVeryFutureYear", func(t *testing.T) {
		// Test with future year
		result := src.CalculateNewYearHoliday(3000)
		assert.GreaterOrEqual(t, result.Days, 6)
		assert.LessOrEqual(t, result.Days, 15)
	})
}
