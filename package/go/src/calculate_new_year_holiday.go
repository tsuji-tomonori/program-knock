package src

import (
	"fmt"
	"time"
)

// NewYearHolidayResult represents the result of new year holiday calculation
type NewYearHolidayResult struct {
	StartDate string // Holiday start date in "YYYY-MM-DD" format
	EndDate   string // Holiday end date in "YYYY-MM-DD" format
	Days      int    // Number of holiday days
}

// nthWeekdayOfMonth returns the nth occurrence of a specific weekday in a given month
// weekday: Monday=1, Tuesday=2, ..., Sunday=0
func nthWeekdayOfMonth(year, month int, weekday time.Weekday, n int) (time.Time, error) {
	date := time.Date(year, time.Month(month), 1, 0, 0, 0, 0, time.UTC)
	count := 0

	for date.Month() == time.Month(month) {
		if date.Weekday() == weekday {
			count++
			if count == n {
				return date, nil
			}
		}
		date = date.AddDate(0, 0, 1)
	}

	return time.Time{}, fmt.Errorf("%d年%d月に%d番目の曜日(%v)は存在しません", year, month, n, weekday)
}

// isHoliday determines if the given date is a weekend or a Japanese national holiday
func isHoliday(date time.Time) bool {
	// Weekend check (Saturday: 6, Sunday: 0)
	if date.Weekday() == time.Saturday || date.Weekday() == time.Sunday {
		return true
	}

	month := int(date.Month())
	day := date.Day()
	year := date.Year()

	// Fixed holidays
	if month == 1 && day == 1 { // New Year's Day
		return true
	}
	if month == 2 && day == 23 { // Emperor's Birthday
		return true
	}
	if month == 5 && (day == 3 || day == 4 || day == 5) { // Golden Week
		return true
	}
	if month == 8 && day == 11 { // Mountain Day
		return true
	}
	if month == 11 && (day == 3 || day == 23) { // Culture Day, Labor Thanksgiving Day
		return true
	}

	// Variable holidays
	if month == 1 {
		// Coming of Age Day: 2nd Monday of January
		if secondMonday, err := nthWeekdayOfMonth(year, 1, time.Monday, 2); err == nil {
			if date.Equal(secondMonday) {
				return true
			}
		}
	}
	if month == 7 {
		// Marine Day: 3rd Monday of July
		if thirdMonday, err := nthWeekdayOfMonth(year, 7, time.Monday, 3); err == nil {
			if date.Equal(thirdMonday) {
				return true
			}
		}
	}
	if month == 9 {
		// Respect for the Aged Day: 3rd Monday of September
		if thirdMonday, err := nthWeekdayOfMonth(year, 9, time.Monday, 3); err == nil {
			if date.Equal(thirdMonday) {
				return true
			}
		}
		// Autumnal Equinox Day: simplified as September 22 or 23
		if day == 22 || day == 23 {
			return true
		}
	}
	if month == 10 {
		// Sports Day: 2nd Monday of October
		if secondMonday, err := nthWeekdayOfMonth(year, 10, time.Monday, 2); err == nil {
			if date.Equal(secondMonday) {
				return true
			}
		}
	}

	return false
}

// CalculateNewYearHoliday calculates the new year holiday period for a given year
func CalculateNewYearHoliday(year int) NewYearHolidayResult {
	// Basic holiday period: December 29 (of previous year) to January 3 (of given year)
	startDate := time.Date(year-1, 12, 29, 0, 0, 0, 0, time.UTC)
	endDate := time.Date(year, 1, 3, 0, 0, 0, 0, time.UTC)

	// Extend start date backwards for consecutive weekends/holidays
	for {
		prevDay := startDate.AddDate(0, 0, -1)
		if isHoliday(prevDay) {
			startDate = prevDay
		} else {
			break
		}
	}

	// Extend end date forwards for consecutive weekends/holidays
	for {
		nextDay := endDate.AddDate(0, 0, 1)
		if isHoliday(nextDay) {
			endDate = nextDay
		} else {
			break
		}
	}

	// Calculate number of days
	days := int(endDate.Sub(startDate).Hours()/24) + 1

	return NewYearHolidayResult{
		StartDate: startDate.Format("2006-01-02"),
		EndDate:   endDate.Format("2006-01-02"),
		Days:      days,
	}
}
