<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\ServerLogAnalysis;

class ServerLogAnalysisTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertCount($expected, $actual, $message = ''): void
    {
        $count = count($actual);
        if ($count !== $expected) {
            throw new \AssertionError($message ?: "Expected count $expected, but got $count");
        }
    }

    private function assertIsArray($value, $message = ''): void
    {
        if (!is_array($value)) {
            throw new \AssertionError($message ?: "Expected array, but got " . gettype($value));
        }
    }

    public function testFilterSuccessfulRequestsSample(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.2", "POST /login", 401],
            ["192.168.0.1", "GET /about.html", 200],
            ["192.168.0.3", "GET /contact.html", 404]
        ];

        $result = ServerLogAnalysis::filterSuccessfulRequests($logs);

        $expected = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.1", "GET /about.html", 200]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testFilterSuccessfulRequestsEmpty(): void
    {
        $logs = [];
        $result = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertEquals([], $result);
    }

    public function testFilterSuccessfulRequestsNoSuccess(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 404],
            ["192.168.0.2", "POST /login", 401],
            ["192.168.0.3", "GET /about.html", 500]
        ];

        $result = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertEquals([], $result);
    }

    public function testFilterSuccessfulRequestsAllSuccess(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.2", "GET /about.html", 200],
            ["192.168.0.3", "GET /contact.html", 200]
        ];

        $result = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertEquals($logs, $result);
    }

    public function testCountRequestsByIpSample(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.2", "POST /login", 401],
            ["192.168.0.1", "GET /about.html", 200],
            ["192.168.0.3", "GET /contact.html", 404],
            ["192.168.0.1", "POST /submit", 500],
            ["192.168.0.2", "GET /dashboard", 200]
        ];

        $result = ServerLogAnalysis::countRequestsByIp($logs);

        $expected = [
            "192.168.0.1" => 3,
            "192.168.0.2" => 2,
            "192.168.0.3" => 1
        ];

        $this->assertEquals($expected, $result);
    }

    public function testCountRequestsByIpEmpty(): void
    {
        $logs = [];
        $result = ServerLogAnalysis::countRequestsByIp($logs);
        $this->assertEquals([], $result);
    }

    public function testCountRequestsByIpSingleRequest(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200]
        ];

        $result = ServerLogAnalysis::countRequestsByIp($logs);
        $expected = ["192.168.0.1" => 1];

        $this->assertEquals($expected, $result);
    }

    public function testCountRequestsByIpSameIp(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.1", "GET /about.html", 404],
            ["192.168.0.1", "POST /submit", 500],
            ["192.168.0.1", "GET /dashboard", 200]
        ];

        $result = ServerLogAnalysis::countRequestsByIp($logs);
        $expected = ["192.168.0.1" => 4];

        $this->assertEquals($expected, $result);
    }

    public function testFilterSuccessfulRequestsVariousStatusCodes(): void
    {
        $logs = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.2", "GET /about.html", 201],
            ["192.168.0.3", "GET /contact.html", 204],
            ["192.168.0.4", "GET /error.html", 404],
            ["192.168.0.5", "GET /server.html", 500],
            ["192.168.0.6", "GET /success.html", 200]
        ];

        $result = ServerLogAnalysis::filterSuccessfulRequests($logs);

        $expected = [
            ["192.168.0.1", "GET /index.html", 200],
            ["192.168.0.6", "GET /success.html", 200]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testCountRequestsByIpDifferentRequests(): void
    {
        $logs = [
            ["10.0.0.1", "GET /api/users", 200],
            ["10.0.0.2", "POST /api/login", 200],
            ["10.0.0.1", "PUT /api/users/1", 200],
            ["10.0.0.3", "DELETE /api/users/2", 404],
            ["10.0.0.2", "GET /api/profile", 401]
        ];

        $result = ServerLogAnalysis::countRequestsByIp($logs);

        $expected = [
            "10.0.0.1" => 2,
            "10.0.0.2" => 2,
            "10.0.0.3" => 1
        ];

        $this->assertEquals($expected, $result);
    }

    public function testComplexLogAnalysis(): void
    {
        $logs = [
            ["192.168.1.100", "GET /homepage", 200],
            ["192.168.1.101", "POST /api/data", 201],
            ["192.168.1.100", "GET /profile", 200],
            ["192.168.1.102", "GET /missing", 404],
            ["192.168.1.101", "PUT /api/update", 500],
            ["192.168.1.100", "DELETE /api/delete", 200],
            ["192.168.1.103", "GET /public", 200]
        ];

        // Test successful requests filtering
        $successfulRequests = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertCount(4, $successfulRequests);

        // Verify all are status 200
        foreach ($successfulRequests as $request) {
            $this->assertEquals(200, $request[2]);
        }

        // Test IP counting
        $ipCounts = ServerLogAnalysis::countRequestsByIp($logs);
        $this->assertEquals(3, $ipCounts["192.168.1.100"]);
        $this->assertEquals(2, $ipCounts["192.168.1.101"]);
        $this->assertEquals(1, $ipCounts["192.168.1.102"]);
        $this->assertEquals(1, $ipCounts["192.168.1.103"]);
    }

    public function testIPv6Addresses(): void
    {
        $logs = [
            ["2001:db8::1", "GET /index.html", 200],
            ["2001:db8::2", "POST /login", 401],
            ["2001:db8::1", "GET /about.html", 200],
            ["fe80::1", "GET /contact.html", 404]
        ];

        $successfulRequests = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertCount(2, $successfulRequests);

        $ipCounts = ServerLogAnalysis::countRequestsByIp($logs);
        $this->assertEquals(2, $ipCounts["2001:db8::1"]);
        $this->assertEquals(1, $ipCounts["2001:db8::2"]);
        $this->assertEquals(1, $ipCounts["fe80::1"]);
    }

    public function testSpecialRequestPaths(): void
    {
        $logs = [
            ["192.168.0.1", "GET /api/v1/users?limit=10&offset=0", 200],
            ["192.168.0.2", "POST /submit-form", 200],
            ["192.168.0.3", "GET /files/document.pdf", 200],
            ["192.168.0.4", "GET /search?q=test+query", 404]
        ];

        $successfulRequests = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $this->assertCount(3, $successfulRequests);

        $ipCounts = ServerLogAnalysis::countRequestsByIp($logs);
        $this->assertCount(4, $ipCounts);
    }

    public function testLargeLogDataset(): void
    {
        $logs = [];

        // Generate 1000 log entries
        for ($i = 0; $i < 1000; $i++) {
            $ip = "192.168." . ($i % 10) . "." . ($i % 100);
            $method = ($i % 2 === 0) ? "GET" : "POST";
            $path = "/path" . ($i % 50);
            $status = ($i % 3 === 0) ? 200 : (($i % 3 === 1) ? 404 : 500);

            $logs[] = [$ip, "$method $path", $status];
        }

        $startTime = microtime(true);
        $successfulRequests = ServerLogAnalysis::filterSuccessfulRequests($logs);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed for filtering: took " . ($endTime - $startTime) . " seconds");
        }

        $startTime = microtime(true);
        $ipCounts = ServerLogAnalysis::countRequestsByIp($logs);
        $endTime = microtime(true);

        // Performance check
        if ($endTime - $startTime >= 1.0) {
            throw new \AssertionError("Performance test failed for counting: took " . ($endTime - $startTime) . " seconds");
        }

        $this->assertIsArray($successfulRequests);
        $this->assertIsArray($ipCounts);
    }

    public function testCombinedOperations(): void
    {
        $logs = [
            ["user1.example.com", "GET /api/status", 200],
            ["user2.example.com", "POST /api/login", 401],
            ["user1.example.com", "GET /api/data", 200],
            ["user3.example.com", "GET /api/info", 500],
            ["user2.example.com", "GET /api/retry", 200]
        ];

        // First filter successful requests
        $successfulRequests = ServerLogAnalysis::filterSuccessfulRequests($logs);

        // Then count by IP on the filtered results
        $successfulIpCounts = ServerLogAnalysis::countRequestsByIp($successfulRequests);

        $expectedSuccessfulCounts = [
            "user1.example.com" => 2,
            "user2.example.com" => 1
        ];

        $this->assertEquals($expectedSuccessfulCounts, $successfulIpCounts);

        // Also test total counts
        $totalIpCounts = ServerLogAnalysis::countRequestsByIp($logs);
        $expectedTotalCounts = [
            "user1.example.com" => 2,
            "user2.example.com" => 2,
            "user3.example.com" => 1
        ];

        $this->assertEquals($expectedTotalCounts, $totalIpCounts);
    }
}
