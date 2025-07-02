package src

import "math"

// Memoization cache for Levenshtein distance
var memoCache = make(map[string]int)

// LevenshteinDistance calculates the Levenshtein distance (edit distance) between two strings
// using recursion and memoization.
func LevenshteinDistance(s, t string) int {
	// Create a cache key from the two strings
	key := s + "|" + t
	if cached, exists := memoCache[key]; exists {
		return cached
	}

	var result int

	// Base case: if either string is empty, the distance is the length of the other string
	if len(s) == 0 {
		result = len(t)
	} else if len(t) == 0 {
		result = len(s)
	} else if s[len(s)-1] == t[len(t)-1] {
		// If the last characters are the same, recurse without cost
		result = LevenshteinDistance(s[:len(s)-1], t[:len(t)-1])
	} else {
		// If the last characters are different, try deletion, insertion, and substitution
		// and take the minimum, then add 1
		deletion := LevenshteinDistance(s[:len(s)-1], t)
		insertion := LevenshteinDistance(s, t[:len(t)-1])
		substitution := LevenshteinDistance(s[:len(s)-1], t[:len(t)-1])

		result = 1 + minThree(deletion, insertion, substitution)
	}

	// Cache the result
	memoCache[key] = result
	return result
}

// SuggestAWSService takes an incorrect AWS service name and suggests the most similar correct service name.
//
// Parameters:
//   - wrongService: incorrectly entered service name
//
// Returns:
//   - the most similar AWS service name
func SuggestAWSService(wrongService string) string {
	// List of supported AWS services
	services := []string{
		"ec2",
		"s3",
		"lambda",
		"dynamodb",
		"rds",
		"cloudfront",
		"iam",
		"route53",
	}

	// Initialize minimum Levenshtein distance and candidate service name
	minDistance := math.MaxInt32
	suggestion := services[0]

	// Calculate distance between each service and the incorrect input string, and select the closest one
	for _, service := range services {
		distance := LevenshteinDistance(wrongService, service)
		if distance < minDistance {
			minDistance = distance
			suggestion = service
		}
	}

	return suggestion
}

// minThree returns the minimum of three integers
func minThree(a, b, c int) int {
	if a <= b && a <= c {
		return a
	}
	if b <= c {
		return b
	}
	return c
}
