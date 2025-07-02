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
