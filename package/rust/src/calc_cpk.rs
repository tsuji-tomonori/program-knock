pub fn calc_cpk(usl: f64, lsl: f64, data: &[f64]) -> f64 {
    let n = data.len() as f64;
    let mean = data.iter().sum::<f64>() / n;
    
    let variance = data.iter()
        .map(|x| (x - mean).powi(2))
        .sum::<f64>() / (n - 1.0);
    let std_dev = variance.sqrt();
    
    let m = (usl + lsl) / 2.0;
    let r = usl - lsl;
    let k = (mean - m).abs() / (r / 2.0);
    
    let cp = (usl - lsl) / (6.0 * std_dev);
    let cpk = (1.0 - k) * cp;
    
    (cpk * 1000.0).round() / 1000.0
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_calc_cpk_sample_1() {
        let usl = 10.0;
        let lsl = 2.0;
        let data = vec![4.5, 5.0, 4.8, 5.2, 5.5];
        let result = calc_cpk(usl, lsl, &data);
        assert_eq!(result, 2.626);
    }

    #[test]
    fn test_calc_cpk_perfect_center() {
        let usl = 10.0;
        let lsl = 0.0;
        let data = vec![5.0, 5.0, 5.0, 5.0, 5.0];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_single_value() {
        let usl = 10.0;
        let lsl = 0.0;
        let data = vec![5.0, 5.1];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_wide_range() {
        let usl = 100.0;
        let lsl = 0.0;
        let data = vec![45.0, 50.0, 48.0, 52.0, 55.0];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_narrow_range() {
        let usl = 6.0;
        let lsl = 4.0;
        let data = vec![4.5, 5.0, 4.8, 5.2, 5.5];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_large_dataset() {
        let usl = 10.0;
        let lsl = 0.0;
        let data: Vec<f64> = (0..1000).map(|i| 5.0 + (i as f64) * 0.001).collect();
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_negative_values() {
        let usl = 0.0;
        let lsl = -10.0;
        let data = vec![-4.5, -5.0, -4.8, -5.2, -5.5];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_asymmetric_data() {
        let usl = 15.0;
        let lsl = 5.0;
        let data = vec![6.0, 7.0, 8.0, 12.0, 13.0];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_high_variance() {
        let usl = 20.0;
        let lsl = 0.0;
        let data = vec![1.0, 5.0, 10.0, 15.0, 19.0];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 0.0);
    }

    #[test]
    fn test_calc_cpk_low_variance() {
        let usl = 10.0;
        let lsl = 0.0;
        let data = vec![5.0, 5.01, 5.02, 4.99, 4.98];
        let result = calc_cpk(usl, lsl, &data);
        assert!(result > 1.0);
    }
}