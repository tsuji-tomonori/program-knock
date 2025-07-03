<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\AwsServiceSuggester;

class AwsServiceSuggesterTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \AssertionError($message ?: "Expected true, but got false");
        }
    }

    private function assertFalse($condition, $message = ''): void
    {
        if ($condition) {
            throw new \AssertionError($message ?: "Expected false, but got true");
        }
    }

    public function testSampleCase1(): void
    {
        $result = AwsServiceSuggester::suggestService("lamda");
        $this->assertEquals("lambda", $result);
    }

    public function testSampleCase2(): void
    {
        $result = AwsServiceSuggester::suggestService("s33");
        $this->assertEquals("s3", $result);
    }

    public function testSampleCase3(): void
    {
        $result = AwsServiceSuggester::suggestService("rts");
        $this->assertEquals("rds", $result);
    }

    public function testSampleCase4(): void
    {
        $result = AwsServiceSuggester::suggestService("cloudfrot");
        $this->assertEquals("cloudfront", $result);
    }

    public function testExactMatch(): void
    {
        $result = AwsServiceSuggester::suggestService("lambda");
        $this->assertEquals("lambda", $result);
    }

    public function testAllServices(): void
    {
        $services = AwsServiceSuggester::getSupportedServices();
        foreach ($services as $service) {
            $result = AwsServiceSuggester::suggestService($service);
            $this->assertEquals($service, $result, "Failed for service: $service");
        }
    }

    public function testSingleCharacterErrors(): void
    {
        $this->assertEquals("s3", AwsServiceSuggester::suggestService("s"));
        $this->assertEquals("ec2", AwsServiceSuggester::suggestService("e"));
        $this->assertEquals("iam", AwsServiceSuggester::suggestService("i"));
    }

    public function testTranspositionErrors(): void
    {
        $this->assertEquals("ec2", AwsServiceSuggester::suggestService("ce2"));
        $this->assertEquals("rds", AwsServiceSuggester::suggestService("drs"));
        $this->assertEquals("iam", AwsServiceSuggester::suggestService("aim"));
    }

    public function testInsertionErrors(): void
    {
        $this->assertEquals("s3", AwsServiceSuggester::suggestService("s3x"));
        $this->assertEquals("ec2", AwsServiceSuggester::suggestService("ec2x"));
        $this->assertEquals("rds", AwsServiceSuggester::suggestService("rdsx"));
    }

    public function testDeletionErrors(): void
    {
        $this->assertEquals("lambda", AwsServiceSuggester::suggestService("ambda"));
        $this->assertEquals("dynamodb", AwsServiceSuggester::suggestService("ynamodb"));
        $this->assertEquals("cloudfront", AwsServiceSuggester::suggestService("loudfront"));
    }

    public function testSubstitutionErrors(): void
    {
        $this->assertEquals("lambda", AwsServiceSuggester::suggestService("lambdx"));
        $this->assertEquals("dynamodb", AwsServiceSuggester::suggestService("dynamodx"));
        $this->assertEquals("route53", AwsServiceSuggester::suggestService("route52"));
    }

    public function testMultipleErrors(): void
    {
        $this->assertEquals("cloudfront", AwsServiceSuggester::suggestService("clodfrnt"));
        $this->assertEquals("dynamodb", AwsServiceSuggester::suggestService("dynmdb"));
        $this->assertEquals("route53", AwsServiceSuggester::suggestService("rout5"));
    }

    public function testLevenshteinDistance(): void
    {
        $this->assertEquals(0, AwsServiceSuggester::levenshteinDistance("lambda", "lambda"));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("lamda", "lambda"));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("s33", "s3"));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("rts", "rds"));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("cloudfrot", "cloudfront"));
    }

    public function testLevenshteinDistanceKnownCases(): void
    {
        // Classic examples
        $this->assertEquals(3, AwsServiceSuggester::levenshteinDistance("kitten", "sitting"));
        $this->assertEquals(3, AwsServiceSuggester::levenshteinDistance("saturday", "sunday"));
        $this->assertEquals(2, AwsServiceSuggester::levenshteinDistance("hello", "help"));
    }

    public function testLevenshteinDistanceEdgeCases(): void
    {
        $this->assertEquals(0, AwsServiceSuggester::levenshteinDistance("", ""));
        $this->assertEquals(3, AwsServiceSuggester::levenshteinDistance("", "abc"));
        $this->assertEquals(3, AwsServiceSuggester::levenshteinDistance("abc", ""));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("a", ""));
        $this->assertEquals(1, AwsServiceSuggester::levenshteinDistance("", "a"));
    }

    public function testCompareWithBuiltIn(): void
    {
        $testCases = [
            ["lambda", "lamda"],
            ["s3", "s33"],
            ["rds", "rts"],
            ["cloudfront", "cloudfrot"],
            ["kitten", "sitting"],
            ["", ""],
            ["abc", ""],
            ["", "abc"],
            ["hello", "world"],
            ["algorithm", "altruistic"]
        ];

        foreach ($testCases as [$str1, $str2]) {
            $this->assertTrue(
                AwsServiceSuggester::compareWithBuiltIn($str1, $str2),
                "Built-in comparison failed for '$str1' and '$str2'"
            );
        }
    }

    public function testSuggestWithDistance(): void
    {
        $result = AwsServiceSuggester::suggestWithDistance("lamda");
        $this->assertEquals("lambda", $result['service']);
        $this->assertEquals(1, $result['distance']);

        $result = AwsServiceSuggester::suggestWithDistance("cloudfrot");
        $this->assertEquals("cloudfront", $result['service']);
        $this->assertEquals(1, $result['distance']);
    }

    public function testCalculateAllDistances(): void
    {
        $distances = AwsServiceSuggester::calculateAllDistances("lamda");

        $this->assertEquals(1, $distances['lambda']);
        $this->assertTrue($distances['s3'] > 1);
        $this->assertTrue($distances['ec2'] > 1);
        $this->assertTrue($distances['iam'] > 1);
    }

    public function testSuggestMultipleServices(): void
    {
        $wrongServices = ["lamda", "s33", "rts", "cloudfrot"];
        $results = AwsServiceSuggester::suggestMultipleServices($wrongServices);
        $expected = ["lambda", "s3", "rds", "cloudfront"];

        $this->assertEquals($expected, $results);
    }

    public function testValidateInput(): void
    {
        $this->assertTrue(AwsServiceSuggester::validateInput("lambda"));
        $this->assertTrue(AwsServiceSuggester::validateInput("s"));
        $this->assertTrue(AwsServiceSuggester::validateInput("ec"));
        $this->assertTrue(AwsServiceSuggester::validateInput("verylongservicename"));

        $this->assertFalse(AwsServiceSuggester::validateInput(""));
        $this->assertFalse(AwsServiceSuggester::validateInput("Lambda")); // 大文字
        $this->assertFalse(AwsServiceSuggester::validateInput("lambda-service")); // ハイフン
        $this->assertFalse(AwsServiceSuggester::validateInput("lambda123")); // 数字
        $this->assertFalse(AwsServiceSuggester::validateInput("verylongservicenamethatexceedstwentycharacters")); // 20文字超
    }

    public function testGetSupportedServices(): void
    {
        $services = AwsServiceSuggester::getSupportedServices();
        $expected = ['ec2', 's3', 'lambda', 'dynamodb', 'rds', 'cloudfront', 'iam', 'route53'];

        $this->assertEquals($expected, $services);
        $this->assertEquals(8, count($services));
    }

    public function testComplexTypos(): void
    {
        // Multiple character errors
        $this->assertEquals("cloudfront", AwsServiceSuggester::suggestService("cloudfrnt"));
        $this->assertEquals("dynamodb", AwsServiceSuggester::suggestService("dynamobd"));
        $this->assertEquals("route53", AwsServiceSuggester::suggestService("route35"));

        // Completely wrong but closest match
        $this->assertEquals("ec2", AwsServiceSuggester::suggestService("xyz")); // Should be shortest service
    }

    public function testTieBreaking(): void
    {
        // When multiple services have the same distance, choose lexicographically first
        $distances = AwsServiceSuggester::calculateAllDistances("ab");

        // Find services with minimum distance
        $minDistance = min($distances);
        $closestServices = array_keys(array_filter($distances, fn($d) => $d === $minDistance));

        $result = AwsServiceSuggester::suggestService("ab");

        // Result should be the lexicographically first among the closest
        sort($closestServices);
        $this->assertEquals($closestServices[0], $result);
    }

    public function testPerformanceTest(): void
    {
        $longInputs = [];
        for ($i = 0; $i < 100; $i++) {
            $longInputs[] = str_repeat("a", 20); // Maximum length inputs
        }

        $startTime = microtime(true);
        $results = AwsServiceSuggester::suggestMultipleServices($longInputs);
        $endTime = microtime(true);

        // Performance check - should complete within reasonable time
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertEquals(100, count($results));

        // All results should be valid AWS services
        $validServices = AwsServiceSuggester::getSupportedServices();
        foreach ($results as $result) {
            $this->assertTrue(in_array($result, $validServices), "Invalid service suggested: $result");
        }
    }

    public function testRandomizedTesting(): void
    {
        $services = AwsServiceSuggester::getSupportedServices();

        for ($i = 0; $i < 50; $i++) {
            // Pick a random service and introduce random errors
            $originalService = $services[array_rand($services)];

            // Introduce a single character error
            $errorTypes = ['insert', 'delete', 'substitute'];
            $errorType = $errorTypes[array_rand($errorTypes)];

            $wrongService = $originalService;

            switch ($errorType) {
                case 'insert':
                    $pos = rand(0, strlen($wrongService));
                    $wrongService = substr($wrongService, 0, $pos) . 'x' . substr($wrongService, $pos);
                    break;

                case 'delete':
                    if (strlen($wrongService) > 1) {
                        $pos = rand(0, strlen($wrongService) - 1);
                        $wrongService = substr($wrongService, 0, $pos) . substr($wrongService, $pos + 1);
                    }
                    break;

                case 'substitute':
                    $pos = rand(0, strlen($wrongService) - 1);
                    $wrongService[$pos] = 'x';
                    break;
            }

            $suggestion = AwsServiceSuggester::suggestService($wrongService);

            // The suggestion should be a valid service
            $this->assertTrue(in_array($suggestion, $services), "Invalid suggestion for '$wrongService': $suggestion");

            // For single errors, the original service should be among the closest
            $distance = AwsServiceSuggester::levenshteinDistance($wrongService, $originalService);
            $suggestionDistance = AwsServiceSuggester::levenshteinDistance($wrongService, $suggestion);

            $this->assertTrue($suggestionDistance <= $distance, "Suggestion distance should be minimal");
        }
    }

    public function testEdgeCaseInputs(): void
    {
        // Very short inputs
        $this->assertTrue(in_array(AwsServiceSuggester::suggestService("a"), AwsServiceSuggester::getSupportedServices()));
        $this->assertTrue(in_array(AwsServiceSuggester::suggestService("z"), AwsServiceSuggester::getSupportedServices()));

        // Maximum length input
        $longInput = str_repeat("a", 20);
        $this->assertTrue(in_array(AwsServiceSuggester::suggestService($longInput), AwsServiceSuggester::getSupportedServices()));
    }

    public function testConsistencyCheck(): void
    {
        $testInputs = ["lamda", "s33", "rts", "cloudfrot"];

        // Multiple calls should return same result
        foreach ($testInputs as $input) {
            $result1 = AwsServiceSuggester::suggestService($input);
            $result2 = AwsServiceSuggester::suggestService($input);
            $result3 = AwsServiceSuggester::suggestService($input);

            $this->assertEquals($result1, $result2);
            $this->assertEquals($result2, $result3);
        }
    }

    public function testDistanceProperties(): void
    {
        // Distance should be symmetric
        $str1 = "lambda";
        $str2 = "lamda";

        $distance1 = AwsServiceSuggester::levenshteinDistance($str1, $str2);
        $distance2 = AwsServiceSuggester::levenshteinDistance($str2, $str1);

        $this->assertEquals($distance1, $distance2);

        // Distance to self should be 0
        foreach (AwsServiceSuggester::getSupportedServices() as $service) {
            $this->assertEquals(0, AwsServiceSuggester::levenshteinDistance($service, $service));
        }

        // Triangle inequality (simplified check)
        $services = AwsServiceSuggester::getSupportedServices();
        $a = $services[0];
        $b = $services[1];
        $c = $services[2];

        $ab = AwsServiceSuggester::levenshteinDistance($a, $b);
        $bc = AwsServiceSuggester::levenshteinDistance($b, $c);
        $ac = AwsServiceSuggester::levenshteinDistance($a, $c);

        $this->assertTrue($ac <= $ab + $bc, "Triangle inequality violated");
    }
}
