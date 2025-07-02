package src

import (
	"math"
)

// FindClosestValue finds the value in a sorted array that is closest to the target
// If multiple values are equally close, returns the smaller one
func FindClosestValue(arr []int, target int) int {
	// If array has only one element, return it
	if len(arr) == 1 {
		return arr[0]
	}

	left, right := 0, len(arr)-1

	// Binary search
	for left < right {
		mid := (left + right) / 2

		if arr[mid] == target {
			// Exact match found
			return arr[mid]
		} else if arr[mid] < target {
			left = mid + 1
		} else {
			right = mid
		}
	}

	// At this point, left == right
	candidate := arr[left]

	// Compare with previous element if it exists
	if left > 0 {
		prevVal := arr[left-1]
		distCandidate := int(math.Abs(float64(candidate - target)))
		distPrevVal := int(math.Abs(float64(prevVal - target)))

		// If previous value is closer, or equally close but smaller, return it
		if distPrevVal < distCandidate || (distPrevVal == distCandidate && prevVal < candidate) {
			candidate = prevVal
		}
	}

	return candidate
}
