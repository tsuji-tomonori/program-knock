use program_knock::server_log_analysis::*;
use std::collections::HashMap;

#[test]
fn test_filter_successful_requests_basic() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /api/users".to_string(), 200),
        ("192.168.1.2".to_string(), "POST /api/login".to_string(), 404),
        ("192.168.1.3".to_string(), "GET /api/data".to_string(), 200),
    ];
    let result = filter_successful_requests(&logs);
    let expected = vec![
        ("192.168.1.1".to_string(), "GET /api/users".to_string(), 200),
        ("192.168.1.3".to_string(), "GET /api/data".to_string(), 200),
    ];
    assert_eq!(result, expected);
}

#[test]
fn test_filter_successful_requests_empty_logs() {
    let logs = vec![];
    let result = filter_successful_requests(&logs);
    assert_eq!(result, vec![]);
}

#[test]
fn test_filter_successful_requests_all_200() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /".to_string(), 200),
        ("192.168.1.2".to_string(), "POST /".to_string(), 200),
    ];
    let result = filter_successful_requests(&logs);
    assert_eq!(result, logs);
}

#[test]
fn test_filter_successful_requests_no_200() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /".to_string(), 404),
        ("192.168.1.2".to_string(), "POST /".to_string(), 500),
    ];
    let result = filter_successful_requests(&logs);
    assert_eq!(result, vec![]);
}

#[test]
fn test_count_requests_by_ip_basic() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /".to_string(), 200),
        ("192.168.1.2".to_string(), "POST /".to_string(), 404),
        ("192.168.1.1".to_string(), "GET /api".to_string(), 200),
    ];
    let result = count_requests_by_ip(&logs);
    let mut expected = HashMap::new();
    expected.insert("192.168.1.1".to_string(), 2);
    expected.insert("192.168.1.2".to_string(), 1);
    assert_eq!(result, expected);
}

#[test]
fn test_count_requests_by_ip_empty_logs() {
    let logs = vec![];
    let result = count_requests_by_ip(&logs);
    assert!(result.is_empty());
}

#[test]
fn test_count_requests_by_ip_single_ip() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /".to_string(), 200),
        ("192.168.1.1".to_string(), "POST /".to_string(), 200),
        ("192.168.1.1".to_string(), "DELETE /".to_string(), 404),
    ];
    let result = count_requests_by_ip(&logs);
    let mut expected = HashMap::new();
    expected.insert("192.168.1.1".to_string(), 3);
    assert_eq!(result, expected);
}

#[test]
fn test_combined_operations() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /api/users".to_string(), 200),
        ("192.168.1.2".to_string(), "POST /api/login".to_string(), 404),
        ("192.168.1.1".to_string(), "GET /api/data".to_string(), 200),
        ("192.168.1.3".to_string(), "GET /api/info".to_string(), 500),
    ];

    let successful = filter_successful_requests(&logs);
    assert_eq!(successful.len(), 2);

    let ip_counts = count_requests_by_ip(&logs);
    assert_eq!(ip_counts.get("192.168.1.1"), Some(&2));
    assert_eq!(ip_counts.get("192.168.1.2"), Some(&1));
    assert_eq!(ip_counts.get("192.168.1.3"), Some(&1));
}

#[test]
fn test_various_status_codes() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /".to_string(), 200),
        ("192.168.1.2".to_string(), "GET /".to_string(), 201),
        ("192.168.1.3".to_string(), "GET /".to_string(), 404),
        ("192.168.1.4".to_string(), "GET /".to_string(), 500),
    ];
    let result = filter_successful_requests(&logs);
    assert_eq!(result.len(), 1);
    assert_eq!(result[0].2, 200);
}

#[test]
fn test_special_request_content() {
    let logs = vec![
        ("192.168.1.1".to_string(), "GET /api/special?param=value&other=123".to_string(), 200),
        ("192.168.1.2".to_string(), "POST /api/upload with spaces".to_string(), 200),
    ];
    let result = filter_successful_requests(&logs);
    assert_eq!(result.len(), 2);
}

#[test]
fn test_ipv6_addresses() {
    let logs = vec![
        ("2001:0db8:85a3:0000:0000:8a2e:0370:7334".to_string(), "GET /".to_string(), 200),
        ("::1".to_string(), "GET /".to_string(), 200),
    ];
    let result = count_requests_by_ip(&logs);
    assert_eq!(result.len(), 2);
}

#[test]
fn test_performance_large_logs() {
    let logs: Vec<(String, String, i32)> = (0..10000).map(|i| {
        (format!("192.168.1.{}", i % 255), format!("GET /api/{}", i), if i % 2 == 0 { 200 } else { 404 })
    }).collect();

    let successful = filter_successful_requests(&logs);
    assert_eq!(successful.len(), 5000);

    let ip_counts = count_requests_by_ip(&logs);
    assert!(ip_counts.len() <= 255);
}
