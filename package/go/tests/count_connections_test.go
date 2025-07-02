package tests

import (
	"testing"

	"github.com/stretchr/testify/assert"
	"program-knock/src"
)

func TestCountConnections(t *testing.T) {
	t.Run("Basic", func(t *testing.T) {
		// Basic test case
		// Count connections at each time interval
		// end_time=5, period=1 with multiple logs to aggregate connection counts
		// Expected result: [3, 5, 5, 5, 8, 6]
		param := src.CountConnectionsParam{
			EndTime: 5,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 3, NDisconnect: 0},
				{Time: 1, NConnect: 2, NDisconnect: 0},
				{Time: 4, NConnect: 5, NDisconnect: 2},
				{Time: 5, NConnect: 3, NDisconnect: 5},
			},
		}
		result := src.CountConnections(param)
		expected := []int{3, 5, 5, 5, 8, 6}
		assert.Equal(t, expected, result)
	})

	t.Run("NoLogs", func(t *testing.T) {
		// Test case when no logs exist
		// All connection counts should be 0
		param := src.CountConnectionsParam{
			EndTime: 3,
			Period:  1,
			Logs:    []src.Log{},
		}
		result := src.CountConnections(param)
		expected := []int{0, 0, 0, 0}
		assert.Equal(t, expected, result)
	})

	t.Run("GapLogs", func(t *testing.T) {
		// Test case when there are gaps between logs
		// end_time=6, period=2 with multiple logs to aggregate connection counts
		// Expected result: [0, 4, 4, 5]
		param := src.CountConnectionsParam{
			EndTime: 6,
			Period:  2,
			Logs: []src.Log{
				{Time: 1, NConnect: 4, NDisconnect: 0},
				{Time: 3, NConnect: 1, NDisconnect: 1},
				{Time: 6, NConnect: 3, NDisconnect: 2},
			},
		}
		result := src.CountConnections(param)
		expected := []int{0, 4, 4, 5}
		assert.Equal(t, expected, result)
	})

	t.Run("SingleLog", func(t *testing.T) {
		// Test case when only one log exists
		// end_time=5, period=1 with only one log to aggregate connection counts
		// Expected result: [5, 5, 5, 5, 5, 5]
		param := src.CountConnectionsParam{
			EndTime: 5,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 5, NDisconnect: 0},
			},
		}
		result := src.CountConnections(param)
		expected := []int{5, 5, 5, 5, 5, 5}
		assert.Equal(t, expected, result)
	})

	t.Run("AllDisconnect", func(t *testing.T) {
		// Test case when all connections are disconnected
		// All connections are disconnected at time 2, expected result: [10, 10, 0, 0, 0]
		param := src.CountConnectionsParam{
			EndTime: 4,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 10, NDisconnect: 0},
				{Time: 2, NConnect: 0, NDisconnect: 10},
			},
		}
		result := src.CountConnections(param)
		expected := []int{10, 10, 0, 0, 0}
		assert.Equal(t, expected, result)
	})

	t.Run("PartialDisconnect", func(t *testing.T) {
		// Test case when partial disconnections occur
		// end_time=4, period=1 with partial disconnections
		// Expected result: [10, 10, 5, 5, 5]
		param := src.CountConnectionsParam{
			EndTime: 4,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 10, NDisconnect: 0},
				{Time: 2, NConnect: 0, NDisconnect: 5},
			},
		}
		result := src.CountConnections(param)
		expected := []int{10, 10, 5, 5, 5}
		assert.Equal(t, expected, result)
	})

	t.Run("LargeConnectAndDisconnect", func(t *testing.T) {
		// Test case with very large number of connections followed by full disconnection
		// end_time=100, period=10 with massive connections followed by full disconnection
		// Expected result: [0, 10000000000, 10000000000, 10000000000, 0, 0, 0, 0, 0, 0, 0]
		largeNum := int(1e10)
		param := src.CountConnectionsParam{
			EndTime: 100,
			Period:  10,
			Logs: []src.Log{
				{Time: 10, NConnect: largeNum, NDisconnect: 0},
				{Time: 40, NConnect: 0, NDisconnect: largeNum},
			},
		}
		result := src.CountConnections(param)
		expected := []int{0, largeNum, largeNum, largeNum, 0, 0, 0, 0, 0, 0, 0}
		assert.Equal(t, expected, result)
	})

	t.Run("Output1000Entries", func(t *testing.T) {
		// Boundary test
		// end_time=1000, period=1 with output for each time interval
		// Verify that connection counts are correctly calculated for all times
		var logs []src.Log
		for i := 0; i <= 1000; i++ {
			logs = append(logs, src.Log{Time: i, NConnect: 1, NDisconnect: 0})
		}
		param := src.CountConnectionsParam{
			EndTime: 1000,
			Period:  1,
			Logs:    logs,
		}
		result := src.CountConnections(param)

		var expected []int
		for i := 0; i <= 1000; i++ {
			expected = append(expected, i+1)
		}
		assert.Equal(t, expected, result)
	})

	t.Run("BoundaryPeriodEqualsEndTime", func(t *testing.T) {
		// Test case when period equals end_time
		// end_time=10, period=10, expected result: [10, 10]
		param := src.CountConnectionsParam{
			EndTime: 10,
			Period:  10,
			Logs: []src.Log{
				{Time: 0, NConnect: 10, NDisconnect: 0},
			},
		}
		result := src.CountConnections(param)
		expected := []int{10, 10}
		assert.Equal(t, expected, result)
	})

	t.Run("OverlappingConnectionsAndDisconnections", func(t *testing.T) {
		// Test case when connections and disconnections overlap
		// Test situation where connections and disconnections occur at the same time
		param := src.CountConnectionsParam{
			EndTime: 5,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 5, NDisconnect: 0},
				{Time: 2, NConnect: 3, NDisconnect: 2}, // 3 connect, 2 disconnect at time 2
				{Time: 4, NConnect: 0, NDisconnect: 6}, // All disconnect at time 4
			},
		}
		result := src.CountConnections(param)
		expected := []int{5, 5, 6, 6, 0, 0}
		assert.Equal(t, expected, result)
	})

	t.Run("EdgeCaseZeroEndTime", func(t *testing.T) {
		// Edge case: end_time=0
		param := src.CountConnectionsParam{
			EndTime: 0,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 5, NDisconnect: 0},
			},
		}
		result := src.CountConnections(param)
		expected := []int{5}
		assert.Equal(t, expected, result)
	})

	t.Run("LogsOutsideTimeRange", func(t *testing.T) {
		// Test case with logs outside the time range
		param := src.CountConnectionsParam{
			EndTime: 5,
			Period:  1,
			Logs: []src.Log{
				{Time: 0, NConnect: 3, NDisconnect: 0},
				{Time: 10, NConnect: 5, NDisconnect: 0}, // This should be ignored
				{Time: 2, NConnect: 2, NDisconnect: 1},
			},
		}
		result := src.CountConnections(param)
		expected := []int{3, 3, 4, 4, 4, 4}
		assert.Equal(t, expected, result)
	})
}
