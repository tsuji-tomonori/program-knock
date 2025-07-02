pub fn suggest_aws_service(wrong_service: &str) -> String {
    let aws_services = vec![
        "s3", "ec2", "lambda", "rds", "dynamodb", "cloudfront", "route53",
        "iam", "vpc", "elb", "autoscaling", "cloudwatch", "sns", "sqs",
        "elasticache", "redshift", "emr", "kinesis", "firehose", "glue",
        "athena", "quicksight", "sagemaker", "rekognition", "comprehend",
        "translate", "polly", "transcribe", "lex", "connect", "workspaces",
        "appstream", "directory", "cognito", "organizations", "config",
        "cloudtrail", "trusted-advisor", "support", "marketplace"
    ];
    
    let wrong_service_lower = wrong_service.to_lowercase();
    let mut best_match = aws_services[0];
    let mut min_distance = levenshtein_distance(&wrong_service_lower, aws_services[0]);
    
    for &service in &aws_services[1..] {
        let distance = levenshtein_distance(&wrong_service_lower, service);
        if distance < min_distance {
            min_distance = distance;
            best_match = service;
        }
    }
    
    best_match.to_string()
}

fn levenshtein_distance(s1: &str, s2: &str) -> usize {
    let len1 = s1.len();
    let len2 = s2.len();
    
    if len1 == 0 {
        return len2;
    }
    if len2 == 0 {
        return len1;
    }
    
    let mut matrix = vec![vec![0; len2 + 1]; len1 + 1];
    
    for (i, row) in matrix.iter_mut().enumerate().take(len1 + 1) {
        row[0] = i;
    }
    for j in 0..=len2 {
        matrix[0][j] = j;
    }
    
    let s1_chars: Vec<char> = s1.chars().collect();
    let s2_chars: Vec<char> = s2.chars().collect();
    
    for i in 1..=len1 {
        for j in 1..=len2 {
            let cost = if s1_chars[i - 1] == s2_chars[j - 1] { 0 } else { 1 };
            
            matrix[i][j] = (matrix[i - 1][j] + 1)
                .min(matrix[i][j - 1] + 1)
                .min(matrix[i - 1][j - 1] + cost);
        }
    }
    
    matrix[len1][len2]
}

#[cfg(test)]
mod tests {
    use super::*;

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

    #[test]
    fn test_levenshtein_distance_basic() {
        assert_eq!(levenshtein_distance("cat", "bat"), 1);
        assert_eq!(levenshtein_distance("kitten", "sitting"), 3);
        assert_eq!(levenshtein_distance("", "abc"), 3);
        assert_eq!(levenshtein_distance("abc", ""), 3);
        assert_eq!(levenshtein_distance("abc", "abc"), 0);
    }
}