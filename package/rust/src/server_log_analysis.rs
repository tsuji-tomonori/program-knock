use std::collections::HashMap;

pub fn filter_successful_requests(logs: &[(String, String, i32)]) -> Vec<(String, String, i32)> {
    logs.iter()
        .filter(|(_, _, status_code)| *status_code == 200)
        .cloned()
        .collect()
}

pub fn count_requests_by_ip(logs: &[(String, String, i32)]) -> HashMap<String, i32> {
    let mut ip_counts = HashMap::new();

    for (ip, _, _) in logs {
        *ip_counts.entry(ip.clone()).or_insert(0) += 1;
    }

    ip_counts
}
