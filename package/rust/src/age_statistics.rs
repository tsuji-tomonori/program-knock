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

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_age_statistics_sample_1() {
        let ages = vec![25, 30, 35, 40, 45, 50];
        let result = age_statistics(&ages);
        assert_eq!(result, (50, 25, 37.5, 3));
    }

    #[test]
    fn test_age_statistics_sample_2() {
        let ages = vec![18, 22, 22, 24, 29, 35, 40, 50, 60];
        let result = age_statistics(&ages);
        assert_eq!(result, (60, 18, 33.33, 5));
    }

    #[test]
    fn test_age_statistics_single_person() {
        let ages = vec![30];
        let result = age_statistics(&ages);
        assert_eq!(result, (30, 30, 30.0, 1));
    }

    #[test]
    fn test_age_statistics_duplicate_ages() {
        let ages = vec![25, 25, 30, 30, 35];
        let result = age_statistics(&ages);
        assert_eq!(result, (35, 25, 29.0, 2));
    }

    #[test]
    fn test_age_statistics_all_same_age() {
        let ages = vec![30, 30, 30, 30];
        let result = age_statistics(&ages);
        assert_eq!(result, (30, 30, 30.0, 4));
    }

    #[test]
    fn test_age_statistics_boundary_ages() {
        let ages = vec![0, 120, 65];
        let result = age_statistics(&ages);
        assert_eq!(result, (120, 0, 61.67, 1));
    }

    #[test]
    fn test_age_statistics_extreme_range() {
        let ages = vec![18, 65, 25, 45, 30];
        let result = age_statistics(&ages);
        assert_eq!(result, (65, 18, 36.6, 3));
    }

    #[test]
    fn test_age_statistics_integer_average() {
        let ages = vec![20, 30, 40];
        let result = age_statistics(&ages);
        assert_eq!(result, (40, 20, 30.0, 2));
    }

    #[test]
    fn test_age_statistics_large_dataset() {
        let ages: Vec<i32> = (20..=80).collect();
        let result = age_statistics(&ages);
        assert_eq!(result, (80, 20, 50.0, 31));
    }

    #[test]
    fn test_age_statistics_two_people() {
        let ages = vec![25, 35];
        let result = age_statistics(&ages);
        assert_eq!(result, (35, 25, 30.0, 1));
    }

    #[test]
    fn test_age_statistics_uneven_distribution() {
        let ages = vec![20, 21, 22, 50, 60];
        let result = age_statistics(&ages);
        assert_eq!(result, (60, 20, 34.6, 3));
    }
}