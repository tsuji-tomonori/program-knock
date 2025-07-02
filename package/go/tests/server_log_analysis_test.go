package tests

import (
	"fmt"
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestFilterSuccessfulRequestsSample(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.2", Request: "POST /login", StatusCode: 401},
		{IPAddress: "192.168.0.1", Request: "GET /about.html", StatusCode: 200},
		{IPAddress: "192.168.0.3", Request: "GET /contact.html", StatusCode: 404},
	}
	expected := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.1", Request: "GET /about.html", StatusCode: 200},
	}
	assert.Equal(t, expected, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsEmpty(t *testing.T) {
	logs := []src.LogEntry{}
	assert.Equal(t, []src.LogEntry{}, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsNoneSuccessful(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /missing", StatusCode: 404},
		{IPAddress: "192.168.0.2", Request: "POST /login", StatusCode: 401},
		{IPAddress: "192.168.0.3", Request: "GET /error", StatusCode: 500},
	}
	assert.Equal(t, []src.LogEntry{}, src.FilterSuccessfulRequests(logs))
}

func TestFilterSuccessfulRequestsAllSuccessful(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.2", Request: "GET /about.html", StatusCode: 200},
		{IPAddress: "192.168.0.3", Request: "POST /submit", StatusCode: 200},
	}
	assert.Equal(t, logs, src.FilterSuccessfulRequests(logs))
}

func TestCountRequestsByIPSample(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.2", Request: "POST /login", StatusCode: 401},
		{IPAddress: "192.168.0.1", Request: "GET /about.html", StatusCode: 200},
		{IPAddress: "192.168.0.3", Request: "GET /contact.html", StatusCode: 404},
		{IPAddress: "192.168.0.1", Request: "POST /submit", StatusCode: 500},
		{IPAddress: "192.168.0.2", Request: "GET /dashboard", StatusCode: 200},
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
		{IPAddress: "192.168.0.1", Request: "GET /page1", StatusCode: 200},
		{IPAddress: "192.168.0.1", Request: "GET /page2", StatusCode: 404},
		{IPAddress: "192.168.0.1", Request: "POST /submit", StatusCode: 500},
	}
	expected := map[string]int{"192.168.0.1": 3}
	assert.Equal(t, expected, src.CountRequestsByIP(logs))
}

func TestCountRequestsByIPSingleRequest(t *testing.T) {
	logs := []src.LogEntry{{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200}}
	expected := map[string]int{"192.168.0.1": 1}
	assert.Equal(t, expected, src.CountRequestsByIP(logs))
}

func TestFilterAndCountIntegration(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.2", Request: "POST /login", StatusCode: 401},
		{IPAddress: "192.168.0.1", Request: "GET /about.html", StatusCode: 200},
		{IPAddress: "192.168.0.3", Request: "GET /contact.html", StatusCode: 404},
		{IPAddress: "192.168.0.1", Request: "POST /submit", StatusCode: 500},
		{IPAddress: "192.168.0.2", Request: "GET /dashboard", StatusCode: 200},
	}

	// Test filtering first
	successful := src.FilterSuccessfulRequests(logs)
	expectedSuccessful := []src.LogEntry{
		{IPAddress: "192.168.0.1", Request: "GET /index.html", StatusCode: 200},
		{IPAddress: "192.168.0.1", Request: "GET /about.html", StatusCode: 200},
		{IPAddress: "192.168.0.2", Request: "GET /dashboard", StatusCode: 200},
	}
	assert.Equal(t, expectedSuccessful, successful)

	// Test counting on successful requests
	successfulCounts := src.CountRequestsByIP(successful)
	expectedCounts := map[string]int{"192.168.0.1": 2, "192.168.0.2": 1}
	assert.Equal(t, expectedCounts, successfulCounts)
}

func TestVariousStatusCodes(t *testing.T) {
	logs := []src.LogEntry{
		{IPAddress: "10.0.0.1", Request: "GET /", StatusCode: 200},
		{IPAddress: "10.0.0.1", Request: "GET /missing", StatusCode: 404},
		{IPAddress: "10.0.0.2", Request: "POST /login", StatusCode: 401},
		{IPAddress: "10.0.0.2", Request: "GET /admin", StatusCode: 403},
		{IPAddress: "10.0.0.3", Request: "POST /upload", StatusCode: 500},
		{IPAddress: "10.0.0.3", Request: "GET /api", StatusCode: 200},
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
		logs = append(logs, src.LogEntry{IPAddress: ip, Request: request, StatusCode: status})
	}

	successful := src.FilterSuccessfulRequests(logs)
	assert.Equal(t, 334, len(successful)) // 1000 / 3 + 1 (0-indexed)

	counts := src.CountRequestsByIP(logs)
	assert.Equal(t, 10, len(counts)) // 10 unique IPs
	for _, count := range counts {
		assert.Equal(t, 100, count)
	}
}
