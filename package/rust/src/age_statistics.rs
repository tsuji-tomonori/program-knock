pub fn age_statistics(ages: &[i32]) -> (i32, i32, f64, i32) {
    if ages.is_empty() {
        return (0, 0, 0.0, 0);
    }

    let max_age = *ages.iter().max().unwrap();
    let min_age = *ages.iter().min().unwrap();
    let avg_age_raw = ages.iter().sum::<i32>() as f64 / ages.len() as f64;
    let avg_age = (avg_age_raw * 100.0).round() / 100.0; // Round to 2 decimal places
    let count_below_avg = ages.iter().filter(|&&age| (age as f64) <= avg_age_raw).count() as i32;

    (max_age, min_age, avg_age, count_below_avg)
}
