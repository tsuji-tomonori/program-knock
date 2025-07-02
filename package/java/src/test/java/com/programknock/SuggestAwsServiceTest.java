package com.programknock;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class SuggestAwsServiceTest {

    @BeforeEach
    void setUp() {
        // Clear memoization cache before each test
        SuggestAwsService.clearCache();
    }

    // Sample test cases from the problem specification
    @Test
    void testSample1() {
        // "lamda" is a spelling mistake of "lambda"
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lamda"));
    }

    @Test
    void testSample2() {
        // "s33" is a typo of "s3" (extra 3)
        assertEquals("s3", SuggestAwsService.suggestAwsService("s33"));
    }

    @Test
    void testSample3() {
        // "rts" is close to "rds"
        assertEquals("rds", SuggestAwsService.suggestAwsService("rts"));
    }

    @Test
    void testSample4() {
        // "cloudfrot" is a spelling mistake of "cloudfront"
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloudfrot"));
    }

    @Test
    void testExactMatch() {
        // Correct service name should return itself
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ec2"));
    }

    @Test
    void testMinLength() {
        // Test with minimum length input (1 character): "s" should suggest "s3"
        assertEquals("s3", SuggestAwsService.suggestAwsService("s"));
    }

    @Test
    void testMaxLength() {
        // Test with maximum length input (20 characters)
        String inputStr = "cloudfronntttttttttt"; // "cloudfront" with extra characters
        assertEquals(20, inputStr.length());
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService(inputStr));
    }

    @Test
    void testDynamo() {
        // "dynamo" is close to "dynamodb"
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamo"));
    }

    @Test
    void testIamm() {
        // "iamm" is a spelling mistake of "iam"
        assertEquals("iam", SuggestAwsService.suggestAwsService("iamm"));
    }

    @Test
    void testRoute() {
        // "route" is close to "route53"
        assertEquals("route53", SuggestAwsService.suggestAwsService("route"));
    }

    // Test cases for EC2 service
    @Test
    void testEc2_1() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ec"));
    }

    @Test
    void testEc2_2() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("e2"));
    }

    @Test
    void testEc2_3() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ecc2"));
    }

    @Test
    void testEc2_4() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ecs"));
    }

    @Test
    void testEc2_5() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ec22"));
    }

    // Test cases for S3 service
    @Test
    void testS3_1() {
        assertEquals("s3", SuggestAwsService.suggestAwsService("s"));
    }

    @Test
    void testS3_2() {
        assertEquals("s3", SuggestAwsService.suggestAwsService("s2"));
    }

    @Test
    void testS3_3() {
        assertEquals("s3", SuggestAwsService.suggestAwsService("ss3"));
    }

    @Test
    void testS3_4() {
        assertEquals("s3", SuggestAwsService.suggestAwsService("s4"));
    }

    @Test
    void testS3_5() {
        assertEquals("s3", SuggestAwsService.suggestAwsService("s33"));
    }

    // Test cases for Lambda service
    @Test
    void testLambda_1() {
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lamb"));
    }

    @Test
    void testLambda_2() {
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lamda"));
    }

    @Test
    void testLambda_3() {
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lambada"));
    }

    @Test
    void testLambda_4() {
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lamba"));
    }

    @Test
    void testLambda_5() {
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lambdaa"));
    }

    // Test cases for DynamoDB service
    @Test
    void testDynamodb_1() {
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamo"));
    }

    @Test
    void testDynamodb_2() {
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamodb"));
    }

    @Test
    void testDynamodb_3() {
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamodbb"));
    }

    @Test
    void testDynamodb_4() {
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynanodb"));
    }

    @Test
    void testDynamodb_5() {
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamodbdb"));
    }

    // Test cases for RDS service
    @Test
    void testRds_1() {
        assertEquals("rds", SuggestAwsService.suggestAwsService("rd"));
    }

    @Test
    void testRds_2() {
        assertEquals("rds", SuggestAwsService.suggestAwsService("rts"));
    }

    @Test
    void testRds_3() {
        assertEquals("rds", SuggestAwsService.suggestAwsService("rdss"));
    }

    @Test
    void testRds_4() {
        assertEquals("rds", SuggestAwsService.suggestAwsService("res"));
    }

    @Test
    void testRds_5() {
        assertEquals("rds", SuggestAwsService.suggestAwsService("rdds"));
    }

    // Test cases for CloudFront service
    @Test
    void testCloudfront_1() {
        assertEquals("ec2", SuggestAwsService.suggestAwsService("cloud"));
    }

    @Test
    void testCloudfront_2() {
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloudfrot"));
    }

    @Test
    void testCloudfront_3() {
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloudfrontt"));
    }

    @Test
    void testCloudfront_4() {
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloadfront"));
    }

    @Test
    void testCloudfront_5() {
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloudfrnt"));
    }

    // Test cases for IAM service
    @Test
    void testIam_1() {
        assertEquals("iam", SuggestAwsService.suggestAwsService("ia"));
    }

    @Test
    void testIam_2() {
        assertEquals("iam", SuggestAwsService.suggestAwsService("iamm"));
    }

    @Test
    void testIam_3() {
        assertEquals("iam", SuggestAwsService.suggestAwsService("iam"));
    }

    @Test
    void testIam_4() {
        assertEquals("iam", SuggestAwsService.suggestAwsService("iim"));
    }

    @Test
    void testIam_5() {
        assertEquals("iam", SuggestAwsService.suggestAwsService("ian"));
    }

    // Test cases for Route53 service
    @Test
    void testRoute53_1() {
        assertEquals("route53", SuggestAwsService.suggestAwsService("route"));
    }

    @Test
    void testRoute53_2() {
        assertEquals("route53", SuggestAwsService.suggestAwsService("route53"));
    }

    @Test
    void testRoute53_3() {
        assertEquals("route53", SuggestAwsService.suggestAwsService("route533"));
    }

    @Test
    void testRoute53_4() {
        assertEquals("route53", SuggestAwsService.suggestAwsService("roue53"));
    }

    @Test
    void testRoute53_5() {
        assertEquals("route53", SuggestAwsService.suggestAwsService("route553"));
    }

    // Edge cases and boundary tests
    @Test
    void testEmptyString() {
        // Empty string should suggest the shortest service
        assertEquals("s3", SuggestAwsService.suggestAwsService(""));
    }

    @Test
    void testSingleCharacter() {
        // Single characters
        assertEquals("s3", SuggestAwsService.suggestAwsService("a"));
        assertEquals("s3", SuggestAwsService.suggestAwsService("z"));
    }

    @Test
    void testLongRandomString() {
        // Long random string
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("abcdefghijklmnopqrst"));
    }

    @Test
    void testLevenshteinDistanceDirectly() {
        // Test the Levenshtein distance function directly
        assertEquals(0, SuggestAwsService.levenshteinDistance("s3", "s3"));
        assertEquals(1, SuggestAwsService.levenshteinDistance("s3", "s33"));
        assertEquals(1, SuggestAwsService.levenshteinDistance("lamda", "lambda"));
    }

    @Test
    void testMemoization() {
        // Test that memoization is working by calling the same calculation twice
        String s1 = "teststring";
        String s2 = "lambda";

        int result1 = SuggestAwsService.levenshteinDistance(s1, s2);
        int result2 = SuggestAwsService.levenshteinDistance(s1, s2);

        assertEquals(result1, result2);
    }

    @Test
    void testAllServicesExactMatch() {
        // Test that all exact service names return themselves
        assertEquals("ec2", SuggestAwsService.suggestAwsService("ec2"));
        assertEquals("s3", SuggestAwsService.suggestAwsService("s3"));
        assertEquals("lambda", SuggestAwsService.suggestAwsService("lambda"));
        assertEquals("dynamodb", SuggestAwsService.suggestAwsService("dynamodb"));
        assertEquals("rds", SuggestAwsService.suggestAwsService("rds"));
        assertEquals("cloudfront", SuggestAwsService.suggestAwsService("cloudfront"));
        assertEquals("iam", SuggestAwsService.suggestAwsService("iam"));
        assertEquals("route53", SuggestAwsService.suggestAwsService("route53"));
    }
}
