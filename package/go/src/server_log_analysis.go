package src

// LogEntry represents a server log entry
type LogEntry struct {
	IPAddress  string // IP address
	Request    string // Request string
	StatusCode int    // HTTP status code
}

// FilterSuccessfulRequests filters logs to return only requests with status code 200.
//
// Parameters:
//   - logs: slice of LogEntry structs
//
// Returns:
//   - slice of logs with status code 200
func FilterSuccessfulRequests(logs []LogEntry) []LogEntry {
	result := make([]LogEntry, 0)
	for _, log := range logs {
		if log.StatusCode == 200 {
			result = append(result, log)
		}
	}
	return result
}

// CountRequestsByIP counts the number of requests per IP address.
//
// Parameters:
//   - logs: slice of LogEntry structs
//
// Returns:
//   - map of IP address to request count
func CountRequestsByIP(logs []LogEntry) map[string]int {
	ipCounts := make(map[string]int)
	for _, log := range logs {
		ipCounts[log.IPAddress]++
	}
	return ipCounts
}
