package src

import (
	"strings"
)

// CountWordFrequencies counts the frequency of each word in the input text
func CountWordFrequencies(text string) map[string]int {
	// Return empty map for empty string
	if text == "" {
		return map[string]int{}
	}

	// Split text into words by spaces
	words := strings.Fields(text)

	// Count word frequencies
	frequencyMap := make(map[string]int)
	for _, word := range words {
		frequencyMap[word]++
	}

	return frequencyMap
}
