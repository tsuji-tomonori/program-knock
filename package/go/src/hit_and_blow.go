package src

// HitAndBlowResult represents the result of Hit & Blow game
type HitAndBlowResult struct {
	Hits  int
	Blows int
}

// HitAndBlow performs Hit & Blow game judgment
func HitAndBlow(answer, guess []int) HitAndBlowResult {
	// Count hits (same position and same value)
	hits := 0
	for i := 0; i < len(answer); i++ {
		if answer[i] == guess[i] {
			hits++
		}
	}

	// Count frequency of each number in both arrays
	answerCounter := make(map[int]int)
	guessCounter := make(map[int]int)

	for _, num := range answer {
		answerCounter[num]++
	}

	for _, num := range guess {
		guessCounter[num]++
	}

	// Count total matches (regardless of position)
	totalMatches := 0
	for num, answerCount := range answerCounter {
		guessCount := guessCounter[num]
		if guessCount < answerCount {
			totalMatches += guessCount
		} else {
			totalMatches += answerCount
		}
	}

	// Blows = total matches - hits
	blows := totalMatches - hits

	return HitAndBlowResult{
		Hits:  hits,
		Blows: blows,
	}
}
