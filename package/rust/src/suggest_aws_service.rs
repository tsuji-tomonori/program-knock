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
