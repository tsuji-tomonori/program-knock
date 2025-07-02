package src

// Log represents a connection log entry at a specific time
type Log struct {
	Time        int // Time 0 <= time <= 1000
	NConnect    int // Number of new connections at this time
	NDisconnect int // Number of new disconnections at this time
}

// CountConnectionsParam represents the parameters for counting connections
type CountConnectionsParam struct {
	EndTime int   // End time for aggregation
	Period  int   // Aggregation interval
	Logs    []Log // Connection logs
}

// CountConnections calculates the number of connections for each period
func CountConnections(param CountConnectionsParam) []int {
	// Initialize arrays to track connections and disconnections
	connect := make([]int, param.EndTime+1)
	disconnect := make([]int, param.EndTime+1)

	// Populate arrays with log data
	for _, log := range param.Logs {
		if log.Time <= param.EndTime {
			connect[log.Time] = log.NConnect
			disconnect[log.Time] = log.NDisconnect
		}
	}

	// Calculate connection count for each period
	var result []int
	for t := 0; t <= param.EndTime; t += param.Period {
		// Sum connections and disconnections up to time t
		totalConnect := 0
		totalDisconnect := 0

		for i := 0; i <= t; i++ {
			totalConnect += connect[i]
			totalDisconnect += disconnect[i]
		}

		// Current connection count = total connections - total disconnections
		currentConnections := totalConnect - totalDisconnect
		result = append(result, currentConnections)
	}

	return result
}
