package src

import (
	"sort"
)

// CountInRange counts the number of elements in a sorted array that fall within the given range [left, right]
func CountInRange(arr []int, left, right int) int {
	// Find the leftmost position where we can insert left (first element >= left)
	leftIndex := sort.SearchInts(arr, left)

	// Find the rightmost position where we can insert right+1 (first element > right)
	rightIndex := sort.SearchInts(arr, right+1)

	// Count of elements in range [left, right] is the difference
	return rightIndex - leftIndex
}
