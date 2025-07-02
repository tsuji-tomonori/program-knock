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
