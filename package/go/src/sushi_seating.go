package src

import (
	"strconv"
	"strings"
)

// SushiSeating implements a seat guidance system for a sushi restaurant.
//
// Parameters:
//   - commands: list of arrival and seating commands
//
// Returns:
//   - list of seated customers in order
func SushiSeating(commands []string) []string {
	waitingQueue := make([]string, 0)    // FIFO queue for waiting customers
	seatedCustomers := make([]string, 0) // List of seated customers in order

	for _, command := range commands {
		parts := strings.Split(command, " ")

		if len(parts) < 2 {
			continue
		}

		if parts[0] == "arrive" {
			name := parts[1]
			// Add to queue only if not already in queue
			if !contains(waitingQueue, name) {
				waitingQueue = append(waitingQueue, name)
			}

		} else if parts[0] == "seat" {
			n, err := strconv.Atoi(parts[1])
			if err != nil || n <= 0 {
				continue
			}
			// Seat up to n customers from the front of the queue
			seatedCount := minInt(n, len(waitingQueue))
			for i := 0; i < seatedCount; i++ {
				customer := waitingQueue[0]
				waitingQueue = waitingQueue[1:]
				seatedCustomers = append(seatedCustomers, customer)
			}
		}
	}

	return seatedCustomers
}

// contains checks if a string exists in a slice
func contains(slice []string, item string) bool {
	for _, s := range slice {
		if s == item {
			return true
		}
	}
	return false
}

// minInt returns the smaller of two integers
func minInt(a, b int) int {
	if a < b {
		return a
	}
	return b
}
