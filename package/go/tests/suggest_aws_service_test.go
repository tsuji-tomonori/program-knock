package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

// Sample test cases and boundary value tests

func TestSuggestAWSServiceSample1(t *testing.T) {
	// "lamda" is a misspelling of "lambda"
	assert.Equal(t, "lambda", src.SuggestAWSService("lamda"))
}

func TestSuggestAWSServiceSample2(t *testing.T) {
	// "s33" is a typo of "s3" (extra 3)
	assert.Equal(t, "s3", src.SuggestAWSService("s33"))
}

func TestSuggestAWSServiceSample3(t *testing.T) {
	// "rts" is close to "rds"
	assert.Equal(t, "rds", src.SuggestAWSService("rts"))
}

func TestSuggestAWSServiceSample4(t *testing.T) {
	// "cloudfrot" is a misspelling of "cloudfront"
	assert.Equal(t, "cloudfront", src.SuggestAWSService("cloudfrot"))
}

func TestSuggestAWSServiceExactMatch(t *testing.T) {
	// For correct service names, should return as-is
	assert.Equal(t, "ec2", src.SuggestAWSService("ec2"))
}

func TestSuggestAWSServiceMinLength(t *testing.T) {
	// Test for minimum length (1 character) input: suggest "s3" from "s"
	assert.Equal(t, "s3", src.SuggestAWSService("s"))
}

func TestSuggestAWSServiceMaxLength(t *testing.T) {
	// Test for maximum length (20 characters) input
	inputStr := "cloudfronntttttttttt" // "cloudfront" with extra characters
	assert.Equal(t, 20, len(inputStr))
	assert.Equal(t, "cloudfront", src.SuggestAWSService(inputStr))
}

func TestSuggestAWSServiceDynamo(t *testing.T) {
	// "dynamo" is close to "dynamodb"
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynamo"))
}

func TestSuggestAWSServiceIamm(t *testing.T) {
	// "iamm" is considered a misspelling of "iam"
	assert.Equal(t, "iam", src.SuggestAWSService("iamm"))
}

func TestSuggestAWSServiceLambdaExact(t *testing.T) {
	// When correct "lambda" is input, return as-is
	assert.Equal(t, "lambda", src.SuggestAWSService("lambda"))
}

func TestSuggestAWSServiceNoSimilarity(t *testing.T) {
	// When not very similar to any service, assume tie occurs and first in service list is returned
	assert.Equal(t, "ec2", src.SuggestAWSService("zzz"))
}

// Additional test cases for each service (5 cases each)

// ec2 tests
func TestSuggestAWSServiceEC2Case1(t *testing.T) {
	// Correct string
	assert.Equal(t, "ec2", src.SuggestAWSService("ec2"))
}

func TestSuggestAWSServiceEC2Case2(t *testing.T) {
	// Missing number
	assert.Equal(t, "ec2", src.SuggestAWSService("ec"))
}

func TestSuggestAWSServiceEC2Case3(t *testing.T) {
	// Extra character inserted
	assert.Equal(t, "ec2", src.SuggestAWSService("eec2"))
}

func TestSuggestAWSServiceEC2Case4(t *testing.T) {
	// Duplicated number
	assert.Equal(t, "ec2", src.SuggestAWSService("ec22"))
}

func TestSuggestAWSServiceEC2Case5(t *testing.T) {
	// Last character replaced with different number
	assert.Equal(t, "ec2", src.SuggestAWSService("ec3"))
}

// s3 tests
func TestSuggestAWSServiceS3Case1(t *testing.T) {
	// Correct string
	assert.Equal(t, "s3", src.SuggestAWSService("s3"))
}

func TestSuggestAWSServiceS3Case2(t *testing.T) {
	// Missing number
	assert.Equal(t, "s3", src.SuggestAWSService("s"))
}

func TestSuggestAWSServiceS3Case3(t *testing.T) {
	// Extra number (already in sample)
	assert.Equal(t, "s3", src.SuggestAWSService("s33"))
}

func TestSuggestAWSServiceS3Case4(t *testing.T) {
	// Extra character inserted
	assert.Equal(t, "s3", src.SuggestAWSService("ss3"))
}

func TestSuggestAWSServiceS3Case5(t *testing.T) {
	// Character order swapped
	assert.Equal(t, "s3", src.SuggestAWSService("3s"))
}

// lambda tests
func TestSuggestAWSServiceLambdaCase1(t *testing.T) {
	// Correct string
	assert.Equal(t, "lambda", src.SuggestAWSService("lambda"))
}

func TestSuggestAWSServiceLambdaCase2(t *testing.T) {
	// Some characters missing (sample)
	assert.Equal(t, "lambda", src.SuggestAWSService("lamda"))
}

func TestSuggestAWSServiceLambdaCase3(t *testing.T) {
	// Last character missing
	assert.Equal(t, "lambda", src.SuggestAWSService("lambd"))
}

