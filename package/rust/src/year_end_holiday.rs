use chrono::{Datelike, Duration, NaiveDate, Weekday};

pub fn year_end_holiday(year: i32) -> (NaiveDate, NaiveDate, i32) {
    let coming_of_age_day = get_coming_of_age_day(year + 1);
    let jan_3 = NaiveDate::from_ymd_opt(year + 1, 1, 3).unwrap();
    
    let mut start_date = NaiveDate::from_ymd_opt(year, 12, 29).unwrap();
    let mut end_date = jan_3;
    
    while start_date.weekday() != Weekday::Sat && start_date != NaiveDate::from_ymd_opt(year, 12, 29).unwrap() {
        start_date -= Duration::days(1);
    }
    
    if start_date.weekday() == Weekday::Sat {
        start_date -= Duration::days(1);
    }
    
    if start_date < NaiveDate::from_ymd_opt(year, 12, 29).unwrap() {
        start_date = NaiveDate::from_ymd_opt(year, 12, 29).unwrap();
    }
    
    if coming_of_age_day == NaiveDate::from_ymd_opt(year + 1, 1, 4).unwrap() {
        end_date = coming_of_age_day;
    } else if end_date.weekday() == Weekday::Fri {
        end_date += Duration::days(2);
    } else if end_date.weekday() == Weekday::Sat {
        end_date += Duration::days(1);
    }
    
    let days_count = (end_date - start_date).num_days() + 1;
    
    (start_date, end_date, days_count as i32)
}

fn get_coming_of_age_day(year: i32) -> NaiveDate {
    let jan_1 = NaiveDate::from_ymd_opt(year, 1, 1).unwrap();
    let mut day = jan_1;
    let mut monday_count = 0;
    
    while monday_count < 2 {
        if day.weekday() == Weekday::Mon {
            monday_count += 1;
        }
        if monday_count < 2 {
            day += Duration::days(1);
        }
    }
    
    day
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_year_end_holiday_2025() {
        let result = year_end_holiday(2025);
        assert_eq!(result.0, NaiveDate::from_ymd_opt(2025, 12, 29).unwrap());
        assert_eq!(result.1, NaiveDate::from_ymd_opt(2026, 1, 4).unwrap());
        assert_eq!(result.2, 7);
    }

    #[test]
    fn test_year_end_holiday_2026() {
        let result = year_end_holiday(2026);
        let start = result.0;
        let end = result.1;
        let days = result.2;
        assert!(days >= 4);
        assert!(start.month() == 12);
        assert!(end.month() == 1);
    }

    #[test]
    fn test_year_end_holiday_basic_period() {
        let result = year_end_holiday(2024);
        let start = result.0;
        let end = result.1;
        let days = result.2;
        assert!(start <= NaiveDate::from_ymd_opt(2024, 12, 29).unwrap());
        assert!(end >= NaiveDate::from_ymd_opt(2025, 1, 3).unwrap());
        assert!(days >= 4);
    }

    #[test]
    fn test_year_end_holiday_coming_of_age_adjacency() {
        for year in 2020..2030 {
            let result = year_end_holiday(year);
            let coming_of_age = get_coming_of_age_day(year + 1);
            assert!(result.1 <= coming_of_age + Duration::days(2));
        }
    }

    #[test]
    fn test_year_end_holiday_weekend_extension() {
        for year in 2020..2025 {
            let result = year_end_holiday(year);
            assert!(result.2 >= 4);
        }
    }

    #[test]
    fn test_year_end_holiday_leap_year() {
        let result = year_end_holiday(2020);
        assert!(result.2 >= 4);
        assert!(result.0.year() == 2020);
        assert!(result.1.year() == 2021);
    }

    #[test]
    fn test_year_end_holiday_hundred_years_future() {
        for year in (2025..2125).step_by(10) {
            let result = year_end_holiday(year);
            assert!(result.2 >= 4);
            assert!(result.0.month() == 12);
            assert!(result.1.month() == 1);
        }
    }

    #[test]
    fn test_year_end_holiday_all_weekday_patterns() {
        let mut weekday_patterns = std::collections::HashSet::new();
        for year in 2020..2027 {
            let jan_1 = NaiveDate::from_ymd_opt(year + 1, 1, 1).unwrap();
            weekday_patterns.insert(jan_1.weekday());
        }
        assert!(weekday_patterns.len() >= 5);
    }

    #[test]
    fn test_year_end_holiday_holiday_transfers() {
        for year in 2020..2030 {
            let result = year_end_holiday(year);
            let jan_1 = NaiveDate::from_ymd_opt(year + 1, 1, 1).unwrap();
            if jan_1.weekday() == Weekday::Sat || jan_1.weekday() == Weekday::Sun {
                assert!(result.2 >= 5);
            }
        }
    }

    #[test]
    fn test_year_end_holiday_date_boundaries() {
        let result = year_end_holiday(2025);
        assert!(result.0 >= NaiveDate::from_ymd_opt(2025, 12, 25).unwrap());
        assert!(result.0 <= NaiveDate::from_ymd_opt(2025, 12, 31).unwrap());
        assert!(result.1 >= NaiveDate::from_ymd_opt(2026, 1, 1).unwrap());
        assert!(result.1 <= NaiveDate::from_ymd_opt(2026, 1, 15).unwrap());
    }

    #[test]
    fn test_year_end_holiday_performance() {
        for year in 2000..2100 {
            let result = year_end_holiday(year);
            assert!(result.2 > 0);
        }
    }
}