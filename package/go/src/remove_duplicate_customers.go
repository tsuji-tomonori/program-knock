package src

// RemoveDuplicateCustomers removes duplicate customer IDs while preserving the order of first occurrence
func RemoveDuplicateCustomers(customerIDs []int) []int {
	if len(customerIDs) == 0 {
		return []int{}
	}

	seen := make(map[int]bool)
	var uniqueIDs []int

	for _, cid := range customerIDs {
		if !seen[cid] {
			seen[cid] = true
			uniqueIDs = append(uniqueIDs, cid)
		}
	}

	return uniqueIDs
}