func TestSuggestAWSServiceLambdaCase4(t *testing.T) {
	// Extra character inserted
	assert.Equal(t, "lambda", src.SuggestAWSService("lambdda"))
}

func TestSuggestAWSServiceLambdaCase5(t *testing.T) {
	// Middle character missing
	assert.Equal(t, "lambda", src.SuggestAWSService("labda"))
}

// dynamodb tests
func TestSuggestAWSServiceDynamoDBCase1(t *testing.T) {
	// Correct string
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynamodb"))
}

func TestSuggestAWSServiceDynamoDBCase2(t *testing.T) {
	// Partial input (insufficient)
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynamo"))
}

func TestSuggestAWSServiceDynamoDBCase3(t *testing.T) {
	// End missing
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynamod"))
}

func TestSuggestAWSServiceDynamoDBCase4(t *testing.T) {
	// Character order swapped
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynmodb"))
}

func TestSuggestAWSServiceDynamoDBCase5(t *testing.T) {
	// Extra character inserted
	assert.Equal(t, "dynamodb", src.SuggestAWSService("dynamodbb"))
}

// rds tests
func TestSuggestAWSServiceRDSCase1(t *testing.T) {
	// Correct string
	assert.Equal(t, "rds", src.SuggestAWSService("rds"))
}

func TestSuggestAWSServiceRDSCase2(t *testing.T) {
	// Some characters swapped (sample)
	assert.Equal(t, "rds", src.SuggestAWSService("rts"))
}

func TestSuggestAWSServiceRDSCase3(t *testing.T) {
	// Last character missing
	assert.Equal(t, "rds", src.SuggestAWSService("rd"))
}

func TestSuggestAWSServiceRDSCase4(t *testing.T) {
	// Extra character appended
	assert.Equal(t, "rds", src.SuggestAWSService("rdsd"))
}

func TestSuggestAWSServiceRDSCase5(t *testing.T) {
	// Number replaced
	assert.Equal(t, "rds", src.SuggestAWSService("r3s"))
}

// cloudfront tests
func TestSuggestAWSServiceCloudFrontCase1(t *testing.T) {
	// Correct string
	assert.Equal(t, "cloudfront", src.SuggestAWSService("cloudfront"))
}

func TestSuggestAWSServiceCloudFrontCase2(t *testing.T) {
	// Some characters swapped (sample)
	assert.Equal(t, "cloudfront", src.SuggestAWSService("cloudfrot"))
}

func TestSuggestAWSServiceCloudFrontCase3(t *testing.T) {
	// Characters missing
	assert.Equal(t, "cloudfront", src.SuggestAWSService("clodfront"))
}

func TestSuggestAWSServiceCloudFrontCase4(t *testing.T) {
	// Middle characters missing
	assert.Equal(t, "cloudfront", src.SuggestAWSService("cloudfrnt"))
}

func TestSuggestAWSServiceCloudFrontCase5(t *testing.T) {
	// Extra character at end
	assert.Equal(t, "cloudfront", src.SuggestAWSService("cloudfronte"))
}

// iam tests
func TestSuggestAWSServiceIAMCase1(t *testing.T) {
	// Correct string
	assert.Equal(t, "iam", src.SuggestAWSService("iam"))
}

func TestSuggestAWSServiceIAMCase2(t *testing.T) {
	// Extra character appended
	assert.Equal(t, "iam", src.SuggestAWSService("iamm"))
}

func TestSuggestAWSServiceIAMCase3(t *testing.T) {
	// Character missing
	assert.Equal(t, "iam", src.SuggestAWSService("im"))
}

func TestSuggestAWSServiceIAMCase4(t *testing.T) {
	// First character replaced
	assert.Equal(t, "iam", src.SuggestAWSService("aim"))
}

func TestSuggestAWSServiceIAMCase5(t *testing.T) {
	// Extra character in middle
	assert.Equal(t, "iam", src.SuggestAWSService("iaam"))
}

// route53 tests
func TestSuggestAWSServiceRoute53Case1(t *testing.T) {
	// Correct string
	assert.Equal(t, "route53", src.SuggestAWSService("route53"))
}

func TestSuggestAWSServiceRoute53Case2(t *testing.T) {
	// Character missing
	assert.Equal(t, "route53", src.SuggestAWSService("rout53"))
}

func TestSuggestAWSServiceRoute53Case3(t *testing.T) {
	// Extra character appended
	assert.Equal(t, "route53", src.SuggestAWSService("rout533"))
}

func TestSuggestAWSServiceRoute53Case4(t *testing.T) {
	// Last number missing
	assert.Equal(t, "route53", src.SuggestAWSService("route5"))
}

func TestSuggestAWSServiceRoute53Case5(t *testing.T) {
	// Middle character replaced
	assert.Equal(t, "route53", src.SuggestAWSService("rouet53"))
}
