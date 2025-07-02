package tests

import (
	"fmt"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestFilterSuccessfulRequestsSample(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.2", "POST /login", 401},
		{"192.168.0.1", "GET /about.html", 200},
		{"192.168.0.3", "GET /contact.html", 404},
	}
	expected := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.1", "GET /about.html", 200},
	}
	assert.Equal(t, expected, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsEmpty(t *testing.T) {
	logs := []src.LogEntry{}
	assert.Equal(t, []src.LogEntry{}, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsNoneSuccessful(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /missing", 404},
		{"192.168.0.2", "POST /login", 401},
		{"192.168.0.3", "GET /error", 500},
	}
	assert.Equal(t, []src.LogEntry{}, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsAllSuccessful(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.2", "GET /about.html", 200},
		{"192.168.0.3", "POST /submit", 200},
	}
	assert.Equal(t, logs, src.FilterSuccessfulRequests(logs))
}

func TestCountRequestsByIPSample(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.2", "POST /login", 401},
		{"192.168.0.1", "GET /about.html", 200},
		{"192.168.0.3", "GET /contact.html", 404},
		{"192.168.0.1", "POST /submit", 500},
		{"192.168.0.2", "GET /dashboard", 200},
	}
	expected := map[string]int{"192.168.0.1": 3, "192.168.0.2": 2, "192.168.0.3": 1}
	assert.Equal(t, expected, src.CountRequestsByIP(logs))
}

func TestCountRequestsByIPEmpty(t *testing.T) {
	logs := []src.LogEntry{}
	assert.Equal(t, map[string]int{}, src.CountRequestsByIP(logs))
}

func TestCountRequestsByIPSingleIP(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /page1", 200},
		{"192.168.0.1", "GET /page2", 404},
		{"192.168.0.1", "POST /submit", 500},
	}
	expected := map[string]int{"192.168.0.1": 3}
	assert.Equal(t, expected, src.CountRequestsByIP(logs))
}

func TestCountRequestsByIPSingleRequest(t *testing.T) {
	logs := []src.LogEntry{{"192.168.0.1", "GET /index.html", 200}}
	expected := map[string]int{"192.168.0.1": 1}
	assert.Equal(t, expected, src.CountRequestsByIP(logs))
}

func TestFilterAndCountIntegration(t *testing.T) {
	logs := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.2", "POST /login", 401},
		{"192.168.0.1", "GET /about.html", 200},
		{"192.168.0.3", "GET /contact.html", 404},
		{"192.168.0.1", "POST /submit", 500},
		{"192.168.0.2", "GET /dashboard", 200},
	}

	// Test filtering first
	successful := src.FilterSuccessfulRequests(logs)
	expectedSuccessful := []src.LogEntry{
		{"192.168.0.1", "GET /index.html", 200},
		{"192.168.0.1", "GET /about.html", 200},
		{"192.168.0.2", "GET /dashboard", 200},
	}
	assert.Equal(t, expectedSuccessful, successful)

	// Test counting on successful requests
	successfulCounts := src.CountRequestsByIP(successful)
	expectedCounts := map[string]int{"192.168.0.1": 2, "192.168.0.2": 1}
	assert.Equal(t, expectedCounts, successfulCounts)
}

func TestVariousStatusCodes(t *testing.T) {
	logs := []src.LogEntry{
		{"10.0.0.1", "GET /", 200},
		{"10.0.0.1", "GET /missing", 404},
		{"10.0.0.2", "POST /login", 401},
		{"10.0.0.2", "GET /admin", 403},
		{"10.0.0.3", "POST /upload", 500},
		{"10.0.0.3", "GET /api", 200},
	}

	successful := src.FilterSuccessfulRequests(logs)
	assert.Equal(t, 2, len(successful))
	for _, log := range successful {
		assert.Equal(t, 200, log.StatusCode)
	}

	counts := src.CountRequestsByIP(logs)
	expectedCounts := map[string]int{"10.0.0.1": 2, "10.0.0.2": 2, "10.0.0.3": 2}
	assert.Equal(t, expectedCounts, counts)
}

func TestLargeDataset(t *testing.T) {
	// Create a larger dataset for performance testing
	var logs []src.LogEntry
	for i := 0; i < 1000; i++ {
		ip := fmt.Sprintf("192.168.0.%d", i%10)
		request := fmt.Sprintf("GET /page%d", i)
		status := 200
		if i%3 != 0 {
			status = 404
		}
		logs = append(logs, src.LogEntry{ip, request, status})
	}

	successful := src.FilterSuccessfulRequests(logs)
	assert.Equal(t, 334, len(successful)) // 1000 / 3 + 1 (0-indexed)

	counts := src.CountRequestsByIP(logs)
	assert.Equal(t, 10, len(counts)) // 10 unique IPs
	for _, count := range counts {
		assert.Equal(t, 100, count)
	}
}
