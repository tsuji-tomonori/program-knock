use program_knock::suggest_aws_service::*;

#[test]
fn test_suggest_aws_service_lambda() {
    assert_eq!(suggest_aws_service("lamda"), "lambda");
    assert_eq!(suggest_aws_service("lamba"), "lambda");
    assert_eq!(suggest_aws_service("lambada"), "lambda");
}

#[test]
fn test_suggest_aws_service_s3() {
    assert_eq!(suggest_aws_service("s33"), "s3");
    assert_eq!(suggest_aws_service("s"), "s3");
    assert_eq!(suggest_aws_service("s333"), "s3");
}

#[test]
fn test_suggest_aws_service_rds() {
    assert_eq!(suggest_aws_service("rts"), "rds");
    assert_eq!(suggest_aws_service("rd"), "rds");
    assert_eq!(suggest_aws_service("rdss"), "rds");
}

#[test]
fn test_suggest_aws_service_cloudfront() {
    assert_eq!(suggest_aws_service("cloudfrot"), "cloudfront");
    assert_eq!(suggest_aws_service("cloudfrnt"), "cloudfront");
    assert_eq!(suggest_aws_service("cloudfromt"), "cloudfront");
}

#[test]
fn test_suggest_aws_service_exact_match() {
    assert_eq!(suggest_aws_service("lambda"), "lambda");
    assert_eq!(suggest_aws_service("s3"), "s3");
    assert_eq!(suggest_aws_service("ec2"), "ec2");
}

#[test]
fn test_suggest_aws_service_single_character() {
    let result = suggest_aws_service("a");
    assert!(!result.is_empty());
}

#[test]
fn test_suggest_aws_service_maximum_length() {
    let long_input = "verylongservicenamethatdoesntexist";
    let result = suggest_aws_service(long_input);
    assert!(!result.is_empty());
}

#[test]
fn test_suggest_aws_service_all_different() {
    let result = suggest_aws_service("xyz");
    assert!(!result.is_empty());
}

#[test]
fn test_suggest_aws_service_case_sensitivity() {
    assert_eq!(suggest_aws_service("LAMBDA"), "lambda");
    assert_eq!(suggest_aws_service("Lambda"), "lambda");
}

#[test]
fn test_suggest_aws_service_performance() {
    for i in 0..1000 {
        let input = format!("service{}", i);
        let result = suggest_aws_service(&input);
        assert!(!result.is_empty());
    }
}

#[test]
fn test_suggest_aws_service_edge_cases() {
    assert_eq!(suggest_aws_service(""), "s3");
    assert_eq!(suggest_aws_service("1"), "s3");
    assert_eq!(suggest_aws_service("!@#"), "s3");
}
