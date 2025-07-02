package src

// RunLengthEncodingResult represents a character and its count
type RunLengthEncodingResult struct {
	Char  string
	Count int
}

// RunLengthEncoding performs run-length encoding on a string
func RunLengthEncoding(s string) []RunLengthEncodingResult {
	if len(s) == 0 {
		return []RunLengthEncodingResult{}
	}

	var result []RunLengthEncodingResult
	currentChar := string(s[0])
	count := 1

	for i := 1; i < len(s); i++ {
		char := string(s[i])
		if char == currentChar {
			count++
		} else {
			// Character changed, add current group to result
			result = append(result, RunLengthEncodingResult{
				Char:  currentChar,
				Count: count,
			})
			currentChar = char
			count = 1
		}
	}

	// Add the last group
	result = append(result, RunLengthEncodingResult{
		Char:  currentChar,
		Count: count,
	})

	return result
}
